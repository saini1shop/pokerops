<?php
/**
 * API Endpoint for Landing Page Form Submissions
 * POST /api/landing/signup
 */

require_once __DIR__ . '/../../includes/bootstrap.php';

use App\Database;
use App\Services\AiSensyService;
use PDO;

header('Content-Type: application/json');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    $db = Database::getInstance();
    
    // Get form data
    $landingPageId = filter_input(INPUT_POST, 'landing_page_id', FILTER_VALIDATE_INT);
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $stateId = filter_input(INPUT_POST, 'state_id', FILTER_VALIDATE_INT);
    $whatsappConsent = isset($_POST['whatsapp_consent']) ? 1 : 0;
    $marketingConsent = isset($_POST['marketing_consent']) ? 1 : 0;
    
    // Validation
    $errors = [];
    
    if (!$landingPageId) {
        $errors[] = 'Invalid landing page';
    }
    
    if (empty($name) || strlen($name) < 2) {
        $errors[] = 'Please enter a valid name';
    }
    
    // Sanitize phone number
    $phone = preg_replace('/\D/', '', $phone);
    if (strlen($phone) === 12 && str_starts_with($phone, '91')) {
        $phone = substr($phone, 2);
    }
    if (strlen($phone) !== 10) {
        $errors[] = 'Please enter a valid 10-digit phone number';
    }
    
    if (!$stateId) {
        $errors[] = 'Please select your state';
    }
    
    if (!$whatsappConsent) {
        $errors[] = 'WhatsApp consent is required';
    }
    
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
        exit;
    }
    
    // Verify landing page exists and is published
    $stmt = $db->prepare("SELECT id FROM igp_landing_pages WHERE id = ? AND status = 'published' AND is_active = 1");
    $stmt->execute([$landingPageId]);
    if (!$stmt->fetch()) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Landing page not found']);
        exit;
    }
    
    // Check for existing player by phone
    $stmt = $db->prepare("SELECT id FROM igp_players WHERE phone = ?");
    $stmt->execute([$phone]);
    $existingPlayer = $stmt->fetch();
    $playerId = $existingPlayer ? $existingPlayer['id'] : null;
    
    // Get UTM params from session if available
    $utmSource = $_SESSION['landing_utms']['utm_source'] ?? $_GET['utm_source'] ?? null;
    $utmMedium = $_SESSION['landing_utms']['utm_medium'] ?? $_GET['utm_medium'] ?? null;
    $utmCampaign = $_SESSION['landing_utms']['utm_campaign'] ?? $_GET['utm_campaign'] ?? null;
    $utmContent = $_SESSION['landing_utms']['utm_content'] ?? $_GET['utm_content'] ?? null;
    $utmTerm = $_SESSION['landing_utms']['utm_term'] ?? $_GET['utm_term'] ?? null;
    
    // Get campaign ID if UTMs match an active campaign
    $campaignId = null;
    if ($utmCampaign) {
        $stmt = $db->prepare("SELECT id FROM igp_campaigns WHERE status = 'active' AND name LIKE ? LIMIT 1");
        $stmt->execute(['%' . $utmCampaign . '%']);
        $campaign = $stmt->fetch();
        $campaignId = $campaign ? $campaign['id'] : null;
    }
    
    // Capture IP and user agent
    $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? null;
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
    
    // Insert signup record
    $stmt = $db->prepare("INSERT INTO igp_player_signups 
        (player_id, landing_page_id, campaign_id, name, phone, email, state_id, 
         whatsapp_consent, marketing_consent, 
         utm_source, utm_medium, utm_campaign, utm_content, utm_term,
         ip_address, user_agent) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, INET6_ATON(?), ?)");
    
    $stmt->execute([
        $playerId, $landingPageId, $campaignId, $name, $phone, $email, $stateId,
        $whatsappConsent, $marketingConsent,
        $utmSource, $utmMedium, $utmCampaign, $utmContent, $utmTerm,
        $ipAddress, $userAgent
    ]);
    
    $signupId = $db->lastInsertId();
    
    // Upsert player record
    if ($existingPlayer) {
        // Update existing player
        $stmt = $db->prepare("UPDATE igp_players SET 
            name = ?, 
            email = COALESCE(NULLIF(?, ''), email),
            state_id = COALESCE(?, state_id),
            whatsapp_consent = ?, 
            marketing_consent = ?,
            last_consent_at = NOW()
            WHERE id = ?");
        $stmt->execute([$name, $email, $stateId, $whatsappConsent, $marketingConsent, $playerId]);
    } else {
        // Create new player
        $stmt = $db->prepare("INSERT INTO igp_players 
            (name, phone, email, state_id, whatsapp_consent, marketing_consent, last_consent_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$name, $phone, $email, $stateId, $whatsappConsent, $marketingConsent]);
        $playerId = $db->lastInsertId();
        
        // Update signup with new player_id
        $stmt = $db->prepare("UPDATE igp_player_signups SET player_id = ? WHERE id = ?");
        $stmt->execute([$playerId, $signupId]);
    }
    
    // Log consent
    $stmt = $db->prepare("INSERT INTO igp_consent_logs 
        (player_id, consent_type, previous_value, new_value, source, evidence, ip_address) 
        SELECT ?, 'whatsapp', whatsapp_consent, ?, 'landing_page', ?, INET6_ATON(?) 
        FROM igp_players WHERE id = ?");
    $stmt->execute([$playerId, $whatsappConsent, json_encode(['landing_page_id' => $landingPageId]), $ipAddress, $playerId]);
    
    if ($marketingConsent) {
        $stmt = $db->prepare("INSERT INTO igp_consent_logs 
            (player_id, consent_type, previous_value, new_value, source, evidence, ip_address) 
            SELECT ?, 'marketing', marketing_consent, ?, 'landing_page', ?, INET6_ATON(?) 
            FROM igp_players WHERE id = ?");
        $stmt->execute([$playerId, $marketingConsent, json_encode(['landing_page_id' => $landingPageId]), $ipAddress, $playerId]);
    }
    
    // Log UTM data
    if ($utmSource || $utmMedium || $utmCampaign) {
        $stmt = $db->prepare("INSERT INTO igp_utm_logs 
            (signup_id, player_id, utm_source, utm_medium, utm_campaign, utm_content, utm_term, raw_query) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $rawQuery = http_build_query($_GET);
        $stmt->execute([$signupId, $playerId, $utmSource, $utmMedium, $utmCampaign, $utmContent, $utmTerm, $rawQuery]);
    }
    
    // Clear session UTM data
    unset($_SESSION['landing_utms'], $_SESSION['landing_page_id']);
    
    // Trigger WhatsApp welcome message for new players
    if (!$existingPlayer && $whatsappConsent) {
        try {
            $whatsapp = new AiSensyService();
            $welcomeResult = $whatsapp->sendTemplate(
                $playerId, 
                'welcome', 
                [$name], 
                $campaignId
            );
            
            if ($welcomeResult['success']) {
                error_log("Welcome WhatsApp sent to player {$playerId}");
            } else {
                error_log("Failed to send welcome WhatsApp to player {$playerId}: " . $welcomeResult['error']);
            }
            
            // Queue community invite for 5 minutes later (if state has community)
            // In production, use a proper queue (Redis, RabbitMQ, etc.)
            // For now, just log the intent
            if ($stateId) {
                $stmt = $db->prepare("SELECT id FROM igp_communities 
                    WHERE state_id = ? AND community_type = 'geo' AND is_active = 1 
                    LIMIT 1");
                $stmt->execute([$stateId]);
                $community = $stmt->fetch();
                
                if ($community) {
                    // Create pending invite record
                    $stmt = $db->prepare("INSERT INTO igp_community_invites 
                        (player_id, community_id, campaign_id, status) 
                        VALUES (?, ?, ?, 'pending')
                        ON DUPLICATE KEY UPDATE 
                        updated_at = NOW()");
                    $stmt->execute([$playerId, $community['id'], $campaignId]);
                    
                    error_log("Community invite queued for player {$playerId} to community {$community['id']}");
                }
            }
            
        } catch (Exception $e) {
            // Log but don't fail the signup
            error_log("WhatsApp send error: " . $e->getMessage());
        }
    }
    
    echo json_encode([
        'success' => true, 
        'message' => 'Thank you for signing up! We will contact you shortly.',
        'player_id' => $playerId,
        'signup_id' => $signupId
    ]);
    
} catch (Exception $e) {
    error_log('Landing signup error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again later.']);
}
