<?php
/**
 * AiSensy Webhook Endpoint
 * POST /webhook/aisensy
 * 
 * Receives delivery status updates from AiSensy
 */

require_once __DIR__ . '/../includes/bootstrap.php';

use App\Services\AiSensyService;

header('Content-Type: application/json');

// Get webhook payload
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
    exit;
}

// Log webhook for debugging
error_log('AiSensy webhook received: ' . $input);

try {
    $whatsapp = new AiSensyService();
    $success = $whatsapp->processWebhook($data);
    
    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Webhook processed']);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Failed to process webhook']);
    }
    
} catch (Exception $e) {
    error_log('Webhook processing error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Internal error']);
}
