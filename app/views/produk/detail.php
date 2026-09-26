<?php $p = $data['produk']; ?>

<a href="<?= BASEURL; ?>/Produk" class="btn btn-back">&larr; Kembali ke Daftar Produk</a>

<h1><?= htmlspecialchars($p['product_name']); ?></h1>

<!-- Pesan Notifikasi (Flash Message) -->
<?php if (isset($_SESSION['flash'])) : ?>
    <div class="alert alert-<?= $_SESSION['flash']['type']; ?>">
        <?= $_SESSION['flash']['message']; ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<div class="detail-grid">
    <div class="card">
        <h2>Informasi Produk</h2>
        <dl class="detail-list">
            <dt>Kode Produk</dt>
            <dd><code><?= htmlspecialchars($p['product_id']); ?></code></dd>

            <dt>Kategori</dt>
            <dd><?= htmlspecialchars($p['category']); ?></dd>

            <dt>Sub-Kategori</dt>
            <dd><?= htmlspecialchars($p['sub_category']); ?></dd>

            <dt>Harga Satuan</dt>
            <dd>Rp <?= number_format($p['harga'], 0, ',', '.'); ?></dd>

            <dt>Total Nilai Stok</dt>
            <dd>Rp <?= number_format($p['harga'] * $p['stok'], 0, ',', '.'); ?></dd>
        </dl>
    </div>

    <div class="card">
        <h2>Status & Manajemen Stok</h2>
        <div class="stok-display <?= ($p['stok'] <= $p['stok_minimum']) ? 'stok-rendah' : 'stok-aman'; ?>">
            <span class="stok-angka"><?= number_format($p['stok'], 0, ',', '.'); ?></span>
            <span class="stok-label">unit tersedia saat ini</span>
        </div>
        <p class="stok-min">Batas Stok Minimum: <strong><?= number_format($p['stok_minimum'], 0, ',', '.'); ?></strong> unit</p>

        <?php if ($p['stok'] <= $p['stok_minimum']) : ?>
            <div class="alert alert-warning">Perhatian: Stok berada di bawah atau sama dengan batas minimum. Segera lakukan restok!</div>
        <?php endif; ?>

        <!-- Form Perbarui Stok -->
        <form method="POST" action="<?= BASEURL; ?>/Produk/updateStok/<?= $p['id']; ?>" class="form-stok">
            <label for="stok">Jumlah Stok Baru:</label>
            <div class="form-inline">
                <input type="number" name="stok" id="stok" class="input" value="<?= $p['stok']; ?>" min="0" required>
                <button type="submit" class="btn">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
