<?php
namespace App\Models;

use App\Database;
use PDO;

class Otp {
    private int $expiryMinutes = 10;
    private int $maxAttempts = 3;
    
    public static function generate(int $userId, string $channel): array {
        $db = Database::getInstance();
        $config = require __DIR__ . '/../../config/aisensy.php';
        
        // Check for dev mode - use default OTP
        if (!empty($config['dev_mode'])) {
            $code = $config['dev_otp'] ?? '123456';
        } else {
            // Generate 6-digit code
            $code = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        }
        
        // Hash the code for storage
        $hash = hash('sha256', $code);
        
        // Set expiry
        $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));
        
        // Insert into database
        $stmt = $db->prepare("INSERT INTO igp_user_otps 
            (user_id, channel, otp_hash, expires_at, attempt_count) 
            VALUES (?, ?, ?, ?, 0)");
        $stmt->execute([$userId, $channel, $hash, $expiresAt]);
        
        $otpId = (int) $db->lastInsertId();
        
        return [
            'id' => $otpId,
            'code' => $code, // Only returned here - never stored in plain text
            'expires_at' => $expiresAt,
        ];
    }
    
    public function verify(int $otpId, string $code): array {
        $db = Database::getInstance();
        
        // Fetch OTP record
        $stmt = $db->prepare("SELECT * FROM igp_user_otps WHERE id = ?");
        $stmt->execute([$otpId]);
        $otp = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$otp) {
            return ['valid' => false, 'message' => 'Invalid verification code'];
        }
        
        // Check if already consumed
        if ($otp['consumed_at']) {
            return ['valid' => false, 'message' => 'Code already used'];
        }
        
        // Check expiry
        if (strtotime($otp['expires_at']) < time()) {
            return ['valid' => false, 'message' => 'Code expired. Please request a new one.'];
        }
        
        // Check max attempts
        if ($otp['attempt_count'] >= $this->maxAttempts) {
            return ['valid' => false, 'message' => 'Too many attempts. Please request a new code.'];
        }
        
        // Increment attempt count
        $stmt = $db->prepare("UPDATE igp_user_otps SET attempt_count = attempt_count + 1 WHERE id = ?");
        $stmt->execute([$otpId]);
        
        // Verify code
        $hash = hash('sha256', $code);
        if (!hash_equals($otp['otp_hash'], $hash)) {
            $remaining = $this->maxAttempts - ($otp['attempt_count'] + 1);
            return [
                'valid' => false, 
                'message' => "Invalid code. {$remaining} attempts remaining."
            ];
        }
        
        // Mark as consumed
        $stmt = $db->prepare("UPDATE igp_user_otps SET consumed_at = NOW() WHERE id = ?");
        $stmt->execute([$otpId]);
        
        return ['valid' => true, 'message' => 'Success'];
    }
    
    public static function cleanupExpired(): void {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM igp_user_otps 
            WHERE expires_at < DATE_SUB(NOW(), INTERVAL 24 HOUR) 
            OR consumed_at IS NOT NULL");
        $stmt->execute();
    }
}
