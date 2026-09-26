<h1>Daftar Stok Produk</h1>

<!-- Filter & Pencarian -->
<div class="toolbar">
    <form method="GET" action="<?= BASEURL; ?>/Produk" class="toolbar-form">
        <select name="category" class="input">
            <option value="">Semua Kategori</option>
            <?php foreach ($data['categories'] as $cat) : ?>
            <option value="<?= htmlspecialchars($cat['category']); ?>" <?= ($data['current_category'] === $cat['category']) ? 'selected' : ''; ?>>
                <?= htmlspecialchars($cat['category']); ?>
            </option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="search" class="input input-search" placeholder="Cari nama atau kode produk..." 
               value="<?= htmlspecialchars($data['current_search'] ?? ''); ?>">
        <button type="submit" class="btn">Cari</button>
    </form>
    <span class="toolbar-info"><?= number_format($data['total'], 0, ',', '.'); ?> produk ditemukan</span>
</div>

<!-- Tabel Produk -->
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Sub-Kategori</th>
                <th>Harga Satuan</th>
                <th>Stok</th>
                <th>Min</th>
                <th>Status Stok</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data['produk'])) : ?>
            <tr><td colspan="8" class="text-center">Tidak ada produk yang sesuai dengan pencarian.</td></tr>
            <?php else : ?>
            <?php foreach ($data['produk'] as $p) : ?>
            <tr class="<?= ($p['stok'] <= $p['stok_minimum']) ? 'row-warning' : ''; ?>">
                <td><code><?= htmlspecialchars($p['product_id']); ?></code></td>
                <td>
                    <a href="<?= BASEURL; ?>/Produk/detail/<?= $p['id']; ?>">
                        <?= htmlspecialchars(mb_substr($p['product_name'], 0, 55)); ?>
                    </a>
                </td>
                <td><?= htmlspecialchars($p['category']); ?></td>
                <td><?= htmlspecialchars($p['sub_category']); ?></td>
                <td>Rp <?= number_format($p['harga'], 0, ',', '.'); ?></td>
                <td><strong><?= number_format($p['stok'], 0, ',', '.'); ?></strong></td>
                <td><?= number_format($p['stok_minimum'], 0, ',', '.'); ?></td>
                <td>
                    <?php if ($p['stok'] <= $p['stok_minimum']) : ?>
                        <span class="badge badge-warning">Rendah</span>
                    <?php elseif ($p['stok'] <= $p['stok_minimum'] * 2) : ?>
                        <span class="badge badge-caution">Sedang</span>
                    <?php else : ?>
                        <span class="badge badge-ok">Aman</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Navigasi Halaman (Pagination) -->
<?php if ($data['total_pages'] > 1) : ?>
<div class="pagination">
    <?php if ($data['current_page'] > 1) : ?>
        <a href="<?= BASEURL; ?>/Produk?page=<?= $data['current_page'] - 1; ?>&category=<?= urlencode($data['current_category'] ?? ''); ?>&search=<?= urlencode($data['current_search'] ?? ''); ?>" class="btn btn-sm">&laquo; Sebelumnya</a>
    <?php endif; ?>

    <span class="pagination-info">Halaman <?= $data['current_page']; ?> dari <?= $data['total_pages']; ?></span>

    <?php if ($data['current_page'] < $data['total_pages']) : ?>
        <a href="<?= BASEURL; ?>/Produk?page=<?= $data['current_page'] + 1; ?>&category=<?= urlencode($data['current_category'] ?? ''); ?>&search=<?= urlencode($data['current_search'] ?? ''); ?>" class="btn btn-sm">Selanjutnya &raquo;</a>
    <?php endif; ?>
</div>
<?php endif; ?>
