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

        // Jika request berupa tambah stok
        if (isset($_POST['aksi']) && $_POST['aksi'] === 'tambah') {
            $jumlah = isset($_POST['jumlah_tambah']) ? (int)$_POST['jumlah_tambah'] : 0;
            if ($jumlah > 0) {
                $produkModel->tambahStok($id, $jumlah);
                $stokAkhir = $produk['stok'] + $jumlah;
                $_SESSION['flash'] = [
                    'type' => 'success', 
                    'message' => "Berhasil menambahkan <strong>+{$jumlah} unit</strong> stok! Total stok sekarang: <strong>{$stokAkhir} unit</strong>."
                ];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Jumlah penambahan stok harus lebih dari 0.'];
            }
            header('Location: ' . BASEURL . '/Produk/detail/' . $id);
            exit;
        }

        // Atur total stok baru
        $stokBaru = isset($_POST['stok']) ? max(0, (int)$_POST['stok']) : 0;

        if ($stokBaru === (int)$produk['stok']) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Jumlah stok disimpan (tidak ada perubahan angka).'];
        } else {
            $produkModel->updateStok($id, $stokBaru);
            $_SESSION['flash'] = [
                'type' => 'success', 
                'message' => "Stok berhasil diperbarui menjadi <strong>{$stokBaru} unit</strong>."
            ];
        }

        header('Location: ' . BASEURL . '/Produk/detail/' . $id);
        exit;
    }

    public function tambahStok($id = null) {
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

        $jumlah = isset($_POST['jumlah']) ? (int)$_POST['jumlah'] : (isset($_POST['jumlah_tambah']) ? (int)$_POST['jumlah_tambah'] : 0);

        if ($jumlah > 0) {
            $produkModel->tambahStok($id, $jumlah);
            $stokAkhir = $produk['stok'] + $jumlah;
            $_SESSION['flash'] = [
                'type' => 'success', 
                'message' => "Berhasil menambahkan <strong>+{$jumlah} unit</strong> stok! Total stok sekarang: <strong>{$stokAkhir} unit</strong>."
            ];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Jumlah penambahan stok harus lebih dari 0.'];
        }

        header('Location: ' . BASEURL . '/Produk/detail/' . $id);
        exit;
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
