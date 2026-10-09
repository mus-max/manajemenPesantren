<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Tambah Santri & Orang Tua';

$kelas = $pdo->query("SELECT id_kelas, nama_kelas FROM kelas ORDER BY nama_kelas")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCSRF();
    $nama = trim($_POST['nama_santri'] ?? '');
    $tempat_lahir = trim($_POST['tempat_lahir'] ?? '');
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
    $alamat = trim($_POST['alamat'] ?? '');
    $jk = $_POST['jenis_kelamin'] ?? '';
    $tanggal_masuk = trim($_POST['tanggal_masuk'] ?? '');
    $ayah_nama = trim($_POST['ayah_nama'] ?? '');
    $ibu_nama = trim($_POST['ibu_nama'] ?? '');

    if (empty($nama) || empty($tanggal_lahir) || empty($alamat) || empty($jk) || empty($tempat_lahir) || empty($tanggal_masuk)) {
        $error = 'Field wajib santri harus diisi.';
    } elseif (empty($ayah_nama) && empty($ibu_nama)) {
        $error = 'Minimal isi nama Ayah atau Ibu.';
    } else {
        $userId = intval($_SESSION['user_id'] ?? 0);
        if ($userId < 1) { setAlert('danger', 'Session expired.'); redirect(BASE_URL . '/auth/logout.php'); }

        $pdo->beginTransaction();
        try {
            // 1. Insert orang tua
            $id_orang_tua = null;
            if (!empty($ayah_nama) || !empty($ibu_nama)) {
                $stmt = $pdo->prepare("INSERT INTO orang_tua (ayah_nama, ayah_warga_negara, ayah_tempat_lahir, ayah_tanggal_lahir, ayah_pendidikan_terakhir, ayah_pekerjaan, ayah_no_hp, ayah_alamat, ibu_nama, ibu_warga_negara, ibu_tempat_lahir, ibu_tanggal_lahir, ibu_pendidikan_terakhir, ibu_pekerjaan, ibu_no_hp, ibu_alamat) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $ayah_nama ?: null, trim($_POST['ayah_warga_negara'] ?? '') ?: null, trim($_POST['ayah_tempat_lahir'] ?? '') ?: null,
                    $_POST['ayah_tanggal_lahir'] ?: null, trim($_POST['ayah_pendidikan_terakhir'] ?? '') ?: null,
                    trim($_POST['ayah_pekerjaan'] ?? '') ?: null, trim($_POST['ayah_no_hp'] ?? '') ?: null, trim($_POST['ayah_alamat'] ?? '') ?: null,
                    $ibu_nama ?: null, trim($_POST['ibu_warga_negara'] ?? '') ?: null, trim($_POST['ibu_tempat_lahir'] ?? '') ?: null,
                    $_POST['ibu_tanggal_lahir'] ?: null, trim($_POST['ibu_pendidikan_terakhir'] ?? '') ?: null,
                    trim($_POST['ibu_pekerjaan'] ?? '') ?: null, trim($_POST['ibu_no_hp'] ?? '') ?: null, trim($_POST['ibu_alamat'] ?? '') ?: null,
                ]);
                $id_orang_tua = $pdo->lastInsertId();
            }

            // 2. Insert santri
            $stmt = $pdo->prepare("INSERT INTO santri (nis, nik, nama_santri, tempat_lahir, tanggal_lahir, alamat, jenis_kelamin, kewarganegaraan, wali_santri, no_hp, email, id_orang_tua, tanggal_masuk, kelas_id, kamar, status, id_user, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                trim($_POST['nis'] ?? '') ?: null, trim($_POST['nik'] ?? '') ?: null, $nama, $tempat_lahir, $tanggal_lahir, $alamat, $jk,
                $_POST['kewarganegaraan'] ?? 'WNI', trim($_POST['wali_santri'] ?? ''), trim($_POST['no_hp'] ?? ''),
                trim($_POST['email'] ?? '') ?: null, $id_orang_tua, $tanggal_masuk,
                $_POST['kelas_id'] ?: null, trim($_POST['kamar'] ?? '') ?: null, isset($_POST['status']) ? 1 : 0, $userId, $userId
            ]);

            $pdo->commit();
            setAlert('success', 'Data santri dan orang tua berhasil ditambahkan.');
            redirect(BASE_URL . '/santri/');
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = 'Gagal menyimpan data: ' . $e->getMessage();
        }
    }
}

$alert = getAlert();
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div><h4 class="mb-0">Tambah Santri & Orang Tua</h4></div>
    <a href="<?= BASE_URL ?>/santri/" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<?php if (!empty($error)): ?><div class="alert alert-danger alert-custom"><?= sanitize($error) ?></div><?php endif; ?>
<?php if ($alert): ?><div class="alert alert-<?= $alert['type'] ?> alert-custom"><?= sanitize($alert['message']) ?></div><?php endif; ?>

