<?php
class Produk extends Controller {
    public function index() {
        $produkModel = $this->model('Produk_model');

        $category = isset($_GET['category']) ? $_GET['category'] : null;
        $search   = isset($_GET['search']) ? $_GET['search'] : null;
        $page     = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage  = 20;
        $offset   = ($page - 1) * $perPage;

        $data['title'] = 'Daftar Produk | Stok Toko Retail';
        $data['produk'] = $produkModel->getProdukPaged($offset, $perPage, $category, $search);
        $data['total'] = $produkModel->countProduk($category, $search);
        $data['categories'] = $produkModel->getCategories();
        $data['current_category'] = $category;
        $data['current_search'] = $search;
        $data['current_page'] = $page;
        $data['total_pages'] = ceil($data['total'] / $perPage);

        $this->view('templates/header', $data);
        $this->view('produk/index', $data);
        $this->view('templates/footer', $data);
    }

    public function detail($id = null) {
        if (!$id) {
            header('Location: ' . BASEURL . '/Produk');
            exit;
        }

        $produkModel = $this->model('Produk_model');
        $data['produk'] = $produkModel->getProdukById($id);

        if (!$data['produk']) {
            header('Location: ' . BASEURL . '/Produk');
            exit;
        }

        $data['title'] = $data['produk']['product_name'] . ' | Stok Toko Retail';

        $this->view('templates/header', $data);
        $this->view('produk/detail', $data);
        $this->view('templates/footer', $data);
    }

    public function updateStok($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: ' . BASEURL . '/Produk');
            exit;
        }

        $produkModel = $this->model('Produk_model');
        $produk = $produkModel->getProdukById($id);

        if (!$produk) {
            header('Location: ' . BASEURL . '/Produk');
            exit;
        }

        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') 
                  || (isset($_POST['is_ajax']) && $_POST['is_ajax'] == '1')
                  || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

        $aksi = $_POST['aksi'] ?? 'set';
        $stokSekarang = (int)$produk['stok'];

