<?php

class App {
    protected $controller = 'Home';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parrseURL();

        // controller check using __DIR__ for robust path resolution
        if (!empty($url) && file_exists(__DIR__ . '/../controllers/' . ucfirst($url[0]) . '.php')) {
            $this->controller = ucfirst($url[0]);
            unset($url[0]);
        }

        require_once __DIR__ . '/../controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // param
        if (!empty($url)) {
            $this->params = array_values($url);
        }

        // jalankan controller & method, serta kirimkan param jika ada
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parrseURL() {
        if (isset($_GET['url'])) {
            $raw = $_GET['url'];
        } else {
            // Fallback for environment without mod_rewrite (e.g. php -S)
            $uri = $_SERVER['REQUEST_URI'] ?? '';
            $path = parse_url($uri, PHP_URL_PATH);
            
            // Trim script directory path if hosted in subfolder
            $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
            if ($scriptDir !== '/' && $scriptDir !== '\\' && strpos($path, $scriptDir) === 0) {
                $path = substr($path, strlen($scriptDir));
            }
            $raw = ltrim($path, '/');
        }

        if (!empty($raw)) {
            $url = rtrim($raw, '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }

        return [];
    }
}