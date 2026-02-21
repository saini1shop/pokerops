<?php
namespace App\Services;

use App\Database;
use PDO;
use Exception;

/**
 * AiSensy WhatsApp API Service
 */
class AiSensyService {
    private array $config;
    private string $apiKey;
    private string $baseUrl;
    
    public function __construct() {
        $this->config = require CONFIG_PATH . '/aisensy.php';
        $this->apiKey = $this->config['api_key'];
        $this->baseUrl = rtrim($this->config['base_url'], '/');
    }
    
    /**
     * Send WhatsApp template message
     * 
     * @param int $playerId Player ID from igp_players
     * @param string $templateName Template name configured in AiSensy
     * @param array $parameters Template variables [{{1}}, {{2}}, etc.]
     * @param int|null $campaignId Optional campaign ID for attribution
     * @return array ['success' => bool, 'message_id' => string|null, 'error' => string|null]
     */
    public function sendTemplate(int $playerId, string $templateName, array $parameters = [], ?int $campaignId = null): array {
        // Get player details
        $player = $this->getPlayer($playerId);
        if (!$player) {
            return ['success' => false, 'message_id' => null, 'error' => 'Player not found'];
        }
        
        // Format phone number (add +91 for India)
        $phone = $this->formatPhone($player['phone']);
        
        // Prepare API payload
        $payload = [
            'apiKey' => $this->apiKey,
            'campaignName' => $templateName,
            'destination' => $phone,
            'userName' => $player['name'],
            'templateParams' => $parameters,
            'source' => 'pokerops_crm',
            'media' => new \stdClass(), // Empty object for AiSensy
            'attributes' => [
                'player_id' => $playerId,
                'campaign_id' => $campaignId,
            ],
        ];
        
        // Log attempt
        $logId = $this->logMessage($playerId, $campaignId, $templateName, 'queued', $payload);
        
        try {
            $response = $this->makeApiCall('/sendTemplateMessage', $payload);
            
            if (isset($response['messageId'])) {
                // Update log with success
                $this->updateLogStatus($logId, 'sent', $response['messageId'], null, null);
                
                return [
                    'success' => true,
                    'message_id' => $response['messageId'],
                    'error' => null,
                ];
            } else {
                throw new Exception($response['error'] ?? 'Unknown error');
            }
            
        } catch (Exception $e) {
            // Update log with failure
            $this->updateLogStatus($logId, 'failed', null, 'api_error', $e->getMessage());
            
            return [
                'success' => false,
                'message_id' => null,
                'error' => $e->getMessage(),
            ];
        }
    }
    
    /**
     * Send bulk template messages
     * 
     * @param array $playerIds Array of player IDs
     * @param string $templateName Template name
     * @param array $parameters Template variables (same for all)
     * @param int|null $campaignId Optional campaign ID
     * @return array Summary of results
     */
    public function sendBulk(array $playerIds, string $templateName, array $parameters = [], ?int $campaignId = null): array {
        $results = [
            'total' => count($playerIds),
            'sent' => 0,
            'failed' => 0,
            'errors' => [],
        ];
        
        foreach ($playerIds as $playerId) {
            $result = $this->sendTemplate($playerId, $templateName, $parameters, $campaignId);
            
            if ($result['success']) {
                $results['sent']++;
            } else {
                $results['failed']++;
                $results['errors'][$playerId] = $result['error'];
            }
            
            // Rate limiting - small delay
            usleep(100000); // 100ms between messages
        }
        
        return $results;
    }
    
