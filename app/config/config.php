<?php
// Dynamic BASEURL detection for flexibility with XAMPP Apache, PHP CLI dev server, or Vercel Cloud
if (isset($_SERVER['HTTP_HOST'])) {
    $isHttps = (
        (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === '1')) ||
        (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
        (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ||
        (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'vercel.app') !== false)
    );
    $protocol = $isHttps ? "https" : "http";
    $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
    $scriptDir = ($scriptDir === '/' || $scriptDir === '\\' || $scriptDir === '/api' || $scriptDir === '\\api') ? '' : $scriptDir;
    define('BASEURL', $protocol . "://" . $_SERVER['HTTP_HOST'] . $scriptDir);
} else {
    define('BASEURL', 'http://localhost/permweb/public');
}

// DB Configuration - Supports Environment Variables & Connection URL (Vercel Cloud MySQL / Railway / Aiven / TiDB)
$dbUrl = getenv('DATABASE_URL') ?: getenv('MYSQL_URL');
$dbUrlParsed = $dbUrl ? parse_url($dbUrl) : null;

define('DB_HOST', getenv('DB_HOST') ?: (getenv('MYSQLHOST') ?: ($dbUrlParsed['host'] ?? '127.0.0.1')));
define('DB_USER', getenv('DB_USER') ?: (getenv('MYSQLUSER') ?: ($dbUrlParsed['user'] ?? 'root')));
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : (getenv('MYSQLPASSWORD') !== false ? getenv('MYSQLPASSWORD') : ($dbUrlParsed['pass'] ?? '')));
define('DB_NAME', getenv('DB_NAME') ?: (getenv('MYSQLDATABASE') ?: (isset($dbUrlParsed['path']) ? ltrim($dbUrlParsed['path'], '/') : 'stok_toko')));
define('DB_PORT', getenv('DB_PORT') ?: (getenv('MYSQLPORT') ?: ($dbUrlParsed['port'] ?? '3306')));