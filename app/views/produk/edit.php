<?php $p = $data['produk']; ?>

<a href="<?= BASEURL; ?>/Produk/detail/<?= $p['id']; ?>" class="btn btn-back">&larr; Kembali ke Detail Produk</a>

<h1>Edit Produk</h1>

<!-- Pesan Notifikasi (Flash Message) -->
<?php if (isset($_SESSION['flash'])) : ?>
    <div class="alert alert-<?= $_SESSION['flash']['type']; ?>">
        <?= $_SESSION['flash']['message']; ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<div class="card" style="max-width: 700px;">
    <form method="POST" action="<?= BASEURL; ?>/Produk/edit/<?= $p['id']; ?>">
        <div class="form-group">
            <label>Kode Produk</label>
            <input type="text" class="input input-full" value="<?= htmlspecialchars($p['product_id']); ?>" disabled style="background:#f0f0f0;">
            <p style="font-size:12px; color:#888; margin-top:4px;">Kode produk tidak dapat diubah.</p>
        </div>
        <div class="form-group">
            <label for="product_name">Nama Produk <span class="required">*</span></label>
            <input type="text" name="product_name" id="product_name" class="input input-full" value="<?= htmlspecialchars($p['product_name']); ?>" required autofocus>
        </div>
        <div class="form-row">
            <div class="form-group form-half">
                <label for="category">Kategori <span class="required">*</span></label>
                <input type="text" name="category" id="category" class="input input-full" value="<?= htmlspecialchars($p['category']); ?>" required>
            </div>
            <div class="form-group form-half">
                <label for="sub_category">Sub-Kategori</label>
                <input type="text" name="sub_category" id="sub_category" class="input input-full" value="<?= htmlspecialchars($p['sub_category']); ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group form-half">
                <label for="harga">Harga Satuan (Rp)</label>
                <input type="number" name="harga" id="harga" class="input input-full" min="0" step="100" value="<?= $p['harga']; ?>">
            </div>
            <div class="form-group form-half">
                <label for="stok_minimum">Stok Minimum</label>
                <input type="number" name="stok_minimum" id="stok_minimum" class="input input-full" min="0" value="<?= $p['stok_minimum']; ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group form-half">
                <label for="kategori_id">Kategori Master</label>
                <select name="kategori_id" id="kategori_id" class="input input-full">
                    <option value="">— Tidak ada —</option>
                    <?php foreach ($data['kategori_list'] as $kat) : ?>
                    <option value="<?= $kat['id']; ?>" <?= (isset($p['kategori_id']) && $p['kategori_id'] == $kat['id']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($kat['nama']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group form-half">
                <label for="supplier_id">Supplier</label>
                <select name="supplier_id" id="supplier_id" class="input input-full">
                    <option value="">— Tidak ada —</option>
                    <?php foreach ($data['supplier_list'] as $sup) : ?>
                    <option value="<?= $sup['id']; ?>" <?= (isset($p['supplier_id']) && $p['supplier_id'] == $sup['id']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($sup['nama']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn">Simpan Perubahan</button>
            <a href="<?= BASEURL; ?>/Produk/detail/<?= $p['id']; ?>" class="btn btn-back" style="margin-bottom:0;">Batal</a>
        </div>
    </form>
</div>
