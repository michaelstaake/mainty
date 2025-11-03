<?php

// Debug configuration
define('DEBUG', false);

if (DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// Database configuration
define('DB_PATH', BASE_PATH . '/data/mainty.db');
define('DB_DIR', BASE_PATH . '/data');

// Application configuration
define('APP_NAME', 'Mainty');
define('ITEMS_PER_PAGE', 20);

// Base URL configuration - auto-detect the base path
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
define('BASE_URL', $scriptName === '/' ? '' : $scriptName);

// Helper function for URLs
function url($path) {
    return BASE_URL . $path;
}

// Ensure data directory exists
if (!file_exists(DB_DIR)) {
    mkdir(DB_DIR, 0755, true);
}
