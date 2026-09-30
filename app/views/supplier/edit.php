<?php $s = $data['supplier']; ?>

<a href="<?= BASEURL; ?>/Supplier" class="btn btn-back">&larr; Kembali ke Daftar Supplier</a>

<h1>Edit Supplier</h1>

<!-- Pesan Notifikasi (Flash Message) -->
<?php if (isset($_SESSION['flash'])) : ?>
    <div class="alert alert-<?= $_SESSION['flash']['type']; ?>">
        <?= $_SESSION['flash']['message']; ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<div class="card" style="max-width: 650px;">
    <form method="POST" action="<?= BASEURL; ?>/Supplier/edit/<?= $s['id']; ?>">
        <div class="form-group">
            <label for="nama">Nama Supplier <span class="required">*</span></label>
            <input type="text" name="nama" id="nama" class="input input-full" value="<?= htmlspecialchars($s['nama']); ?>" required autofocus>
        </div>
        <div class="form-row">
            <div class="form-group form-half">
                <label for="kontak">Nama Kontak</label>
                <input type="text" name="kontak" id="kontak" class="input input-full" value="<?= htmlspecialchars($s['kontak'] ?? ''); ?>">
            </div>
            <div class="form-group form-half">
                <label for="telepon">Telepon</label>
                <input type="text" name="telepon" id="telepon" class="input input-full" value="<?= htmlspecialchars($s['telepon'] ?? ''); ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="input input-full" value="<?= htmlspecialchars($s['email'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="alamat">Alamat</label>
            <textarea name="alamat" id="alamat" class="input input-full textarea" rows="3"><?= htmlspecialchars($s['alamat'] ?? ''); ?></textarea>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn">Simpan Perubahan</button>
            <a href="<?= BASEURL; ?>/Supplier" class="btn btn-back" style="margin-bottom:0;">Batal</a>
        </div>
    </form>
</div>
