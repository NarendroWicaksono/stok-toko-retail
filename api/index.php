<?php
// Handle static CSS files directly for Vercel Serverless environment
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);

if (preg_match('/\.css$/i', $uri)) {
    $filename = basename($uri);
    $cssPath = __DIR__ . '/../public/css/' . $filename;
    if (file_exists($cssPath)) {
        header('Content-Type: text/css; charset=utf-8');
        header('Cache-Control: public, max-age=86400');
        readfile($cssPath);
        exit;
    }
}

// Ensure $_GET['url'] is populated on Vercel
if (empty($_GET['url'])) {
    $rawUri = $_SERVER['HTTP_X_FORWARDED_URI'] ?? ($_SERVER['REQUEST_URI'] ?? '');
    $path = parse_url($rawUri, PHP_URL_PATH);
    if ($path && $path !== '/' && $path !== '/api/index.php' && $path !== '/api') {
        $_GET['url'] = ltrim($path, '/');
    }
}

// Entry point for Vercel Serverless PHP Function
require_once __DIR__ . '/../public/index.php';
