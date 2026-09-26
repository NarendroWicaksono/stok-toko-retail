<h1>Dasbor Pemantau Stok</h1>

<!-- Ringkasan Statistik -->
<div class="card-grid">
    <div class="card card-stat">
        <span class="card-label">Total Jenis Produk</span>
        <span class="card-value"><?= number_format($data['total_produk'], 0, ',', '.'); ?></span>
    </div>
    <div class="card card-stat">
        <span class="card-label">Total Kategori</span>
        <span class="card-value"><?= number_format($data['total_kategori'], 0, ',', '.'); ?></span>
    </div>
    <div class="card card-stat card-warning">
        <span class="card-label">Perlu Restok (Stok Rendah)</span>
        <span class="card-value"><?= number_format($data['total_stok_rendah'], 0, ',', '.'); ?></span>
    </div>
    <div class="card card-stat">
        <span class="card-label">Total Nilai Stok</span>
        <span class="card-value">Rp <?= number_format($data['nilai_stok'], 0, ',', '.'); ?></span>
    </div>
</div>

<!-- Ringkasan per Kategori -->
<h2>Stok per Kategori</h2>
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Jumlah Produk</th>
                <th>Total Stok (Unit)</th>
                <th>Stok Rendah</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['stok_by_category'] as $cat) : ?>
            <tr>
                <td>
                    <a href="<?= BASEURL; ?>/Produk?category=<?= urlencode($cat['category']); ?>">
                        <?= htmlspecialchars($cat['category']); ?>
                    </a>
                </td>
                <td><?= number_format($cat['total_produk'], 0, ',', '.'); ?></td>
                <td><?= number_format($cat['total_stok'], 0, ',', '.'); ?></td>
                <td>
                    <?php if ($cat['stok_rendah'] > 0) : ?>
                        <span class="badge badge-warning"><?= $cat['stok_rendah']; ?> produk</span>
                    <?php else : ?>
                        <span class="badge badge-ok">Aman (0)</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Daftar Stok Rendah -->
<?php if (!empty($data['stok_rendah'])) : ?>
<h2>Peringatan Stok Rendah (Perlu Restok)</h2>
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Sub-Kategori</th>
                <th>Stok Saat Ini</th>
                <th>Stok Minimum</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $limited = array_slice($data['stok_rendah'], 0, 10);
            foreach ($limited as $p) : ?>
            <tr class="row-warning">
                <td><code><?= htmlspecialchars($p['product_id']); ?></code></td>
                <td><?= htmlspecialchars(mb_substr($p['product_name'], 0, 60)); ?></td>
                <td><?= htmlspecialchars($p['sub_category']); ?></td>
                <td><strong><?= $p['stok']; ?> unit</strong></td>
                <td><?= $p['stok_minimum']; ?> unit</td>
                <td><a href="<?= BASEURL; ?>/Produk/detail/<?= $p['id']; ?>" class="btn btn-sm">Lihat Detail</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php if (count($data['stok_rendah']) > 10) : ?>
    <p><a href="<?= BASEURL; ?>/Produk/stokRendah" class="btn">Lihat Semua Produk Stok Rendah (<?= count($data['stok_rendah']); ?>)</a></p>
<?php endif; ?>
<?php endif; ?>
