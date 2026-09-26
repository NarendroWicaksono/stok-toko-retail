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
        $stok = isset($_POST['stok']) ? (int)$_POST['stok'] : 0;

        if ($produkModel->updateStok($id, $stok) > 0) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Stok berhasil diperbarui.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Gagal memperbarui stok.'];
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
