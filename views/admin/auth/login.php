<?php
/**
 * Admin Login View
 * @var string|null $error
 * @var string $phone
 * @var string $step
 */
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PokerOps Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            900: '#14532d',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-brand-600 rounded-xl mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold">PokerOps</h1>
            <p class="text-gray-400">Admin Dashboard</p>
        </div>

        <!-- Login Card -->
        <div class="bg-gray-800 rounded-2xl shadow-xl p-6 sm:p-8">
            <?php if ($error): ?>
                <div class="bg-red-900/50 border border-red-500 text-red-200 px-4 py-3 rounded-lg mb-6">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($step === 'phone'): ?>
                <!-- Phone Input Step -->
                <form action="/admin/login/send-otp" method="POST" class="space-y-6">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">
                            Phone Number
                        </label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            value="<?= htmlspecialchars($phone) ?>"
                            placeholder="Enter your phone number"
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-white placeholder-gray-400"
                            required
                            autocomplete="tel"
                        >
                        <p class="text-xs text-gray-400 mt-2">
                            We'll send you a one-time password via WhatsApp to verify.
                        </p>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-brand-600 hover:bg-brand-700 text-white font-medium py-3 px-4 rounded-lg transition-colors flex items-center justify-center"
                    >
                        <span>Send OTP</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </form>

            <?php else: ?>
                <!-- OTP Verification Step -->
                <form action="/admin/login/verify" method="POST" class="space-y-6">
                    <div>
                        <label for="otp" class="block text-sm font-medium text-gray-300 mb-2">
                            Verification Code
                        </label>
                        <input 
                            type="text" 
                            id="otp" 
                            name="otp" 
                            placeholder="000000"
                            maxlength="6"
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-white placeholder-gray-400 text-center text-2xl tracking-widest"
                            required
                            inputmode="numeric"
                            pattern="[0-9]{6}"
                        >
                        <p class="text-xs text-gray-400 mt-2">
                            Enter the 6-digit code sent via WhatsApp to <?= htmlspecialchars($phone) ?>
                        </p>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-brand-600 hover:bg-brand-700 text-white font-medium py-3 px-4 rounded-lg transition-colors"
                    >
                        Verify & Login
                    </button>

                    <div class="text-center">
                        <a href="/admin/login" class="text-sm text-brand-500 hover:text-brand-400">
                            Use different number
                        </a>
                    </div>
                </form>
            <?php endif; ?>
        </div>

        <!-- Footer -->
        <p class="text-center text-gray-500 text-sm mt-6">
            Secure admin access only
        </p>
    </div>

    <script>
        // Auto-focus OTP input
        document.addEventListener('DOMContentLoaded', function() {
            const otpInput = document.getElementById('otp');
            if (otpInput) {
                otpInput.focus();
            }
        });
    </script>
</body>
</html>