    /**
     * Process webhook from AiSensy for delivery status updates
     * 
     * @param array $webhookData JSON payload from AiSensy
     * @return bool Success
     */
    public function processWebhook(array $webhookData): bool {
        // Verify webhook signature if configured
        if (!$this->verifyWebhook($webhookData)) {
            error_log('AiSensy webhook verification failed');
            return false;
        }
        
        $messageId = $webhookData['messageId'] ?? null;
        $status = $webhookData['status'] ?? null; // sent, delivered, read, failed
        
        if (!$messageId || !$status) {
            return false;
        }
        
        // Map AiSensy status to our status
        $statusMap = [
            'sent' => 'sent',
            'delivered' => 'delivered',
            'read' => 'read',
            'failed' => 'failed',
        ];
        
        $mappedStatus = $statusMap[$status] ?? 'unknown';
        
        // Update log
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE igp_whatsapp_logs 
            SET status = ?, 
                sent_at = CASE WHEN ? = 'sent' AND sent_at IS NULL THEN NOW() ELSE sent_at END,
                updated_at = NOW()
            WHERE provider_message_id = ? AND provider = 'aisensy'");
        $stmt->execute([$mappedStatus, $mappedStatus, $messageId]);
        
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Get message delivery status
     * 
     * @param string $messageId AiSensy message ID
     * @return array|null Status info
     */
    public function getMessageStatus(string $messageId): ?array {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM igp_whatsapp_logs 
            WHERE provider_message_id = ? AND provider = 'aisensy'
            ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$messageId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    
    /**
     * Get available templates (cached)
     * 
     * @return array Template list
     */
    public function getTemplates(): array {
        return $this->config['templates'] ?? [];
    }
    
    /**
     * Check API health
     * 
     * @return bool
     */
    public function healthCheck(): bool {
        try {
            // Try to fetch templates or make a simple API call
            $response = $this->makeApiCall('/getTemplates', ['apiKey' => $this->apiKey]);
            return isset($response['status']) && $response['status'] === 'success';
        } catch (Exception $e) {
            error_log('AiSensy health check failed: ' . $e->getMessage());
            return false;
        }
    }
    
    private function getPlayer(int $playerId): ?array {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT id, name, phone, whatsapp_consent FROM igp_players WHERE id = ?");
        $stmt->execute([$playerId]);
        $player = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$player) {
            return null;
        }
        
        // Check if player has WhatsApp consent
        if (!$player['whatsapp_consent']) {
            return null;
        }
        
        // Check if player has opted out
        $stmt = $db->prepare("SELECT id FROM igp_opt_outs WHERE player_id = ? AND channel = 'whatsapp' AND cleared_at IS NULL");
        $stmt->execute([$playerId]);
        if ($stmt->fetch()) {
            return null;
        }
        
        return $player;
    }
    
    private function formatPhone(string $phone): string {
        // Remove all non-digits
        $phone = preg_replace('/\D/', '', $phone);
        
        // Add country code if not present
        if (strlen($phone) === 10) {
            $phone = '91' . $phone;
        }
        
        return '+' . $phone;
    }
    
    private function makeApiCall(string $endpoint, array $payload): array {
        if (empty($this->apiKey)) {
            throw new Exception('AiSensy API key not configured');
        }
        
        $url = $this->baseUrl . $endpoint;
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            throw new Exception('Curl error: ' . curl_error($ch));
        }
        
        curl_close($ch);
        
        if ($httpCode !== 200) {
            throw new Exception("HTTP error: {$httpCode}");
        }
        
        $data = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON response');
        }
        
        return $data;
    }
    
    private function logMessage(int $playerId, ?int $campaignId, string $templateName, string $status, array $payload): int {
        $db = Database::getInstance();
        
        $stmt = $db->prepare("INSERT INTO igp_whatsapp_logs 
            (player_id, campaign_id, template_name, provider, status, payload, created_at) 
            VALUES (?, ?, ?, 'aisensy', ?, ?, NOW())");
        
        $stmt->execute([
            $playerId,
            $campaignId,
            $templateName,
            $status,
            json_encode($payload),
        ]);
        
        return (int) $db->lastInsertId();
    }
    
    private function updateLogStatus(int $logId, string $status, ?string $messageId, ?string $errorCode, ?string $errorMessage): void {
        $db = Database::getInstance();
        
        $stmt = $db->prepare("UPDATE igp_whatsapp_logs 
            SET status = ?, 
                provider_message_id = COALESCE(?, provider_message_id),
                error_code = ?,
                error_message = ?,
                sent_at = CASE WHEN ? = 'sent' THEN NOW() ELSE sent_at END,
                updated_at = NOW()
            WHERE id = ?");
        
        $stmt->execute([$status, $messageId, $errorCode, $errorMessage, $status, $logId]);
    }
    
    private function verifyWebhook(array $data): bool {
        // If no webhook secret configured, skip verification
        if (empty($this->config['webhook_secret'])) {
            return true;
        }
        
        // Get signature from headers
        $headers = getallheaders();
        $signature = $headers['X-Aisensy-Signature'] ?? '';
        
        // Verify HMAC
        $computed = hash_hmac('sha256', json_encode($data), $this->config['webhook_secret']);
        
        return hash_equals($computed, $signature);
    }
}
