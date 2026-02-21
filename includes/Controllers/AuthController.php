<?php
namespace App\Controllers;

use App\Database;
use App\Models\User;
use App\Models\Otp;
use App\Services\AiSensyService;

class AuthController {
    
    public function showLogin(): void {
        // If already logged in, redirect to dashboard
        if (isset($_SESSION['admin_user_id'])) {
            header('Location: /admin/dashboard');
            exit;
        }
        
        $error = $_SESSION['login_error'] ?? null;
        $phone = $_SESSION['login_phone'] ?? '';
        $step = $_SESSION['login_step'] ?? 'phone'; // phone | otp
        
        unset($_SESSION['login_error'], $_SESSION['login_phone'], $_SESSION['login_step']);
        
        include VIEWS_PATH . '/admin/auth/login.php';
    }
    
    public function sendOtp(): void {
        $phone = $this->sanitizePhone($_POST['phone'] ?? '');
        
        if (!$phone || strlen($phone) < 10) {
            $_SESSION['login_error'] = 'Please enter a valid phone number';
            $_SESSION['login_step'] = 'phone';
            header('Location: /admin/login');
            exit;
        }
        
        // Check if user exists
        $user = User::findByPhone($phone);
        
        if (!$user) {
            $_SESSION['login_error'] = 'No admin account found with this phone number';
            $_SESSION['login_step'] = 'phone';
            header('Location: /admin/login');
            exit;
        }
        
        if ($user['status'] !== 'active') {
            $_SESSION['login_error'] = 'Account is suspended. Contact support.';
            $_SESSION['login_step'] = 'phone';
            header('Location: /admin/login');
            exit;
        }
        
        // Generate OTP with WhatsApp channel
        $otp = Otp::generate($user['id'], 'whatsapp');
        
        // Send OTP via WhatsApp (skip in dev mode)
        $aisensyConfig = require __DIR__ . '/../../config/aisensy.php';
        
        if (empty($aisensyConfig['dev_mode'])) {
            $whatsapp = new AiSensyService();
            $result = $whatsapp->sendTemplate(
                $user['id'],
                'admin_login_otp',
                [$user['name'], $otp['code']]
            );
            
            if (!$result['success']) {
                error_log("WhatsApp OTP failed for {$phone}: " . $result['error']);
            }
        } else {
            // Dev mode: skip WhatsApp, just log
            error_log("DEV MODE - OTP for {$phone}: {$otp['code']}");
        }
        
        // Store in session for verification
        $_SESSION['login_phone'] = $phone;
        $_SESSION['login_step'] = 'otp';
        $_SESSION['pending_otp_id'] = $otp['id'];
        
        header('Location: /admin/login');
        exit;
    }
    
    public function verifyOtp(): void {
        $code = $_POST['otp'] ?? '';
        $phone = $_SESSION['login_phone'] ?? '';
        $otpId = $_SESSION['pending_otp_id'] ?? null;
        
        error_log("Session data: phone={$phone}, otpId={$otpId}, code={$code}");
        error_log("Session ID: " . session_id());
        
        if (!$otpId || !$code) {
            $_SESSION['login_error'] = 'Invalid verification';
            $_SESSION['login_step'] = 'otp';
            header('Location: /admin/login');
            exit;
        }
        
        $otp = new Otp();
        $result = $otp->verify($otpId, $code);
        
        if (!$result['valid']) {
            $_SESSION['login_error'] = $result['message'];
            $_SESSION['login_step'] = 'otp';
            header('Location: /admin/login');
            exit;
        }
        
        // Login successful
        $user = User::findByPhone($phone);
        
        if (!$user) {
            error_log("Login failed: User not found for phone: {$phone}");
            $_SESSION['login_error'] = 'User not found';
            $_SESSION['login_step'] = 'phone';
            header('Location: /admin/login');
            exit;
        }
        
        $_SESSION['admin_user_id'] = $user['id'];
        $_SESSION['admin_user_name'] = $user['name'];
        $_SESSION['admin_user_role'] = $user['role'];
        $_SESSION['admin_location_id'] = $user['location_id'];
        $_SESSION['login_time'] = time();
        
        // Update last login
        User::updateLastLogin($user['id']);
        
        // Clear temporary session data
        unset($_SESSION['login_phone'], $_SESSION['login_step'], $_SESSION['pending_otp_id']);
        
        header('Location: /admin/dashboard');
        exit;
    }
    
    public function logout(): void {
        session_destroy();
        header('Location: /admin/login');
        exit;
    }
    
    private function sanitizePhone(string $phone): string {
        // Remove all non-digits
        $phone = preg_replace('/\D/', '', $phone);
        
        // Remove country code if present (assume India +91)
        if (strlen($phone) === 12 && str_starts_with($phone, '91')) {
            $phone = substr($phone, 2);
        }
        
        return $phone;
    }
}
