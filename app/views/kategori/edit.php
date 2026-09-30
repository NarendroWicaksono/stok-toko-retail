<?php $k = $data['kategori']; ?>

<a href="<?= BASEURL; ?>/Kategori" class="btn btn-back">&larr; Kembali ke Daftar Kategori</a>

<h1>Edit Kategori</h1>

<!-- Pesan Notifikasi (Flash Message) -->
<?php if (isset($_SESSION['flash'])) : ?>
    <div class="alert alert-<?= $_SESSION['flash']['type']; ?>">
        <?= $_SESSION['flash']['message']; ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<div class="card" style="max-width: 600px;">
    <form method="POST" action="<?= BASEURL; ?>/Kategori/edit/<?= $k['id']; ?>">
        <div class="form-group">
            <label for="nama">Nama Kategori <span class="required">*</span></label>
            <input type="text" name="nama" id="nama" class="input input-full" value="<?= htmlspecialchars($k['nama']); ?>" required autofocus>
        </div>
        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="input input-full textarea" rows="3"><?= htmlspecialchars($k['deskripsi'] ?? ''); ?></textarea>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn">Simpan Perubahan</button>
            <a href="<?= BASEURL; ?>/Kategori" class="btn btn-back" style="margin-bottom:0;">Batal</a>
        </div>
    </form>
</div>
