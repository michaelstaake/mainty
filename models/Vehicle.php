<?php

class Vehicle {
    private PDO $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAll(): array {
        $stmt = $this->db->query("
            SELECT v.*, 
                   COUNT(m.id) as maintenance_count,
                   MAX(m.date) as last_maintenance_date
            FROM vehicles v
            LEFT JOIN maintenance_items m ON v.id = m.vehicle_id
            GROUP BY v.id
            ORDER BY v.name ASC
        ");
        return $stmt->fetchAll();
    }
    
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM vehicles WHERE id = ?");
        $stmt->execute([$id]);
        $vehicle = $stmt->fetch();
        return $vehicle ?: null;
    }
    
    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO vehicles (name, year, make, model, color, license_plate)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $data['name'],
            $data['year'] ?? null,
            $data['make'] ?? null,
            $data['model'] ?? null,
            $data['color'] ?? null,
            $data['license_plate'] ?? null
        ]);
        
        return (int)$this->db->lastInsertId();
    }
    
    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE vehicles 
            SET name = ?, year = ?, make = ?, model = ?, color = ?, license_plate = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        
        return $stmt->execute([
            $data['name'],
            $data['year'] ?? null,
            $data['make'] ?? null,
            $data['model'] ?? null,
            $data['color'] ?? null,
            $data['license_plate'] ?? null,
            $id
        ]);
    }
    
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM vehicles WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
