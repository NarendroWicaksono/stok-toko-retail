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

        <!-- Form 1: Tambah Stok (Restok Masuk) -->
        <div class="box-tambah-stok">
            <h3 style="margin-bottom: 8px; color: #1b5e20;">+ Tambah Stok (Restok Masuk)</h3>
            <p style="font-size: 13px; color: #555; margin-bottom: 10px;">Pilih jumlah cepat atau ketik jumlah barang masuk yang ingin ditambahkan:</p>
            
            <div class="quick-add-group" style="display: flex; gap: 8px; margin-bottom: 12px; flex-wrap: wrap;">
                <button type="button" class="btn-quick" onclick="setQuickAdd(1)">+1</button>
                <button type="button" class="btn-quick" onclick="setQuickAdd(5)">+5</button>
                <button type="button" class="btn-quick" onclick="setQuickAdd(10)">+10</button>
                <button type="button" class="btn-quick" onclick="setQuickAdd(20)">+20</button>
                <button type="button" class="btn-quick" onclick="setQuickAdd(50)">+50</button>
                <button type="button" class="btn-quick" onclick="setQuickAdd(100)">+100</button>
            </div>

            <form method="POST" action="<?= BASEURL; ?>/Produk/updateStok/<?= $p['id']; ?>" class="form-stok" style="margin-top: 0; background: #e8f5e9; border-color: #2e7d32;">
                <input type="hidden" name="aksi" value="tambah">
                <label for="jumlah_tambah" style="color: #1b5e20;">Jumlah Unit Masuk:</label>
                <div class="form-inline">
                    <input type="number" name="jumlah_tambah" id="jumlah_tambah" class="input" value="10" min="1" required style="border-color: #2e7d32; font-weight: bold; font-size: 16px;">
                    <button type="submit" class="btn btn-success" style="background-color: #2e7d32; border-color: #1b5e20; font-size: 15px;">
                        &#10010; Tambah Stok
                    </button>
                </div>
            </form>
        </div>

        <!-- Form 2: Atur Total Stok Manual (Stok Opname) -->
        <div style="margin-top: 24px; padding-top: 16px; border-top: 1px dashed #ccc;">
            <details>
                <summary style="cursor: pointer; font-weight: 700; color: #444; font-size: 14px;">
                    Atur Total Stok Manual (Koreksi / Stok Opname)
                </summary>
                <form method="POST" action="<?= BASEURL; ?>/Produk/updateStok/<?= $p['id']; ?>" class="form-stok" style="margin-top: 10px;">
                    <label for="stok">Koreksi Jumlah Total Stok Fisik:</label>
                    <div class="form-inline">
                        <input type="number" name="stok" id="stok" class="input" value="<?= $p['stok']; ?>" min="0" required>
                        <button type="submit" class="btn btn-secondary" style="background-color: #555; border-color: #333;">Simpan Perubahan Total</button>
                    </div>
                </form>
            </details>
        </div>
    </div>
</div>

<script>
function setQuickAdd(val) {
    const input = document.getElementById('jumlah_tambah');
    if (input) {
        input.value = val;
        input.focus();
    }
}
</script>
