<?php
/**
 * PokerOps - Main Entry Point
 * 
 * Public landing pages (root domain)
 * Routes based on URL slug to landing pages
 */

require_once __DIR__ . '/../includes/bootstrap.php';

$router = new App\Router();

// Get the requested path
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($uri, '/');

// Route: Landing pages (root slug)
if ($path && !str_starts_with($path, 'api/')) {
    $router->handleLandingPage($path);
    exit;
}

// Default: 404
http_response_code(404);
echo 'Page not found';
