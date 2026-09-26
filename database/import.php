<?php
/**
 * Script untuk import data dari train.csv ke database MySQL
 * Mengonversi harga ke Rupiah (kurs 1 USD = Rp 15.000)
 * Jalankan: C:\xampp\php\php.exe database/import.php
 */

require_once __DIR__ . '/../app/config/config.php';

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "Koneksi database berhasil.\n";
} catch (PDOException $e) {
    die("Gagal koneksi: " . $e->getMessage() . "\n");
}

$csvFile = __DIR__ . '/../train.csv';
if (!file_exists($csvFile)) {
    die("File train.csv tidak ditemukan.\n");
}

$handle = fopen($csvFile, 'r');
if (!$handle) {
    die("Gagal membuka file CSV.\n");
}

// Skip header
$header = fgetcsv($handle);

$products = [];
$kursRupiah = 15000; // Konversi USD ke IDR

while (($row = fgetcsv($handle)) !== false) {
    if (count($row) < 18) continue;

    $productId   = trim($row[13]);
    $category    = trim($row[14]);
    $subCategory = trim($row[15]);
    $productName = trim($row[16]);
    $salesUSD    = floatval($row[17]);
    $salesIDR    = $salesUSD * $kursRupiah;

    if (!isset($products[$productId])) {
        $products[$productId] = [
            'product_id'   => $productId,
            'product_name' => $productName,
            'category'     => $category,
            'sub_category' => $subCategory,
            'harga'        => $salesIDR,
            'total_orders' => 1,
        ];
    } else {
        $products[$productId]['total_orders']++;
        // Rata-rata harga dalam Rupiah
        $products[$productId]['harga'] = 
            (($products[$productId]['harga'] * ($products[$productId]['total_orders'] - 1)) + $salesIDR) 
            / $products[$productId]['total_orders'];
    }
}
fclose($handle);

echo "Ditemukan " . count($products) . " produk unik.\n";

// Insert ke database
$stmt = $pdo->prepare("
    INSERT INTO produk (product_id, product_name, category, sub_category, harga, stok, stok_minimum)
    VALUES (:product_id, :product_name, :category, :sub_category, :harga, :stok, :stok_minimum)
    ON DUPLICATE KEY UPDATE
        product_name = VALUES(product_name),
        category = VALUES(category),
        sub_category = VALUES(sub_category),
        harga = VALUES(harga),
        stok = VALUES(stok)
");

$count = 0;
foreach ($products as $p) {
    // Generate stok acak realistis (5 - 200)
    $stok = rand(5, 200);
    // Minimum stok berdasarkan frekuensi order
    $stokMin = max(5, min(50, intval($p['total_orders'] * 2)));

    $stmt->execute([
        ':product_id'   => $p['product_id'],
        ':product_name' => mb_substr($p['product_name'], 0, 255),
        ':category'     => $p['category'],
        ':sub_category' => $p['sub_category'],
        ':harga'        => round($p['harga'], 0),
        ':stok'         => $stok,
        ':stok_minimum' => $stokMin,
    ]);
    $count++;
}

echo "Berhasil mengimpor $count produk dengan harga Rupiah ke database.\n";
