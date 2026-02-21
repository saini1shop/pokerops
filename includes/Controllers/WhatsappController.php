<?php
namespace App\Controllers;

use App\Database;
use App\Services\AiSensyService;
use PDO;

class WhatsappController {
    private AiSensyService $aisensy;
    
    public function __construct() {
        $this->aisensy = new AiSensyService();
    }
    
    /**
     * Show WhatsApp composer UI
     */
    public function compose(): void {
        $db = Database::getInstance();
        
        // Get available templates
        $templates = $this->aisensy->getTemplates();
        
        // Get recent player list (for quick select)
        $stmt = $db->query("SELECT id, name, phone, city FROM igp_players 
            WHERE whatsapp_consent = 1 
            ORDER BY created_at DESC 
            LIMIT 50");
        $recentPlayers = $stmt->fetchAll();
        
        // Get communities for filtering
        $stmt = $db->query("SELECT id, name, state_id FROM igp_communities WHERE is_active = 1 ORDER BY name");
        $communities = $stmt->fetchAll();
        
        // Get states for filtering
        $stmt = $db->query("SELECT id, name FROM igp_states WHERE status = 'active' ORDER BY name");
        $states = $stmt->fetchAll();
        
        // Get recent WhatsApp activity
        $stmt = $db->prepare("SELECT wl.*, p.name as player_name 
            FROM igp_whatsapp_logs wl 
            JOIN igp_players p ON wl.player_id = p.id 
            ORDER BY wl.created_at DESC 
            LIMIT 20");
        $stmt->execute();
        $recentMessages = $stmt->fetchAll();
        
        $title = 'Send WhatsApp';
        $contentView = __DIR__ . '/../../views/admin/whatsapp/compose.php';
        $user = [
            'id' => $_SESSION['admin_user_id'],
            'name' => $_SESSION['admin_user_name'],
            'role' => $_SESSION['admin_user_role'],
        ];
        
        include __DIR__ . '/../../views/admin/layout.php';
    }
    
    /**
     * Send WhatsApp message to single player
     */
    public function send(): void {
        header('Content-Type: application/json');
        
        $playerId = filter_input(INPUT_POST, 'player_id', FILTER_VALIDATE_INT);
        $template = $_POST['template'] ?? '';
        $customMessage = $_POST['custom_message'] ?? '';
        $campaignId = filter_input(INPUT_POST, 'campaign_id', FILTER_VALIDATE_INT);
        
        if (!$playerId || !$template) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Player and template required']);
            return;
        }
        
        // Get player details for template parameters
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT name, assigned_location_id FROM igp_players WHERE id = ?");
        $stmt->execute([$playerId]);
        $player = $stmt->fetch();
        
        if (!$player) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Player not found']);
            return;
        }
        
        // Build template parameters based on template type
        $params = $this->buildTemplateParams($template, $player, $_POST);
        
        // Send via AiSensy
        $result = $this->aisensy->sendTemplate($playerId, $template, $params, $campaignId);
        
        echo json_encode($result);
    }
    
    /**
     * Send bulk WhatsApp messages
     */
    public function sendBulk(): void {
        header('Content-Type: application/json');
        
        $playerIds = $_POST['player_ids'] ?? [];
        $template = $_POST['template'] ?? '';
        $campaignId = filter_input(INPUT_POST, 'campaign_id', FILTER_VALIDATE_INT);
        
        if (empty($playerIds) || !$template) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Players and template required']);
            return;
        }
        
