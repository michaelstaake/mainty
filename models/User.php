<?php

class User {
    private PDO $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function verifyPassword(string $password): bool {
        $stmt = $this->db->query("SELECT password_hash FROM users LIMIT 1");
        $user = $stmt->fetch();
        
        if (!$user) {
            return false;
        }
        
        return password_verify($password, $user['password_hash']);
    }
    
    public function updatePassword(string $newPassword): bool {
        try {
            $stmt = $this->db->prepare("UPDATE users SET password_hash = ?, updated_at = CURRENT_TIMESTAMP");
            return $stmt->execute([password_hash($newPassword, PASSWORD_DEFAULT)]);
        } catch (PDOException $e) {
            error_log('Password update failed: ' . $e->getMessage());
            return false;
        }
    }
    
    public function getId(): ?int {
        $stmt = $this->db->query("SELECT id FROM users LIMIT 1");
        $user = $stmt->fetch();
        return $user ? (int)$user['id'] : null;
    }
}
