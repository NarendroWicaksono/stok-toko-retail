<h1>Daftar Supplier</h1>

<!-- Pesan Notifikasi (Flash Message) -->
<?php if (isset($_SESSION['flash'])) : ?>
    <div class="alert alert-<?= $_SESSION['flash']['type']; ?>">
        <?= $_SESSION['flash']['message']; ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<!-- Toolbar -->
<div class="toolbar">
    <form method="GET" action="<?= BASEURL; ?>/Supplier" class="toolbar-form">
        <input type="text" name="search" class="input input-search" placeholder="Cari supplier..."
               value="<?= htmlspecialchars($data['current_search'] ?? ''); ?>">
        <button type="submit" class="btn">Cari</button>
    </form>
    <a href="<?= BASEURL; ?>/Supplier/tambah" class="btn btn-tambah">+ Tambah Supplier</a>
</div>

<!-- Tabel Supplier -->
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Supplier</th>
                <th>Kontak</th>
                <th>Telepon</th>
                <th>Email</th>
                <th>Jumlah Produk</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data['supplier'])) : ?>
            <tr><td colspan="7" class="text-center">Belum ada data supplier.</td></tr>
            <?php else : ?>
            <?php $no = 1; foreach ($data['supplier'] as $s) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td>
                    <a href="<?= BASEURL; ?>/Supplier/detail/<?= $s['id']; ?>">
                        <strong><?= htmlspecialchars($s['nama']); ?></strong>
                    </a>
                </td>
                <td><?= htmlspecialchars($s['kontak'] ?: '-'); ?></td>
                <td><?= htmlspecialchars($s['telepon'] ?: '-'); ?></td>
                <td><?= htmlspecialchars($s['email'] ?: '-'); ?></td>
                <td>
                    <?php if ($s['jumlah_produk'] > 0) : ?>
                        <span class="badge badge-ok"><?= $s['jumlah_produk']; ?> produk</span>
                    <?php else : ?>
                        <span style="color:#999;">0 produk</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="action-group">
                        <a href="<?= BASEURL; ?>/Supplier/edit/<?= $s['id']; ?>" class="btn btn-sm btn-edit">Edit</a>
                        <form method="POST" action="<?= BASEURL; ?>/Supplier/hapus/<?= $s['id']; ?>" class="form-inline-action" onsubmit="return confirm('Yakin ingin menghapus supplier <?= htmlspecialchars(addslashes($s['nama'])); ?>?');">
                            <button type="submit" class="btn btn-sm btn-hapus">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
