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
}
