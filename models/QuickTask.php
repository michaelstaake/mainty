<?php

class QuickTask {
    private PDO $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAll(): array {
        $stmt = $this->db->query("SELECT * FROM quick_tasks ORDER BY name ASC");
        return $stmt->fetchAll();
    }
    
    public function create(string $name): int {
        try {
            $stmt = $this->db->prepare("INSERT INTO quick_tasks (name) VALUES (?)");
            $stmt->execute([$name]);
            return (int)$this->db->lastInsertId();
        } catch (PDOException $e) {
            // Handle duplicate entry
            if ($e->getCode() == 23000) {
                return 0;
            }
            throw $e;
        }
    }
    
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM quick_tasks WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
