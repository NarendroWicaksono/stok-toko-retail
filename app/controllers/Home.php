<?php
class Home extends Controller {
    public function index() {
        $produkModel = $this->model('Produk_model');
        $supplierModel = $this->model('Supplier_model');
        $kategoriModel = $this->model('Kategori_model');

        $data['title'] = 'Dashboard | Stok Toko Retail';
        $data['total_produk'] = $produkModel->getTotalProduk();
        $data['total_stok_rendah'] = $produkModel->getTotalStokRendah();
        $data['total_kategori'] = $kategoriModel->getTotalKategori();
        $data['total_supplier'] = $supplierModel->getTotalSupplier();
        $data['nilai_stok'] = $produkModel->getNilaiStok();
        $data['stok_rendah'] = $produkModel->getStokRendah();
        $data['stok_by_category'] = $produkModel->getStokByCategory();

        $this->view('templates/header', $data);
        $this->view('home/index', $data);
        $this->view('templates/footer', $data);
    }
}