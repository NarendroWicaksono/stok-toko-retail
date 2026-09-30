<a href="<?= BASEURL; ?>/Produk" class="btn btn-back">&larr; Kembali ke Daftar Produk</a>

<h1>Tambah Produk Baru</h1>

<!-- Pesan Notifikasi (Flash Message) -->
<?php if (isset($_SESSION['flash'])) : ?>
    <div class="alert alert-<?= $_SESSION['flash']['type']; ?>">
        <?= $_SESSION['flash']['message']; ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<div class="card" style="max-width: 700px;">
    <form method="POST" action="<?= BASEURL; ?>/Produk/tambah">
        <div class="form-row">
            <div class="form-group form-half">
                <label for="product_id">Kode Produk <span class="required">*</span></label>
                <input type="text" name="product_id" id="product_id" class="input input-full" placeholder="Contoh: PRD-001" required autofocus>
            </div>
            <div class="form-group form-half">
                <label for="product_name">Nama Produk <span class="required">*</span></label>
                <input type="text" name="product_name" id="product_name" class="input input-full" placeholder="Nama lengkap produk" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group form-half">
                <label for="category">Kategori <span class="required">*</span></label>
                <input type="text" name="category" id="category" class="input input-full" placeholder="Contoh: Furniture" required>
            </div>
            <div class="form-group form-half">
                <label for="sub_category">Sub-Kategori</label>
                <input type="text" name="sub_category" id="sub_category" class="input input-full" placeholder="Contoh: Chairs">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group form-half">
                <label for="harga">Harga Satuan (Rp)</label>
                <input type="number" name="harga" id="harga" class="input input-full" min="0" step="100" value="0">
            </div>
            <div class="form-group form-half">
                <label for="stok">Stok Awal</label>
                <input type="number" name="stok" id="stok" class="input input-full" min="0" value="0">
            </div>
        </div>
        <div class="form-group">
            <label for="stok_minimum">Stok Minimum (Batas Peringatan)</label>
            <input type="number" name="stok_minimum" id="stok_minimum" class="input" style="width:150px;" min="0" value="5">
        </div>
        <div class="form-row">
            <div class="form-group form-half">
                <label for="kategori_id">Kategori Master</label>
                <select name="kategori_id" id="kategori_id" class="input input-full">
                    <option value="">— Tidak ada —</option>
                    <?php foreach ($data['kategori_list'] as $kat) : ?>
                    <option value="<?= $kat['id']; ?>"><?= htmlspecialchars($kat['nama']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group form-half">
                <label for="supplier_id">Supplier</label>
                <select name="supplier_id" id="supplier_id" class="input input-full">
                    <option value="">— Tidak ada —</option>
                    <?php foreach ($data['supplier_list'] as $sup) : ?>
                    <option value="<?= $sup['id']; ?>"><?= htmlspecialchars($sup['nama']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn">Simpan Produk</button>
            <a href="<?= BASEURL; ?>/Produk" class="btn btn-back" style="margin-bottom:0;">Batal</a>
        </div>
    </form>
</div>
