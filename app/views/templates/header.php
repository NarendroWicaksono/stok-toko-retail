<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Pemantau Stok Toko Retail">
    <title><?= $data['title'] ?? 'Stok Toko Retail'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
    *, *::before, *::after, body, input, button, select, textarea, table, th, td, h1, h2, h3, h4, h5, h6 {
      font-family: "Segoe UI Variable Display", "Segoe UI Variable Text", "Segoe UI Variable", "Segoe UI", "Inter", -apple-system, BlinkMacSystemFont, Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    <?php 
    $cssPath = __DIR__ . '/../../../public/css/style.css';
    if (file_exists($cssPath)) {
        include $cssPath;
    }
    ?>

    /* === Responsive: navbar & container === */
    .container {
      width: 100%;
      max-width: 1100px;
      margin: 0 auto;
      padding: 0 16px;
      box-sizing: border-box;
    }

    .nav-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }

    .nav-menu {
      display: flex;
      list-style: none;
      gap: 16px;
      margin: 0;
      padding: 0;
    }

    @media (max-width: 768px) {
      .nav-container {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
      }
      .nav-menu {
        flex-direction: column;
        width: 100%;
        gap: 4px;
      }
      .nav-link {
        display: block;
        padding: 8px 0;
      }
      .main-content {
        padding: 0 10px;
      }
      table {
        font-size: 13px;
      }
      h1 { font-size: 1.4rem; }
      h2 { font-size: 1.1rem; }
    }

    /* Tabel jadi scrollable horizontal di HP */
    @media (max-width: 480px) {
      table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
      }
    }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container nav-container">
            <a href="<?= BASEURL; ?>" class="nav-brand">Stok Toko Retail</a>
            <ul class="nav-menu">
                <li><a href="<?= BASEURL; ?>" class="nav-link">Dashboard</a></li>
                <li><a href="<?= BASEURL; ?>/Produk" class="nav-link">Produk</a></li>
                <li><a href="<?= BASEURL; ?>/Kategori" class="nav-link">Kategori</a></li>
                <li><a href="<?= BASEURL; ?>/Supplier" class="nav-link">Supplier</a></li>
                <li><a href="<?= BASEURL; ?>/Produk/stokRendah" class="nav-link nav-alert">Stok Rendah</a></li>
            </ul>
        </div>
    </nav>
    <main class="container main-content">