        // Limit bulk size
        if (count($playerIds) > 100) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Maximum 100 players at a time']);
            return;
        }
        
        // Get template parameters (use first player for defaults)
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT name FROM igp_players WHERE id = ?");
        $stmt->execute([$playerIds[0]]);
        $player = $stmt->fetch();
        
        $params = $this->buildTemplateParams($template, $player, $_POST);
        
        // Send bulk
        $result = $this->aisensy->sendBulk($playerIds, $template, $params, $campaignId);
        
        echo json_encode([
            'success' => $result['failed'] === 0,
            'summary' => $result,
        ]);
    }
    
    /**
     * Search players for WhatsApp
     */
    public function searchPlayers(): void {
        header('Content-Type: application/json');
        
        $query = $_GET['q'] ?? '';
        $stateId = filter_input($_GET, 'state_id', FILTER_VALIDATE_INT);
        $communityId = filter_input($_GET, 'community_id', FILTER_VALIDATE_INT);
        
        $db = Database::getInstance();
        
        $sql = "SELECT p.id, p.name, p.phone, p.city, s.name as state_name 
            FROM igp_players p 
            LEFT JOIN igp_states s ON p.state_id = s.id 
            WHERE p.whatsapp_consent = 1 
            AND NOT EXISTS (
                SELECT 1 FROM igp_opt_outs oo 
                WHERE oo.player_id = p.id AND oo.channel = 'whatsapp' AND oo.cleared_at IS NULL
            )";
        $params = [];
        
        if ($query) {
            $sql .= " AND (p.name LIKE ? OR p.phone LIKE ?)";
            $params[] = "%{$query}%";
            $params[] = "%{$query}%";
        }
        
        if ($stateId) {
            $sql .= " AND p.state_id = ?";
            $params[] = $stateId;
        }
        
        if ($communityId) {
            $sql .= " AND EXISTS (
                SELECT 1 FROM igp_community_invites ci 
                WHERE ci.player_id = p.id AND ci.community_id = ? AND ci.status = 'joined'
            )";
            $params[] = $communityId;
        }
        
        $sql .= " ORDER BY p.name LIMIT 100";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $players = $stmt->fetchAll();
        
        echo json_encode(['players' => $players]);
    }
    
    /**
     * Show message history/logs
     */
    public function logs(): void {
        $db = Database::getInstance();
        
        $page = filter_input($_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
        $perPage = 50;
        $offset = ($page - 1) * $perPage;
        
        // Get logs with pagination
        $stmt = $db->prepare("SELECT wl.*, p.name as player_name, p.phone 
            FROM igp_whatsapp_logs wl 
            JOIN igp_players p ON wl.player_id = p.id 
            ORDER BY wl.created_at DESC 
            LIMIT ? OFFSET ?");
        $stmt->execute([$perPage, $offset]);
        $logs = $stmt->fetchAll();
        
        // Get total count
        $stmt = $db->query("SELECT COUNT(*) FROM igp_whatsapp_logs");
        $total = $stmt->fetchColumn();
        
        $totalPages = ceil($total / $perPage);
        
        $title = 'WhatsApp Logs';
        $contentView = __DIR__ . '/../../views/admin/whatsapp/logs.php';
        $user = [
            'id' => $_SESSION['admin_user_id'],
            'name' => $_SESSION['admin_user_name'],
            'role' => $_SESSION['admin_user_role'],
        ];
        
        include __DIR__ . '/../../views/admin/layout.php';
    }
    
    /**
     * Handle AiSensy webhook for status updates
     */
    public function webhook(): void {
        header('Content-Type: application/json');
        
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (!$data) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
            return;
        }
        
        $success = $this->aisensy->processWebhook($data);
        
        echo json_encode(['success' => $success]);
    }
    
    /**
     * Send community invite to player
     */
    public function sendCommunityInvite(int $playerId, int $communityId, ?int $campaignId = null): array {
        $db = Database::getInstance();
        
        // Get community invite link
        $stmt = $db->prepare("SELECT name, invite_link FROM igp_communities WHERE id = ?");
        $stmt->execute([$communityId]);
        $community = $stmt->fetch();
        
        if (!$community || !$community['invite_link']) {
            return ['success' => false, 'error' => 'Community not found or no invite link'];
        }
        
        // Get player
        $stmt = $db->prepare("SELECT name FROM igp_players WHERE id = ?");
        $stmt->execute([$playerId]);
        $player = $stmt->fetch();
        
        if (!$player) {
            return ['success' => false, 'error' => 'Player not found'];
        }
        
        // Send via AiSensy
        $template = 'community_invite';
        $params = [
            $player['name'],
            $community['invite_link'],
        ];
        
        $result = $this->aisensy->sendTemplate($playerId, $template, $params, $campaignId);
        
        if ($result['success']) {
            // Update or create invite record
            $stmt = $db->prepare("INSERT INTO igp_community_invites 
                (player_id, community_id, campaign_id, status, invite_link_sent_at) 
                VALUES (?, ?, ?, 'sent', NOW())
                ON DUPLICATE KEY UPDATE 
                status = 'sent', 
                invite_link_sent_at = NOW(),
                updated_at = NOW()");
            $stmt->execute([$playerId, $communityId, $campaignId]);
        }
        
        return $result;
    }
    
    /**
     * Trigger welcome message for new signup
     */
    public function sendWelcome(int $playerId, ?int $campaignId = null): array {
        $db = Database::getInstance();
        
        // Get player
        $stmt = $db->prepare("SELECT name, state_id FROM igp_players WHERE id = ?");
        $stmt->execute([$playerId]);
        $player = $stmt->fetch();
        
        if (!$player) {
            return ['success' => false, 'error' => 'Player not found'];
        }
        
        // Send welcome message
        $template = 'welcome';
        $params = [$player['name']];
        
        $result = $this->aisensy->sendTemplate($playerId, $template, $params, $campaignId);
        
        // If player has assigned state with community, queue invite
        if ($player['state_id']) {
            $stmt = $db->prepare("SELECT id FROM igp_communities 
                WHERE state_id = ? AND community_type = 'geo' AND is_active = 1 
                LIMIT 1");
            $stmt->execute([$player['state_id']]);
            $community = $stmt->fetch();
            
            if ($community) {
                // Delay community invite by 5 minutes
                // In production, use a queue (Redis, database queue, etc.)
                // For now, we'll just log it
                $stmt = $db->prepare("INSERT INTO igp_community_invites 
                    (player_id, community_id, campaign_id, status) 
                    VALUES (?, ?, ?, 'pending')");
                $stmt->execute([$playerId, $community['id'], $campaignId]);
            }
        }
        
        return $result;
    }
    
    /**
     * Build template parameters based on template type
     */
    private function buildTemplateParams(string $template, array $player, array $postData): array {
        $params = [$player['name'] ?? 'Player'];
        
        switch ($template) {
            case 'community_invite':
                $params[] = $postData['invite_link'] ?? 'https://chat.whatsapp.com/...';
                break;
                
            case 'tournament_reminder':
                $params[] = $postData['tournament_name'] ?? 'Upcoming Tournament';
                $params[] = $postData['tournament_datetime'] ?? 'Soon';
                break;
                
            case 'event_promo':
                $params[] = $postData['event_details'] ?? 'Exciting event coming up!';
                break;
                
            case 'checkin_thanks':
                $params[] = $postData['venue_name'] ?? 'our venue';
                break;
        }
        
        return $params;
    }
}
