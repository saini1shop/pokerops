<?php
/**
 * AiSensy API Configuration
 * 
 * Get your API key from: https://app.aisensy.com/campaigns/api-management
 */

return [
    'enabled' => true,
    'dev_mode' => $_ENV['APP_ENV'] === 'development' || $_ENV['DEV_MODE'] === 'true',
    'dev_otp' => $_ENV['DEV_OTP'] ?? '123456',
    'api_key' => $_ENV['AISENSY_API_KEY'] ?? '',
    'base_url' => 'https://backend.aisensy.com/campaign/t1/api',
    'webhook_secret' => $_ENV['AISENSY_WEBHOOK_SECRET'] ?? '',
    
    // Default template names (configure in AiSensy dashboard)
    'templates' => [
        'welcome' => 'welcome_message',           // {{1}} = player name
        'admin_login_otp' => 'admin_login_otp', // {{1}} = admin name, {{2}} = OTP code
        'community_invite' => 'community_invite', // {{1}} = player name, {{2}} = invite link
        'tournament_reminder' => 'tournament_reminder', // {{1}} = player name, {{2}} = tournament name, {{3}} = date/time
        'event_promo' => 'event_promotion',       // {{1}} = player name, {{2}} = event details
        'checkin_thanks' => 'checkin_thanks',     // {{1}} = player name, {{2}} = venue name
    ],
    
    // Rate limiting
    'rate_limit' => [
        'messages_per_minute' => 60,
        'burst_limit' => 10,
    ],
];
