<?php 

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $db_name = DB_NAME;
    private $port = DB_PORT;

    private $dbh;
    private $stmt;

    public function __construct() {
        // Data source name using TCP connection to prevent socket errors
        $dsn = 'mysql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->db_name . ';charset=utf8mb4';

        $options = [
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        // SSL option for Cloud MySQL providers like Aiven
        if (defined('PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT')) {
            $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
        }

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            // Render user-friendly error page if Cloud DB is not configured yet
            http_response_code(500);
            echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>Koneksi Database | Stok Toko Retail</title>';
            echo '<style>body{font-family:sans-serif;background:#fff;color:#111;padding:40px;line-height:1.6;max-width:800px;margin:0 auto;}';
            echo '.box{border:2px solid #d32f2f;background:#ffe6e6;padding:24px;border-radius:8px;}h1{color:#d32f2f;margin-top:0;}code{background:#fff;padding:2px 6px;border:1px solid #ccc;border-radius:4px;}</style></head><body>';
            echo '<div class="box"><h1>Perhatian: Koneksi Database MySQL Cloud Belum Dikonfigurasi</h1>';
            echo '<p>Aplikasi web berhasil di-deploy ke Vercel, tetapi membutuhkan koneksi ke database MySQL online agar data dapat ditampilkan di cloud.</p>';
            echo '<h3>Langkah Penyelesaian:</h3>';
            echo '<ol>';
            echo '<li>Buka dashboard Vercel -> Project Settings -> <strong>Environment Variables</strong>, lalu tambahkan variabel Aiven Anda.</li>';
            echo '</ol>';
            echo '<p><small>Detail Error MySQL: ' . htmlspecialchars($e->getMessage()) . '</small></p></div></body></html>';
            exit;
        }
    }

    public function query($query) {
        $this->stmt = $this->dbh->prepare($query);
    }

    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute() {
        $this->stmt->execute();
    }

    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function rowCount() {
        return $this->stmt->rowCount();
    }
}