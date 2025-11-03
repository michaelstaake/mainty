<?php

class Router {
    private array $routes = [];
    
    public function get(string $path, string $handler): void {
        $this->addRoute('GET', $path, $handler);
    }
    
    public function post(string $path, string $handler): void {
        $this->addRoute('POST', $path, $handler);
    }
    
    private function addRoute(string $method, string $path, string $handler): void {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }
    
    public function dispatch(string $uri, string $method): void {
        // Remove query string
        $uri = parse_url($uri, PHP_URL_PATH);
        
        // Remove base directory from URI if running in subdirectory
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptName !== '/' && strpos($uri, $scriptName) === 0) {
            $uri = substr($uri, strlen($scriptName));
        }
        
        // Ensure URI starts with /
        if (empty($uri) || $uri[0] !== '/') {
            $uri = '/' . $uri;
        }
        
        // Remove trailing slash except for root
        if ($uri !== '/' && str_ends_with($uri, '/')) {
            $uri = rtrim($uri, '/');
        }
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            
            $pattern = $this->convertToRegex($route['path']);
            
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove full match
                $this->callHandler($route['handler'], $matches);
                return;
            }
        }
        
        // 404 Not Found
        http_response_code(404);
        echo '404 - Page Not Found<br>';
        echo 'Requested URI: ' . htmlspecialchars($uri) . '<br>';
        echo 'Request Method: ' . htmlspecialchars($method);
    }
    
    private function convertToRegex(string $path): string {
        $path = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $path);
        return '#^' . $path . '$#';
    }
    
    private function callHandler(string $handler, array $params): void {
        [$controller, $method] = explode('@', $handler);
        
        $controllerInstance = new $controller();
        call_user_func_array([$controllerInstance, $method], $params);
    }
}
