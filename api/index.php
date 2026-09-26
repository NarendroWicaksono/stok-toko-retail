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

// Entry point for Vercel Serverless PHP Function
require_once __DIR__ . '/../public/index.php';