        if ($aksi === 'tambah') {
            $jumlah = isset($_POST['jumlah_tambah']) ? (int)$_POST['jumlah_tambah'] : (isset($_POST['jumlah']) ? (int)$_POST['jumlah'] : 1);
            if ($jumlah <= 0) $jumlah = 1;
            
            $produkModel->tambahStok($id, $jumlah);
            $stokAkhir = $stokSekarang + $jumlah;
            $msg = "Berhasil menambahkan <strong>+{$jumlah} unit</strong> stok! Total stok sekarang: <strong>{$stokAkhir} unit</strong>.";
            
            $_SESSION['flash'] = ['type' => 'success', 'message' => $msg];
        } elseif ($aksi === 'kurang') {
            $jumlah = isset($_POST['jumlah_kurang']) ? (int)$_POST['jumlah_kurang'] : (isset($_POST['jumlah']) ? (int)$_POST['jumlah'] : 1);
            if ($jumlah <= 0) $jumlah = 1;

            $produkModel->kurangStok($id, $jumlah);
            $stokAkhir = max(0, $stokSekarang - $jumlah);
            $msg = "Berhasil mengurangi <strong>-{$jumlah} unit</strong> stok! Total stok sekarang: <strong>{$stokAkhir} unit</strong>.";
            
            $_SESSION['flash'] = ['type' => 'success', 'message' => $msg];
        } else {
            // Atur total stok manual
            $stokBaru = isset($_POST['stok']) ? max(0, (int)$_POST['stok']) : 0;
            $stokAkhir = $stokBaru;

            if ($stokBaru === $stokSekarang) {
                $msg = 'Jumlah stok disimpan (tidak ada perubahan angka).';
                $_SESSION['flash'] = ['type' => 'success', 'message' => $msg];
            } else {
                $produkModel->updateStok($id, $stokBaru);
                $msg = "Stok berhasil diperbarui menjadi <strong>{$stokBaru} unit</strong>.";
                $_SESSION['flash'] = ['type' => 'success', 'message' => $msg];
            }
        }

        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => true,
                'stok' => $stokAkhir,
                'stok_formatted' => number_format($stokAkhir, 0, ',', '.'),
                'stok_minimum' => (int)$produk['stok_minimum'],
                'is_low' => ($stokAkhir <= (int)$produk['stok_minimum']),
                'total_nilai' => 'Rp ' . number_format($produk['harga'] * $stokAkhir, 0, ',', '.'),
                'message' => $msg
            ]);
            exit;
        }

        header('Location: ' . BASEURL . '/Produk/detail/' . $id);
        exit;
    }

    public function tambahStok($id = null) {
        $_POST['aksi'] = 'tambah';
        return $this->updateStok($id);
    }

    public function stokRendah() {
        $produkModel = $this->model('Produk_model');

        $data['title'] = 'Stok Rendah | Stok Toko Retail';
        $data['produk'] = $produkModel->getStokRendah();

        $this->view('templates/header', $data);
        $this->view('produk/stok_rendah', $data);
        $this->view('templates/footer', $data);
    }

    public function tambah() {
        $produkModel = $this->model('Produk_model');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = trim($_POST['product_id'] ?? '');
            $productName = trim($_POST['product_name'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $subCategory = trim($_POST['sub_category'] ?? '');
            $harga = isset($_POST['harga']) ? max(0, (float)$_POST['harga']) : 0;
            $stok = isset($_POST['stok']) ? max(0, (int)$_POST['stok']) : 0;
            $stokMinimum = isset($_POST['stok_minimum']) ? max(0, (int)$_POST['stok_minimum']) : 5;
            $kategoriId = !empty($_POST['kategori_id']) ? (int)$_POST['kategori_id'] : null;
            $supplierId = !empty($_POST['supplier_id']) ? (int)$_POST['supplier_id'] : null;

            if (empty($productId) || empty($productName) || empty($category)) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Kode produk, nama produk, dan kategori wajib diisi.'];
                header('Location: ' . BASEURL . '/Produk/tambah');
                exit;
            }

            try {
                $produkModel->tambahProduk([
                    'product_id' => $productId,
                    'product_name' => $productName,
                    'category' => $category,
                    'sub_category' => $subCategory,
                    'harga' => $harga,
                    'stok' => $stok,
                    'stok_minimum' => $stokMinimum,
                    'kategori_id' => $kategoriId,
                    'supplier_id' => $supplierId
                ]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Produk <strong>' . htmlspecialchars($productName) . '</strong> berhasil ditambahkan.'];
                header('Location: ' . BASEURL . '/Produk');
                exit;
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Produk dengan kode tersebut sudah ada.'];
                } else {
                    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Gagal menambahkan produk: ' . htmlspecialchars($e->getMessage())];
                }
                header('Location: ' . BASEURL . '/Produk/tambah');
                exit;
            }
        }

        // Load dropdown data for kategori and supplier
        $kategoriModel = $this->model('Kategori_model');
        $supplierModel = $this->model('Supplier_model');

        $data['title'] = 'Tambah Produk | Stok Toko Retail';
        $data['kategori_list'] = $kategoriModel->getKategoriForDropdown();
        $data['supplier_list'] = $supplierModel->getSupplierForDropdown();

        $this->view('templates/header', $data);
        $this->view('produk/tambah', $data);
        $this->view('templates/footer', $data);
    }

    public function edit($id = null) {
        if (!$id) {
            header('Location: ' . BASEURL . '/Produk');
            exit;
        }

        $produkModel = $this->model('Produk_model');
        $produk = $produkModel->getProdukById($id);

        if (!$produk) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Produk tidak ditemukan.'];
            header('Location: ' . BASEURL . '/Produk');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productName = trim($_POST['product_name'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $subCategory = trim($_POST['sub_category'] ?? '');
            $harga = isset($_POST['harga']) ? max(0, (float)$_POST['harga']) : 0;
            $stokMinimum = isset($_POST['stok_minimum']) ? max(0, (int)$_POST['stok_minimum']) : 5;
            $kategoriId = !empty($_POST['kategori_id']) ? (int)$_POST['kategori_id'] : null;
            $supplierId = !empty($_POST['supplier_id']) ? (int)$_POST['supplier_id'] : null;

            if (empty($productName) || empty($category)) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Nama produk dan kategori wajib diisi.'];
                header('Location: ' . BASEURL . '/Produk/edit/' . $id);
                exit;
            }

            try {
                $produkModel->updateProduk([
                    'id' => $id,
                    'product_name' => $productName,
                    'category' => $category,
                    'sub_category' => $subCategory,
                    'harga' => $harga,
                    'stok_minimum' => $stokMinimum,
                    'kategori_id' => $kategoriId,
                    'supplier_id' => $supplierId
                ]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Produk <strong>' . htmlspecialchars($productName) . '</strong> berhasil diperbarui.'];
                header('Location: ' . BASEURL . '/Produk/detail/' . $id);
                exit;
            } catch (PDOException $e) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Gagal memperbarui produk: ' . htmlspecialchars($e->getMessage())];
                header('Location: ' . BASEURL . '/Produk/edit/' . $id);
                exit;
            }
        }

        // Load dropdown data
        $kategoriModel = $this->model('Kategori_model');
        $supplierModel = $this->model('Supplier_model');

        $data['title'] = 'Edit Produk | Stok Toko Retail';
        $data['produk'] = $produk;
        $data['kategori_list'] = $kategoriModel->getKategoriForDropdown();
        $data['supplier_list'] = $supplierModel->getSupplierForDropdown();

        $this->view('templates/header', $data);
        $this->view('produk/edit', $data);
        $this->view('templates/footer', $data);
    }

    public function hapus($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: ' . BASEURL . '/Produk');
            exit;
        }

        $produkModel = $this->model('Produk_model');
        $produk = $produkModel->getProdukById($id);

        if (!$produk) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Produk tidak ditemukan.'];
            header('Location: ' . BASEURL . '/Produk');
            exit;
        }

        $produkModel->hapusProduk($id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Produk <strong>' . htmlspecialchars($produk['product_name']) . '</strong> berhasil dihapus.'];
        header('Location: ' . BASEURL . '/Produk');
        exit;
    }
}
