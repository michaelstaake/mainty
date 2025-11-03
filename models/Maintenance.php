<?php

class Maintenance {
    private PDO $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getByVehicleId(int $vehicleId): array {
        $stmt = $this->db->prepare("
            SELECT * FROM maintenance_items 
            WHERE vehicle_id = ? 
            ORDER BY date DESC, id DESC
        ");
        $stmt->execute([$vehicleId]);
        return $stmt->fetchAll();
    }
    
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM maintenance_items WHERE id = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch();
        return $item ?: null;
    }
    
    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO maintenance_items 
            (vehicle_id, name, date, mileage, description, cost, parts_list, performed_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $data['vehicle_id'],
            $data['name'],
            $data['date'],
            $data['mileage'],
            $data['description'] ?? null,
            $data['cost'] ?? null,
            $data['parts_list'] ?? null,
            $data['performed_by'] ?? null
        ]);
        
        return (int)$this->db->lastInsertId();
    }
    
    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE maintenance_items 
            SET name = ?, date = ?, mileage = ?, description = ?, cost = ?, parts_list = ?, performed_by = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        
        return $stmt->execute([
            $data['name'],
            $data['date'],
            $data['mileage'],
            $data['description'] ?? null,
            $data['cost'] ?? null,
            $data['parts_list'] ?? null,
            $data['performed_by'] ?? null,
            $id
        ]);
    }
    
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM maintenance_items WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function searchByName(string $query): array {
        // Search in both quick_tasks and existing maintenance items
        $stmt = $this->db->prepare("
            SELECT DISTINCT name FROM (
                SELECT name FROM quick_tasks WHERE name LIKE ?
                UNION
                SELECT DISTINCT name FROM maintenance_items WHERE name LIKE ?
            )
            ORDER BY name ASC
            LIMIT 10
        ");
        
        $searchTerm = '%' . $query . '%';
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
