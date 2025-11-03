<?php
// Start session
session_start();

// Require PHP 8.0 or higher
if (version_compare(PHP_VERSION, '8.0.0', '<')) {
    die('This application requires PHP 8.0 or higher. Current version: ' . PHP_VERSION);
}

// Check if SQLite is available
if (!extension_loaded('pdo_sqlite')) {
    die('SQLite PDO extension is not available. Please enable it in your PHP configuration.');
}

// Define base path
define('BASE_PATH', __DIR__);

// Autoloader
spl_autoload_register(function ($class) {
    $directories = [
        BASE_PATH . '/core/',
        BASE_PATH . '/controllers/',
        BASE_PATH . '/models/',
    ];
    
    foreach ($directories as $directory) {
        $file = $directory . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Initialize router
$router = new Router();

// Define routes
$router->get('/', 'SetupController@index');
$router->get('/setup', 'SetupController@index');
$router->post('/setup', 'SetupController@setup');

$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@authenticate');
$router->get('/logout', 'AuthController@logout');

$router->get('/home', 'VehicleController@index');
$router->get('/vehicles', 'VehicleController@index');
$router->post('/vehicles/add', 'VehicleController@add');
$router->get('/vehicles/{id}', 'VehicleController@show');
$router->post('/vehicles/{id}/edit', 'VehicleController@edit');
$router->post('/vehicles/{id}/delete', 'VehicleController@delete');
$router->get('/vehicles/{id}/export/{format}', 'VehicleController@export');

$router->post('/maintenance/add', 'MaintenanceController@add');
$router->post('/maintenance/{id}/edit', 'MaintenanceController@edit');
$router->post('/maintenance/{id}/delete', 'MaintenanceController@delete');
$router->get('/maintenance/search', 'MaintenanceController@search');

$router->get('/settings', 'SettingsController@index');
$router->post('/settings/password', 'SettingsController@changePassword');
$router->post('/settings/quick-tasks/add', 'SettingsController@addQuickTask');
$router->post('/settings/quick-tasks/{id}/delete', 'SettingsController@deleteQuickTask');

// Dispatch the request
try {
    $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
} catch (Exception $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}
