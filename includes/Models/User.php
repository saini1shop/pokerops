<?php
namespace App\Models;

use App\Database;
use PDO;

class User {
    
    public static function findByPhone(string $phone): ?array {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM igp_users WHERE phone = ?");
        $stmt->execute([$phone]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    public static function findById(int $id): ?array {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM igp_users WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    public static function updateLastLogin(int $userId): void {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE igp_users SET last_login_at = NOW() WHERE id = ?");
        $stmt->execute([$userId]);
    }
    
    public static function hasRole(int $userId, string $role): bool {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT role FROM igp_users WHERE id = ?");
        $stmt->execute([$userId]);
        $userRole = $stmt->fetchColumn();
        
        $roleHierarchy = [
            'super_admin' => 4,
            'hq_admin' => 3,
            'branch_admin' => 2,
            'staff' => 1,
        ];
        
        return ($roleHierarchy[$userRole] ?? 0) >= ($roleHierarchy[$role] ?? 0);
    }
}
