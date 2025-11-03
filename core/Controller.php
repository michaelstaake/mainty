<?php

class Controller {
    protected function view(string $viewName, array $data = []): void {
        extract($data);
        
        $viewPath = BASE_PATH . '/views/' . $viewName . '.php';
        
        if (!file_exists($viewPath)) {
            die("View not found: $viewName");
        }
        
        require_once $viewPath;
    }
    
    protected function redirect(string $path): void {
        // Add base URL if path doesn't already include it
        $url = BASE_URL . $path;
        header('Location: ' . $url);
        exit;
    }
    
    protected function json(mixed $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function requireAuth(): void {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }
    
    protected function requireSetup(): void {
        if (!Database::isInitialized()) {
            $this->redirect('/setup');
        }
    }
}
