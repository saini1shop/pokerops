<?php
/**
 * Application configuration
 */

return [
    'name' => 'PokerOps',
    'version' => '0.1.0',
    'url' => $_ENV['APP_URL'] ?? 'https://pokerops.in',
    'env' => $_ENV['APP_ENV'] ?? 'development',
    
    'otp' => [
        'expiry_minutes' => 10,
        'max_attempts' => 3,
        'length' => 6,
    ],
    
    'session' => [
        'lifetime' => 8 * 60 * 60, // 8 hours
        'regenerate_interval' => 30 * 60, // 30 minutes
    ],
    
    'landing_pages' => [
        'cache_seconds' => 60,
    ],
    
    'uploads' => [
        'path' => BASE_PATH . '/public/uploads',
        'url_path' => '/uploads',
        'max_size' => 5 * 1024 * 1024, // 5MB
    ],
];
