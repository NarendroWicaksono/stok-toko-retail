<?php
// Dynamic BASEURL detection for flexibility with XAMPP Apache or PHP CLI dev server
if (isset($_SERVER['HTTP_HOST'])) {
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
    $scriptDir = ($scriptDir === '/' || $scriptDir === '\\') ? '' : $scriptDir;
    define('BASEURL', $protocol . "://" . $_SERVER['HTTP_HOST'] . $scriptDir);
} else {
    define('BASEURL', 'http://localhost/permweb/public');
}

// DB Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'stok_toko');