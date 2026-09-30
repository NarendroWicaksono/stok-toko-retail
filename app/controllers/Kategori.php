<?php
class Kategori extends Controller {

    public function index() {
        $kategoriModel = $this->model('Kategori_model');

        $search = isset($_GET['search']) ? $_GET['search'] : null;

        $data['title'] = 'Daftar Kategori | Stok Toko Retail';
        if ($search) {
            $data['kategori'] = $kategoriModel->searchKategori($search);
        } else {
            $data['kategori'] = $kategoriModel->getAllKategori();
        }
        $data['current_search'] = $search;

        $this->view('templates/header', $data);
        $this->view('kategori/index', $data);
        $this->view('templates/footer', $data);
    }

    public function tambah() {
        $kategoriModel = $this->model('Kategori_model');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['nama'] ?? '');
            $deskripsi = trim($_POST['deskripsi'] ?? '');

            if (empty($nama)) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Nama kategori wajib diisi.'];
                header('Location: ' . BASEURL . '/Kategori/tambah');
                exit;
            }

            try {
                $kategoriModel->tambahKategori([
                    'nama' => $nama,
                    'deskripsi' => $deskripsi
                ]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Kategori <strong>' . htmlspecialchars($nama) . '</strong> berhasil ditambahkan.'];
                header('Location: ' . BASEURL . '/Kategori');
                exit;
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Kategori dengan nama tersebut sudah ada.'];
                } else {
                    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Gagal menambahkan kategori: ' . htmlspecialchars($e->getMessage())];
                }
                header('Location: ' . BASEURL . '/Kategori/tambah');
                exit;
            }
        }

        $data['title'] = 'Tambah Kategori | Stok Toko Retail';
        $this->view('templates/header', $data);
        $this->view('kategori/tambah', $data);
        $this->view('templates/footer', $data);
    }

    public function edit($id = null) {
        if (!$id) {
            header('Location: ' . BASEURL . '/Kategori');
            exit;
        }

        $kategoriModel = $this->model('Kategori_model');
        $kategori = $kategoriModel->getKategoriById($id);

        if (!$kategori) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Kategori tidak ditemukan.'];
            header('Location: ' . BASEURL . '/Kategori');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['nama'] ?? '');
            $deskripsi = trim($_POST['deskripsi'] ?? '');

            if (empty($nama)) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Nama kategori wajib diisi.'];
                header('Location: ' . BASEURL . '/Kategori/edit/' . $id);
                exit;
            }

            try {
                $kategoriModel->updateKategori([
                    'id' => $id,
                    'nama' => $nama,
                    'deskripsi' => $deskripsi
                ]);
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Kategori <strong>' . htmlspecialchars($nama) . '</strong> berhasil diperbarui.'];
                header('Location: ' . BASEURL . '/Kategori');
                exit;
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Kategori dengan nama tersebut sudah ada.'];
                } else {
                    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Gagal memperbarui kategori: ' . htmlspecialchars($e->getMessage())];
                }
                header('Location: ' . BASEURL . '/Kategori/edit/' . $id);
                exit;
            }
        }

        $data['title'] = 'Edit Kategori | Stok Toko Retail';
        $data['kategori'] = $kategori;
        $this->view('templates/header', $data);
        $this->view('kategori/edit', $data);
        $this->view('templates/footer', $data);
    }

    public function hapus($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: ' . BASEURL . '/Kategori');
            exit;
        }

        $kategoriModel = $this->model('Kategori_model');
        $kategori = $kategoriModel->getKategoriById($id);

        if (!$kategori) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Kategori tidak ditemukan.'];
            header('Location: ' . BASEURL . '/Kategori');
            exit;
        }

        $kategoriModel->hapusKategori($id);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Kategori <strong>' . htmlspecialchars($kategori['nama']) . '</strong> berhasil dihapus.'];
        header('Location: ' . BASEURL . '/Kategori');
        exit;
    }
}