<form method="POST">
    <?= csrfField() ?>

    <!-- Data Diri Santri -->
    <div class="form-card mb-3">
        <h6 class="border-bottom pb-2 mb-3" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#6b7280">Data Diri Santri</h6>
        <div class="row g-3">
            <div class="col-md-3"><label class="form-label fw-semibold">NIS</label><input type="text" name="nis" class="form-control" value="<?= sanitize($_POST['nis'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">NIK</label><input type="text" name="nik" class="form-control" maxlength="16" value="<?= sanitize($_POST['nik'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label><input type="text" name="nama_santri" class="form-control" required value="<?= sanitize($_POST['nama_santri'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                <select name="jenis_kelamin" class="form-select" required><option value="">-- Pilih --</option><option value="Laki-laki" <?= ($_POST['jenis_kelamin'] ?? '') === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option><option value="Perempuan" <?= ($_POST['jenis_kelamin'] ?? '') === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option></select>
            </div>
            <div class="col-md-3"><label class="form-label fw-semibold">Tempat Lahir <span class="text-danger">*</span></label><input type="text" name="tempat_lahir" class="form-control" required value="<?= sanitize($_POST['tempat_lahir'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Tanggal Lahir <span class="text-danger">*</span></label><input type="date" name="tanggal_lahir" class="form-control" required value="<?= sanitize($_POST['tanggal_lahir'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Kewarganegaraan</label><select name="kewarganegaraan" class="form-select"><option value="WNI">WNI</option><option value="WNA">WNA</option></select></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Email</label><input type="email" name="email" class="form-control" value="<?= sanitize($_POST['email'] ?? '') ?>"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Alamat <span class="text-danger">*</span></label><textarea name="alamat" class="form-control" rows="2" required><?= sanitize($_POST['alamat'] ?? '') ?></textarea></div>
            <div class="col-md-3"><label class="form-label fw-semibold">No. HP</label><input type="text" name="no_hp" class="form-control" maxlength="15" value="<?= sanitize($_POST['no_hp'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Wali Santri</label><input type="text" name="wali_santri" class="form-control" value="<?= sanitize($_POST['wali_santri'] ?? '') ?>"></div>
        </div>
    </div>

    <!-- Data Pesantren -->
    <div class="form-card mb-3">
        <h6 class="border-bottom pb-2 mb-3" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#6b7280">Data Pesantren</h6>
        <div class="row g-3">
            <div class="col-md-3"><label class="form-label fw-semibold">Tanggal Masuk <span class="text-danger">*</span></label><input type="date" name="tanggal_masuk" class="form-control" required value="<?= date('Y-m-d') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Kelas</label><select name="kelas_id" class="form-select"><option value="">-- Pilih --</option><?php foreach ($kelas as $k): ?><option value="<?= $k['id_kelas'] ?>"><?= sanitize($k['nama_kelas']) ?></option><?php endforeach; ?></select></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Kamar</label><input type="text" name="kamar" class="form-control" value="<?= sanitize($_POST['kamar'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Status</label><div class="form-check form-switch mt-2"><input class="form-check-input" type="checkbox" name="status" value="1" checked><label class="form-check-label">Aktif</label></div></div>
        </div>
    </div>

    <!-- Data Ayah -->
    <div class="form-card mb-3">
        <h6 class="border-bottom pb-2 mb-3" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#6b7280">Data Ayah</h6>
        <div class="row g-3">
            <div class="col-md-4"><label class="form-label fw-semibold">Nama Ayah <small class="text-muted">*</small></label><input type="text" name="ayah_nama" class="form-control" value="<?= sanitize($_POST['ayah_nama'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Warga Negara</label><input type="text" name="ayah_warga_negara" class="form-control" value="<?= sanitize($_POST['ayah_warga_negara'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Tempat Lahir</label><input type="text" name="ayah_tempat_lahir" class="form-control" value="<?= sanitize($_POST['ayah_tempat_lahir'] ?? '') ?>"></div>
            <div class="col-md-2"><label class="form-label fw-semibold">Tgl Lahir</label><input type="date" name="ayah_tanggal_lahir" class="form-control" value="<?= sanitize($_POST['ayah_tanggal_lahir'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Pendidikan</label><input type="text" name="ayah_pendidikan_terakhir" class="form-control" value="<?= sanitize($_POST['ayah_pendidikan_terakhir'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Pekerjaan</label><input type="text" name="ayah_pekerjaan" class="form-control" value="<?= sanitize($_POST['ayah_pekerjaan'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">No. HP</label><input type="text" name="ayah_no_hp" class="form-control" maxlength="15" value="<?= sanitize($_POST['ayah_no_hp'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Alamat</label><textarea name="ayah_alamat" class="form-control" rows="2"><?= sanitize($_POST['ayah_alamat'] ?? '') ?></textarea></div>
        </div>
    </div>

    <!-- Data Ibu -->
    <div class="form-card mb-3">
        <h6 class="border-bottom pb-2 mb-3" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#6b7280">Data Ibu</h6>
        <div class="row g-3">
            <div class="col-md-4"><label class="form-label fw-semibold">Nama Ibu <small class="text-muted">*</small></label><input type="text" name="ibu_nama" class="form-control" value="<?= sanitize($_POST['ibu_nama'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Warga Negara</label><input type="text" name="ibu_warga_negara" class="form-control" value="<?= sanitize($_POST['ibu_warga_negara'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Tempat Lahir</label><input type="text" name="ibu_tempat_lahir" class="form-control" value="<?= sanitize($_POST['ibu_tempat_lahir'] ?? '') ?>"></div>
            <div class="col-md-2"><label class="form-label fw-semibold">Tgl Lahir</label><input type="date" name="ibu_tanggal_lahir" class="form-control" value="<?= sanitize($_POST['ibu_tanggal_lahir'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Pendidikan</label><input type="text" name="ibu_pendidikan_terakhir" class="form-control" value="<?= sanitize($_POST['ibu_pendidikan_terakhir'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Pekerjaan</label><input type="text" name="ibu_pekerjaan" class="form-control" value="<?= sanitize($_POST['ibu_pekerjaan'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">No. HP</label><input type="text" name="ibu_no_hp" class="form-control" maxlength="15" value="<?= sanitize($_POST['ibu_no_hp'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Alamat</label><textarea name="ibu_alamat" class="form-control" rows="2"><?= sanitize($_POST['ibu_alamat'] ?? '') ?></textarea></div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i>Simpan Semua</button>
        <a href="<?= BASE_URL ?>/santri/" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
