<?php $s = $data['supplier']; ?>

<a href="<?= BASEURL; ?>/Supplier" class="btn btn-back">&larr; Kembali ke Daftar Supplier</a>

<h1><?= htmlspecialchars($s['nama']); ?></h1>

<!-- Pesan Notifikasi (Flash Message) -->
<?php if (isset($_SESSION['flash'])) : ?>
    <div class="alert alert-<?= $_SESSION['flash']['type']; ?>">
        <?= $_SESSION['flash']['message']; ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<div class="detail-grid">
    <div class="card">
        <h2>Informasi Supplier</h2>
        <dl class="detail-list">
            <dt>Nama Supplier</dt>
            <dd><strong><?= htmlspecialchars($s['nama']); ?></strong></dd>

            <dt>Kontak Person</dt>
            <dd><?= htmlspecialchars($s['kontak'] ?: '-'); ?></dd>

            <dt>Telepon</dt>
            <dd><?= htmlspecialchars($s['telepon'] ?: '-'); ?></dd>

            <dt>Email</dt>
            <dd>
                <?php if (!empty($s['email'])) : ?>
                    <a href="mailto:<?= htmlspecialchars($s['email']); ?>"><?= htmlspecialchars($s['email']); ?></a>
                <?php else : ?>
                    -
                <?php endif; ?>
            </dd>

            <dt>Alamat</dt>
            <dd><?= nl2br(htmlspecialchars($s['alamat'] ?: '-')); ?></dd>

            <dt>Terdaftar Sejak</dt>
            <dd><?= date('d M Y, H:i', strtotime($s['created_at'])); ?></dd>
        </dl>
    </div>

    <div class="card">
        <h2>Aksi</h2>
        <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:12px;">
            <a href="<?= BASEURL; ?>/Supplier/edit/<?= $s['id']; ?>" class="btn btn-edit">Edit Supplier</a>
            <form method="POST" action="<?= BASEURL; ?>/Supplier/hapus/<?= $s['id']; ?>" onsubmit="return confirm('Yakin ingin menghapus supplier ini?');">
                <button type="submit" class="btn btn-hapus">Hapus Supplier</button>
            </form>
        </div>
    </div>
</div>
