<h1>Daftar Kategori</h1>

<!-- Pesan Notifikasi (Flash Message) -->
<?php if (isset($_SESSION['flash'])) : ?>
    <div class="alert alert-<?= $_SESSION['flash']['type']; ?>">
        <?= $_SESSION['flash']['message']; ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<!-- Toolbar -->
<div class="toolbar">
    <form method="GET" action="<?= BASEURL; ?>/Kategori" class="toolbar-form">
        <input type="text" name="search" class="input input-search" placeholder="Cari kategori..."
               value="<?= htmlspecialchars($data['current_search'] ?? ''); ?>">
        <button type="submit" class="btn">Cari</button>
    </form>
    <a href="<?= BASEURL; ?>/Kategori/tambah" class="btn btn-tambah">+ Tambah Kategori</a>
</div>

<!-- Tabel Kategori -->
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                <th>Jumlah Produk</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data['kategori'])) : ?>
            <tr><td colspan="5" class="text-center">Belum ada data kategori.</td></tr>
            <?php else : ?>
            <?php $no = 1; foreach ($data['kategori'] as $k) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><strong><?= htmlspecialchars($k['nama']); ?></strong></td>
                <td><?= htmlspecialchars($k['deskripsi'] ?? '-'); ?></td>
                <td>
                    <?php if ($k['jumlah_produk'] > 0) : ?>
                        <span class="badge badge-ok"><?= $k['jumlah_produk']; ?> produk</span>
                    <?php else : ?>
                        <span style="color:#999;">0 produk</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="action-group">
                        <a href="<?= BASEURL; ?>/Kategori/edit/<?= $k['id']; ?>" class="btn btn-sm btn-edit">Edit</a>
                        <form method="POST" action="<?= BASEURL; ?>/Kategori/hapus/<?= $k['id']; ?>" class="form-inline-action" onsubmit="return confirm('Yakin ingin menghapus kategori <?= htmlspecialchars(addslashes($k['nama'])); ?>?');">
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
