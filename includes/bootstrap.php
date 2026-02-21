<?php
/**
 * Bootstrap file - Loaded by all entry points
 */

// Error reporting (dev mode - disable in production)
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Base path
define('BASE_PATH', dirname(__DIR__));
define('INCLUDES_PATH', BASE_PATH . '/includes');
define('VIEWS_PATH', BASE_PATH . '/views');
define('CONFIG_PATH', BASE_PATH . '/config');

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = INCLUDES_PATH . '/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Load config
require_once CONFIG_PATH . '/database.php';
require_once CONFIG_PATH . '/app.php';

// Timezone
date_default_timezone_set('Asia/Kolkata');
