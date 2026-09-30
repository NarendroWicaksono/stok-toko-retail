<?php
class Supplier extends Controller {

    public function index() {
        $supplierModel = $this->model('Supplier_model');

        $search = isset($_GET['search']) ? $_GET['search'] : null;

        $data['title'] = 'Daftar Supplier | Stok Toko Retail';
        if ($search) {
            $data['supplier'] = $supplierModel->searchSupplier($search);
        } else {
            $data['supplier'] = $supplierModel->getAllSupplier();
        }
        $data['current_search'] = $search;

        $this->view('templates/header', $data);
        $this->view('supplier/index', $data);
        $this->view('templates/footer', $data);
    }

    public function tambah() {
        $supplierModel = $this->model('Supplier_model');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['nama'] ?? '');
            $kontak = trim($_POST['kontak'] ?? '');
            $telepon = trim($_POST['telepon'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $alamat = trim($_POST['alamat'] ?? '');

            if (empty($nama)) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Nama supplier wajib diisi.'];
                header('Location: ' . BASEURL . '/Supplier/tambah');
                exit;
            }

            try {
                $supplierModel->tambahSupplier([
                    'nama' => $nama,
                    'kontak' => $kontak,
                    'telepon' => $telepon,
                    'email' => $email,
                    'alamat' => $alamat
                ]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Supplier <strong>' . htmlspecialchars($nama) . '</strong> berhasil ditambahkan.'];
                header('Location: ' . BASEURL . '/Supplier');
                exit;
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Supplier dengan nama tersebut sudah ada.'];
                } else {
                    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Gagal menambahkan supplier: ' . htmlspecialchars($e->getMessage())];
                }
                header('Location: ' . BASEURL . '/Supplier/tambah');
                exit;
            }
        }

        $data['title'] = 'Tambah Supplier | Stok Toko Retail';
        $this->view('templates/header', $data);
        $this->view('supplier/tambah', $data);
        $this->view('templates/footer', $data);
    }

    public function edit($id = null) {
        if (!$id) {
            header('Location: ' . BASEURL . '/Supplier');
            exit;
        }

        $supplierModel = $this->model('Supplier_model');
        $supplier = $supplierModel->getSupplierById($id);

        if (!$supplier) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Supplier tidak ditemukan.'];
            header('Location: ' . BASEURL . '/Supplier');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['nama'] ?? '');
            $kontak = trim($_POST['kontak'] ?? '');
            $telepon = trim($_POST['telepon'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $alamat = trim($_POST['alamat'] ?? '');

            if (empty($nama)) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Nama supplier wajib diisi.'];
                header('Location: ' . BASEURL . '/Supplier/edit/' . $id);
                exit;
            }

            try {
                $supplierModel->updateSupplier([
                    'id' => $id,
                    'nama' => $nama,
                    'kontak' => $kontak,
                    'telepon' => $telepon,
                    'email' => $email,
                    'alamat' => $alamat
                ]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Supplier <strong>' . htmlspecialchars($nama) . '</strong> berhasil diperbarui.'];
                header('Location: ' . BASEURL . '/Supplier');
                exit;
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Supplier dengan nama tersebut sudah ada.'];
                } else {
                    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Gagal memperbarui supplier: ' . htmlspecialchars($e->getMessage())];
                }
                header('Location: ' . BASEURL . '/Supplier/edit/' . $id);
                exit;
            }
        }

        $data['title'] = 'Edit Supplier | Stok Toko Retail';
        $data['supplier'] = $supplier;
        $this->view('templates/header', $data);
        $this->view('supplier/edit', $data);
        $this->view('templates/footer', $data);
    }

    public function detail($id = null) {
        if (!$id) {
            header('Location: ' . BASEURL . '/Supplier');
            exit;
        }

        $supplierModel = $this->model('Supplier_model');
        $supplier = $supplierModel->getSupplierById($id);

        if (!$supplier) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Supplier tidak ditemukan.'];
            header('Location: ' . BASEURL . '/Supplier');
            exit;
        }

        $data['title'] = $supplier['nama'] . ' | Stok Toko Retail';
        $data['supplier'] = $supplier;

        $this->view('templates/header', $data);
        $this->view('supplier/detail', $data);
        $this->view('templates/footer', $data);
    }

    public function hapus($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: ' . BASEURL . '/Supplier');
            exit;
        }

        $supplierModel = $this->model('Supplier_model');
        $supplier = $supplierModel->getSupplierById($id);

        if (!$supplier) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Supplier tidak ditemukan.'];
            header('Location: ' . BASEURL . '/Supplier');
            exit;
        }

        $supplierModel->hapusSupplier($id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Supplier <strong>' . htmlspecialchars($supplier['nama']) . '</strong> berhasil dihapus.'];
        header('Location: ' . BASEURL . '/Supplier');
        exit;
    }
}
