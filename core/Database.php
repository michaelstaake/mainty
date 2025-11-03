<?php

require_once BASE_PATH . '/config.php';

class Database {
    private static ?PDO $instance = null;
    
    public static function getInstance(): PDO {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO('sqlite:' . DB_PATH);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die('Database connection failed: ' . $e->getMessage());
            }
        }
        
        return self::$instance;
    }
    
    public static function isInitialized(): bool {
        if (!file_exists(DB_PATH)) {
            return false;
        }
        
        try {
            $db = self::getInstance();
            $result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
            return $result->fetch() !== false;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static function initialize(string $password): bool {
        try {
            $db = self::getInstance();
            
            // Create users table
            $db->exec("
                CREATE TABLE IF NOT EXISTS users (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    password_hash TEXT NOT NULL,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
                )
            ");
            
            // Create vehicles table
            $db->exec("
                CREATE TABLE IF NOT EXISTS vehicles (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name TEXT NOT NULL,
                    year TEXT,
                    make TEXT,
                    model TEXT,
                    color TEXT,
                    license_plate TEXT,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
                )
            ");
            
            // Create maintenance_items table
            $db->exec("
                CREATE TABLE IF NOT EXISTS maintenance_items (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    vehicle_id INTEGER NOT NULL,
                    name TEXT NOT NULL,
                    date DATE NOT NULL,
                    mileage INTEGER NOT NULL,
                    description TEXT,
                    cost REAL,
                    parts_list TEXT,
                    performed_by TEXT,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE
                )
            ");
            
            // Create quick_tasks table (predefined maintenance items)
            $db->exec("
                CREATE TABLE IF NOT EXISTS quick_tasks (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name TEXT NOT NULL UNIQUE,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
                )
            ");
            
            // Insert default password
            $stmt = $db->prepare("INSERT INTO users (password_hash) VALUES (?)");
            $stmt->execute([password_hash($password, PASSWORD_DEFAULT)]);
            
            // Insert default quick tasks
            $defaultTasks = [
                'Oil Change - Oil and Filter',
                'Oil Change - Oil Only',
                'Tire Rotation',
                'Air Filter Replacement',
                'Battery Replacement',
                'Spark Plug Replacement',
                'Wiper Blade Replacement - Front',
                'Wiper Blade Replacement - Rear',
                'Wiper Blade Replacement - Front and Rear',
                'Cabin Air Filter Replacement',
                'Wheel Alignment',
                'Tire Replacement',
                'Brake Fluid Change',
                'Power Steering Fluid Change',
                'Fuel Filter Replacement',
                'Serpentine Belt Replacement',
                'Timing Belt Replacement',
                'Inspection/Emissions Test',
                'Registration Renewed',
            ];
            
            $stmt = $db->prepare("INSERT INTO quick_tasks (name) VALUES (?)");
            foreach ($defaultTasks as $task) {
                $stmt->execute([$task]);
            }
            
            return true;
        } catch (PDOException $e) {
            error_log('Database initialization failed: ' . $e->getMessage());
            return false;
        }
    }
}
