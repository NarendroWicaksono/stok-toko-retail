<h1>Daftar Produk Stok Rendah</h1>
<p class="subtitle"><?= number_format(count($data['produk']), 0, ',', '.'); ?> produk memerlukan pemesanan ulang (restok)</p>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Sub-Kategori</th>
                <th>Stok Saat Ini</th>
                <th>Batas Minimum</th>
                <th>Kekurangan Stok</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data['produk'])) : ?>
            <tr><td colspan="8" class="text-center">Semua stok produk aman dan mencukupi.</td></tr>
            <?php else : ?>
            <?php foreach ($data['produk'] as $p) : ?>
            <tr class="row-warning">
                <td><code><?= htmlspecialchars($p['product_id']); ?></code></td>
                <td>
                    <a href="<?= BASEURL; ?>/Produk/detail/<?= $p['id']; ?>">
                        <?= htmlspecialchars(mb_substr($p['product_name'], 0, 55)); ?>
                    </a>
                </td>
                <td><?= htmlspecialchars($p['category']); ?></td>
                <td><?= htmlspecialchars($p['sub_category']); ?></td>
                <td><strong><?= number_format($p['stok'], 0, ',', '.'); ?> unit</strong></td>
                <td><?= number_format($p['stok_minimum'], 0, ',', '.'); ?> unit</td>
                <td>
                    <span class="badge badge-warning">
                        -<?= number_format($p['stok_minimum'] - $p['stok'], 0, ',', '.'); ?> unit
                    </span>
                </td>
                <td><a href="<?= BASEURL; ?>/Produk/detail/<?= $p['id']; ?>" class="btn btn-sm">Restok</a></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
