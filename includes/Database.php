<?php
namespace App;

use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;
    
    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $config = require CONFIG_PATH . '/database.php';
            
            try {
                self::$instance = new PDO(
                    $config['dsn'],
                    $config['username'],
                    $config['password'],
                    $config['options']
                );
            } catch (PDOException $e) {
                error_log('Database connection failed: ' . $e->getMessage());
                error_log('DSN: ' . $config['dsn'] . ' | User: ' . $config['username']);
                throw new \Exception('Database connection failed: ' . $e->getMessage());
            }
        }
        
        return self::$instance;
    }
    
    // Prevent cloning and unserialization
    private function __clone() {}
    public function __wakeup() {
        throw new \Exception("Cannot unserialize singleton");
    }
}
