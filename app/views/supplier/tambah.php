<a href="<?= BASEURL; ?>/Supplier" class="btn btn-back">&larr; Kembali ke Daftar Supplier</a>

<h1>Tambah Supplier Baru</h1>

<!-- Pesan Notifikasi (Flash Message) -->
<?php if (isset($_SESSION['flash'])) : ?>
    <div class="alert alert-<?= $_SESSION['flash']['type']; ?>">
        <?= $_SESSION['flash']['message']; ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<div class="card" style="max-width: 650px;">
    <form method="POST" action="<?= BASEURL; ?>/Supplier/tambah">
        <div class="form-group">
            <label for="nama">Nama Supplier <span class="required">*</span></label>
            <input type="text" name="nama" id="nama" class="input input-full" placeholder="Nama perusahaan/toko supplier" required autofocus>
        </div>
        <div class="form-row">
            <div class="form-group form-half">
                <label for="kontak">Nama Kontak</label>
                <input type="text" name="kontak" id="kontak" class="input input-full" placeholder="Nama PIC / kontak person">
            </div>
            <div class="form-group form-half">
                <label for="telepon">Telepon</label>
                <input type="text" name="telepon" id="telepon" class="input input-full" placeholder="08xx-xxxx-xxxx">
            </div>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="input input-full" placeholder="email@supplier.com">
        </div>
        <div class="form-group">
            <label for="alamat">Alamat</label>
            <textarea name="alamat" id="alamat" class="input input-full textarea" rows="3" placeholder="Alamat lengkap supplier"></textarea>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn">Simpan Supplier</button>
            <a href="<?= BASEURL; ?>/Supplier" class="btn btn-back" style="margin-bottom:0;">Batal</a>
        </div>
    </form>
</div>
