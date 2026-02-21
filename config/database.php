<?php
/**
 * Database configuration
 */

// Note: In production, these should come from environment variables
// For now, using the documented credentials

$host = $_ENV['DB_HOST'] ?? 'localhost';
$dbname = $_ENV['DB_NAME'] ?? 'pokerops';
$username = $_ENV['DB_USER'] ?? 'pokerops_app';
$password = $_ENV['DB_PASS'] ?? 'PkrOps!2026#Admin';

return [
    'dsn' => "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
    'username' => $username,
    'password' => $password,
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
];
