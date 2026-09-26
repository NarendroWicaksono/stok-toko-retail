<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Pemantau Stok Toko Retail">
    <title><?= $data['title'] ?? 'Stok Toko Retail'; ?></title>
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container nav-container">
            <a href="<?= BASEURL; ?>" class="nav-brand">Stok Toko Retail</a>
            <ul class="nav-menu">
                <li><a href="<?= BASEURL; ?>" class="nav-link">Dashboard</a></li>
                <li><a href="<?= BASEURL; ?>/Produk" class="nav-link">Produk</a></li>
                <li><a href="<?= BASEURL; ?>/Produk/stokRendah" class="nav-link nav-alert">Stok Rendah</a></li>
            </ul>
        </div>
    </nav>
    <main class="container main-content">
