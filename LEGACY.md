# Documentasi Legacy & Panduan Developer — Stok Toko Retail (MVC PHP)

Selamat datang! Dokumen ini dibuat khusus untuk membantu programmer/developer berikutnya dalam memahami, memelihara, dan mengembangkan aplikasi **Stok Toko Retail** tanpa kebingungan.

---

## 1. Ringkasan Aplikasi
**Stok Toko Retail** adalah aplikasi web sederhana berbasis **PHP Native dengan pola arsitektur MVC (Model-View-Controller)** yang dirancang untuk pemilik toko retail dalam memantau dan mengelola stok produk.

### Fitur Utama:
- **Dasbor Pemantauan**: Ringkasan total produk, kategori, nilai aset stok (dalam Rupiah), serta produk stok rendah.
- **Katalog & Inventaris Produk**: Daftar produk lengkap dengan fitur pencarian nama/kode, filter kategori, dan pagination.
- **Peringatan Stok Rendah**: Deteksi otomatis produk yang stoknya di bawah batas minimum (safety stock).
- **Manajemen & Restok Produk**: Kemampuan melihat detail produk dan memperbarui jumlah stok secara langsung.

---

## 2. Struktur Direktori & File

Berikut adalah peta struktur folder proyek:

```text
permweb/
├── api/
│   └── index.php               # Entry point Vercel Serverless Function
├── app/                        # Direktori utama logika aplikasi (MVC Core)
│   ├── .htaccess               # Mencegah akses langsung ke direktori app
│   ├── init.php                # Entry loader (memuat core class & konfigurasi)
│   ├── config/
│   │   └── config.php          # Konfigurasi konstanta (BASEURL & Database)
│   ├── controllers/
│   │   ├── Home.php            # Controller untuk halaman Dasbor
│   │   └── Produk.php          # Controller untuk halaman Katalog & Stok Produk
│   ├── core/
│   │   ├── App.php             # Core Router (Parsing URL & Dispatch Controller/Method)
│   │   ├── Controller.php      # Base Controller (Loader untuk View & Model)
│   │   ├── Database.php        # PDO Database Wrapper (Handler Query & Binding)
│   │   └── Flasher.php         # Handler pesan notifikasi flash session
│   ├── models/
│   │   └── Produk_model.php    # Model data produk (Query MySQL CRUD & Statistik)
│   └── views/
│       ├── home/
│       │   └── index.php       # Tampilan halaman Dasbor Utama
│       ├── produk/
│       │   ├── index.php       # Tampilan daftar produk (tabel, pencarian, pagination)
│       │   ├── detail.php      # Tampilan detail produk & form perbarui stok
│       │   └── stok_rendah.php # Tampilan daftar produk yang perlu direstok
│       └── templates/
│           ├── header.php      # Template Navigasi & HTML Head
│           └── footer.php      # Template Footer HTML
├── database/
│   ├── schema.sql              # Skema SQL tabel database MySQL (stok_toko)
│   └── import.php              # Script pengimpor data dari train.csv ke MySQL
├── public/                     # Document root web (yang diakses publik)
│   ├── .htaccess               # URL Rewriting Apache (Pretty URL)
│   ├── router.php              # Fallback Router untuk PHP Built-in Server (php -S)
│   ├── index.php               # Front Controller (entry point utama aplikasi)
│   └── css/
│       └── style.css           # Styling CSS Vanilla (Putih #FFFFFF, Merah Solid & Kontras Tinggi)
├── vercel.json                 # Konfigurasi deploy ke Vercel Serverless
├── train.csv                   # Dataset awal produk retail
└── LEGACY.md                   # Dokumen ini (Panduan Developer)
```

---

## 3. Cara Kolaborasi GitHub & Deploy Vercel

Agar Anda dan teman Anda bisa mengedit kode bersama di GitHub dan ter-update otomatis di Vercel:

### Langkah 1: Tambahkan Teman sebagai Collaborator di GitHub
1. Buka repository proyek di GitHub (`https://github.com/rahmattulus/tamplate-mvc`).
2. Masuk ke **Settings** -> **Collaborators**.
3. Klik **Add people**, masukkan username GitHub teman Anda, lalu kirim undangan.
4. Teman Anda harus menerima undangan tersebut.

### Langkah 2: Hubungkan GitHub ke Vercel
1. Login ke [vercel.com](https://vercel.com).
2. Klik **Add New** -> **Project**.
3. Pilih repository **`tamplate-mvc`** dari akun GitHub Anda.
4. Di bagian **Environment Variables**, isi kredensial MySQL cloud (host, user, password, db name).
5. Klik **Deploy**.

### Bagaimana Alur Kerjanya?
- Setiap kali Anda atau teman Anda melakukan **`git push`** ke branch `main`, Vercel akan **otomatis melakukan build & deploy ulang** URL aplikasi Anda secara live!
- Jika teman Anda membuat Pull Request (PR), Vercel akan otomatis membuat **Preview Deployment** unik untuk menguji perubahan sebelum di-merge.

---
*Dokumen ini dibuat secara otomatis sebagai panduan estafet pengembang untuk proyek Stok Toko Retail.*
