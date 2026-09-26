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
            <dd id="total-nilai-stok">Rp <?= number_format($p['harga'] * $p['stok'], 0, ',', '.'); ?></dd>
        </dl>
    </div>

    <div class="card">
        <h2>Status & Manajemen Stok</h2>

        <!-- Wadah Notifikasi AJAX Dinamis -->
        <div id="ajax-notif-box"></div>

        <div class="stok-display <?= ($p['stok'] <= $p['stok_minimum']) ? 'stok-rendah' : 'stok-aman'; ?>" id="stok-display-box">
            <div class="stok-counter-row">
                <form method="POST" action="<?= BASEURL; ?>/Produk/updateStok/<?= $p['id']; ?>" class="counter-form" onsubmit="return submitStokAjax(event, this)">
                    <input type="hidden" name="aksi" value="kurang">
                    <input type="hidden" name="jumlah_kurang" value="1">
                    <button type="submit" class="btn-counter btn-counter-minus" title="Kurangi 1 unit">&minus; 1</button>
                </form>

                <div>
                    <span class="stok-angka" id="stok-angka"><?= number_format($p['stok'], 0, ',', '.'); ?></span>
                    <span class="stok-label">unit tersedia saat ini</span>
                </div>

                <form method="POST" action="<?= BASEURL; ?>/Produk/updateStok/<?= $p['id']; ?>" class="counter-form" onsubmit="return submitStokAjax(event, this)">
                    <input type="hidden" name="aksi" value="tambah">
                    <input type="hidden" name="jumlah_tambah" value="1">
                    <button type="submit" class="btn-counter btn-counter-plus" title="Tambah 1 unit">&#10010; 1</button>
                </form>
            </div>
        </div>
        
        <p class="stok-min">Batas Stok Minimum: <strong><?= number_format($p['stok_minimum'], 0, ',', '.'); ?></strong> unit</p>

        <div id="stok-warning-box" style="<?= ($p['stok'] <= $p['stok_minimum']) ? '' : 'display:none;'; ?>">
            <div class="alert alert-warning">Perhatian: Stok berada di bawah atau sama dengan batas minimum. Segera lakukan restok!</div>
        </div>

        <!-- Tombol Tambah Cepat Langsung -->
        <div class="box-tambah-stok">
            <h3 style="margin-bottom: 6px; color: #1b5e20;">&#10010; Tambah Stok Masuk (Sekali Klik)</h3>
            <p style="font-size: 13px; color: #444; margin-bottom: 12px;">Pencet salah satu tombol di bawah untuk <strong>langsung menambah</strong> jumlah stok secara instan:</p>
            
            <div class="quick-add-grid">
                <?php foreach ([1, 5, 10, 20, 50, 100] as $qty) : ?>
                <form method="POST" action="<?= BASEURL; ?>/Produk/updateStok/<?= $p['id']; ?>" style="display: inline-block;" onsubmit="return submitStokAjax(event, this)">
                    <input type="hidden" name="aksi" value="tambah">
                    <input type="hidden" name="jumlah_tambah" value="<?= $qty; ?>">
                    <button type="submit" class="btn-quick-action">
                        &#10010; <strong><?= $qty; ?></strong> Unit
                    </button>
                </form>
                <?php endforeach; ?>
            </div>

            <!-- Form Tambah Unit Manual -->
            <form method="POST" action="<?= BASEURL; ?>/Produk/updateStok/<?= $p['id']; ?>" class="form-stok" style="margin-top: 12px; background: #e8f5e9; border-color: #2e7d32;" onsubmit="return submitStokAjax(event, this)">
                <input type="hidden" name="aksi" value="tambah">
                <label for="jumlah_tambah" style="color: #1b5e20; font-weight: 800;">Atau Ketik Jumlah Unit Masuk Sendiri:</label>
                <div class="form-inline">
                    <input type="number" name="jumlah_tambah" id="jumlah_tambah" class="input" value="10" min="1" required style="border-color: #2e7d32; font-weight: bold; font-size: 18px; width: 140px;">
                    <button type="submit" class="btn btn-success" style="font-size: 15px; padding: 10px 20px;">
                        &#10010; Tambah Stok Ini
                    </button>
                </div>
            </form>
        </div>

        <!-- Form Atur Ulang Total Stok Manual -->
        <div style="margin-top: 24px; padding-top: 16px; border-top: 1px dashed #ccc;">
            <details>
                <summary style="cursor: pointer; font-weight: 700; color: #444; font-size: 14px;">
                    Atur Total Stok Manual (Koreksi Fisik / Stok Opname)
                </summary>
                <form method="POST" action="<?= BASEURL; ?>/Produk/updateStok/<?= $p['id']; ?>" class="form-stok" style="margin-top: 10px;" onsubmit="return submitStokAjax(event, this)">
                    <input type="hidden" name="aksi" value="set">
                    <label for="stok">Koreksi Angka Total Stok:</label>
                    <div class="form-inline">
                        <input type="number" name="stok" id="stok" class="input" value="<?= $p['stok']; ?>" min="0" required>
                        <button type="submit" class="btn btn-secondary">Simpan Total Stok</button>
                    </div>
                </form>
            </details>
        </div>
    </div>
</div>

<script>
async function submitStokAjax(event, form) {
    if (window.fetch) {
        event.preventDefault();
        const btn = form.querySelector('button[type="submit"]');
        const originalText = btn ? btn.innerHTML : '';
        if (btn) {
            btn.disabled = true;
            btn.style.opacity = '0.7';
        }

        const formData = new FormData(form);
        formData.append('is_ajax', '1');

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    const angkaEl = document.getElementById('stok-angka');
                    if (angkaEl) {
                        angkaEl.textContent = data.stok_formatted;
                        angkaEl.style.transform = 'scale(1.15)';
                        angkaEl.style.transition = 'transform 0.15s ease';
                        setTimeout(() => { angkaEl.style.transform = 'scale(1)'; }, 200);
                    }

                    const stokInput = document.getElementById('stok');
                    if (stokInput) stokInput.value = data.stok;

                    const totalNilaiEl = document.getElementById('total-nilai-stok');
                    if (totalNilaiEl && data.total_nilai) {
                        totalNilaiEl.textContent = data.total_nilai;
                    }

                    const box = document.getElementById('stok-display-box');
                    const warningBox = document.getElementById('stok-warning-box');
                    if (box) {
                        if (data.is_low) {
                            box.className = 'stok-display stok-rendah';
                            if (warningBox) warningBox.style.display = 'block';
                        } else {
                            box.className = 'stok-display stok-aman';
                            if (warningBox) warningBox.style.display = 'none';
                        }
                    }

                    const notif = document.getElementById('ajax-notif-box');
                    if (notif) {
                        notif.innerHTML = '<div class="alert alert-success" style="margin-bottom: 14px;">' + data.message + '</div>';
                    }
                }
            } else {
                form.submit();
            }
        } catch (e) {
            form.submit();
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.innerHTML = originalText;
            }
        }
        return false;
    }
    return true;
}
</script>
