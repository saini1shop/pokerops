<?php
/**
 * Admin Entry Point
 * 
 * All admin routes under /admin
 */

require_once __DIR__ . '/../includes/bootstrap.php';

// Start session for admin auth
session_start();

$router = new App\AdminRouter();

// Get the requested admin path
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = preg_replace('#^/admin/?#', '', $uri);
$path = trim($path, '/');

// Route based on path
$router->route($path);
