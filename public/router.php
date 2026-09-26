<?php
// Router script for PHP built-in server (php -S)
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$filePath = __DIR__ . $path;

if ($path !== '/' && file_exists($filePath) && !is_dir($filePath)) {
    return false; // Serve static file directly
}

$_GET['url'] = ltrim($path, '/');
require_once __DIR__ . '/index.php';
