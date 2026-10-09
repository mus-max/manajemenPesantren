<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Edit Santri & Orang Tua';

$id = $_GET['id'] ?? null;
if (!$id) redirect(BASE_URL . '/santri/');
$stmt = $pdo->prepare("SELECT * FROM santri WHERE id_santri = ?");
$stmt->execute([$id]);
$s = $stmt->fetch();
if (!$s) { setAlert('danger', 'Santri tidak ditemukan.'); redirect(BASE_URL . '/santri/'); }

$kelas = $pdo->query("SELECT id_kelas, nama_kelas FROM kelas ORDER BY nama_kelas")->fetchAll();

$ortu = null;
if (!empty($s['id_orang_tua'])) {
    $stmt = $pdo->prepare("SELECT * FROM orang_tua WHERE id_orang_tua = ?");
    $stmt->execute([$s['id_orang_tua']]);
    $ortu = $stmt->fetch();
}

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
        $error = 'Field wajib harus diisi.';
    } elseif (empty($ayah_nama) && empty($ibu_nama)) {
        $error = 'Minimal isi nama Ayah atau Ibu.';
    } else {
        $email = trim($_POST['email'] ?? '') ?: null;
        $pdo->beginTransaction();
        try {
            $existing_ortu = intval($_POST['existing_id_orang_tua'] ?? 0);
            $id_orang_tua = $existing_ortu > 0 ? $existing_ortu : null;

            if (!empty($ayah_nama) || !empty($ibu_nama)) {
                if ($id_orang_tua) {
                    $stmt = $pdo->prepare("UPDATE orang_tua SET ayah_nama=?, ayah_warga_negara=?, ayah_tempat_lahir=?, ayah_tanggal_lahir=?, ayah_pendidikan_terakhir=?, ayah_pekerjaan=?, ayah_no_hp=?, ayah_alamat=?, ibu_nama=?, ibu_warga_negara=?, ibu_tempat_lahir=?, ibu_tanggal_lahir=?, ibu_pendidikan_terakhir=?, ibu_pekerjaan=?, ibu_no_hp=?, ibu_alamat=? WHERE id_orang_tua=?");
                    $stmt->execute([
                        $ayah_nama, trim($_POST['ayah_warga_negara'] ?? '') ?: null, trim($_POST['ayah_tempat_lahir'] ?? '') ?: null,
                        $_POST['ayah_tanggal_lahir'] ?: null, trim($_POST['ayah_pendidikan_terakhir'] ?? '') ?: null,
                        trim($_POST['ayah_pekerjaan'] ?? '') ?: null, trim($_POST['ayah_no_hp'] ?? '') ?: null, trim($_POST['ayah_alamat'] ?? '') ?: null,
                        $ibu_nama, trim($_POST['ibu_warga_negara'] ?? '') ?: null, trim($_POST['ibu_tempat_lahir'] ?? '') ?: null,
                        $_POST['ibu_tanggal_lahir'] ?: null, trim($_POST['ibu_pendidikan_terakhir'] ?? '') ?: null,
                        trim($_POST['ibu_pekerjaan'] ?? '') ?: null, trim($_POST['ibu_no_hp'] ?? '') ?: null, trim($_POST['ibu_alamat'] ?? '') ?: null,
                        $id_orang_tua
                    ]);
                } else {
                    $stmt = $pdo->prepare("INSERT INTO orang_tua (ayah_nama, ayah_warga_negara, ayah_tempat_lahir, ayah_tanggal_lahir, ayah_pendidikan_terakhir, ayah_pekerjaan, ayah_no_hp, ayah_alamat, ibu_nama, ibu_warga_negara, ibu_tempat_lahir, ibu_tanggal_lahir, ibu_pendidikan_terakhir, ibu_pekerjaan, ibu_no_hp, ibu_alamat) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([
                        $ayah_nama, trim($_POST['ayah_warga_negara'] ?? '') ?: null, trim($_POST['ayah_tempat_lahir'] ?? '') ?: null,
                        $_POST['ayah_tanggal_lahir'] ?: null, trim($_POST['ayah_pendidikan_terakhir'] ?? '') ?: null,
                        trim($_POST['ayah_pekerjaan'] ?? '') ?: null, trim($_POST['ayah_no_hp'] ?? '') ?: null, trim($_POST['ayah_alamat'] ?? '') ?: null,
                        $ibu_nama, trim($_POST['ibu_warga_negara'] ?? '') ?: null, trim($_POST['ibu_tempat_lahir'] ?? '') ?: null,
                        $_POST['ibu_tanggal_lahir'] ?: null, trim($_POST['ibu_pendidikan_terakhir'] ?? '') ?: null,
                        trim($_POST['ibu_pekerjaan'] ?? '') ?: null, trim($_POST['ibu_no_hp'] ?? '') ?: null, trim($_POST['ibu_alamat'] ?? '') ?: null,
                    ]);
                    $id_orang_tua = $pdo->lastInsertId();
                }
            }

            $stmt = $pdo->prepare("UPDATE santri SET nis=?, nik=?, nama_santri=?, tempat_lahir=?, tanggal_lahir=?, alamat=?, jenis_kelamin=?, kewarganegaraan=?, wali_santri=?, no_hp=?, email=?, id_orang_tua=?, tanggal_masuk=?, kelas_id=?, kamar=?, status=? WHERE id_santri=?");
            $stmt->execute([
                trim($_POST['nis'] ?? ''), trim($_POST['nik'] ?? ''), $nama, $tempat_lahir, $tanggal_lahir, $alamat, $jk,
                $_POST['kewarganegaraan'] ?? 'WNI', trim($_POST['wali_santri'] ?? ''), trim($_POST['no_hp'] ?? ''),
                $email, $id_orang_tua, $tanggal_masuk,
                $_POST['kelas_id'] ?: null, trim($_POST['kamar'] ?? ''), isset($_POST['status']) ? 1 : 0, $id
            ]);

            $pdo->commit();
            setAlert('success', 'Data santri dan orang tua berhasil diperbarui.');
            redirect(BASE_URL . '/santri/');
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = 'Gagal memperbarui data: ' . $e->getMessage();
        }
    }
}

$p = $s;
$alert = getAlert();
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div><h4 class="mb-0">Edit: <?= sanitize($s['nama_santri']) ?></h4></div>
    <a href="<?= BASE_URL ?>/santri/" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>

<?php if (!empty($error)): ?><div class="alert alert-danger alert-custom"><?= sanitize($error) ?></div><?php endif; ?>
<?php if ($alert): ?><div class="alert alert-<?= $alert['type'] ?> alert-custom"><?= sanitize($alert['message']) ?></div><?php endif; ?>

<form method="POST">
    <?= csrfField() ?>
    <input type="hidden" name="existing_id_orang_tua" value="<?= intval($ortu['id_orang_tua'] ?? 0) ?>">

    <div class="form-card mb-3">
        <h6 class="border-bottom pb-2 mb-3" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#6b7280">Data Diri Santri</h6>
        <div class="row g-3">
            <div class="col-md-3"><label class="form-label fw-semibold">NIS</label><input type="text" name="nis" class="form-control" value="<?= sanitize($p['nis'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">NIK</label><input type="text" name="nik" class="form-control" maxlength="16" value="<?= sanitize($p['nik'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label><input type="text" name="nama_santri" class="form-control" required value="<?= sanitize($p['nama_santri']) ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                <select name="jenis_kelamin" class="form-select" required><option value="Laki-laki" <?= $p['jenis_kelamin'] === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option><option value="Perempuan" <?= $p['jenis_kelamin'] === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option></select>
            </div>
            <div class="col-md-3"><label class="form-label fw-semibold">Tempat Lahir <span class="text-danger">*</span></label><input type="text" name="tempat_lahir" class="form-control" required value="<?= sanitize($p['tempat_lahir']) ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Tanggal Lahir <span class="text-danger">*</span></label><input type="date" name="tanggal_lahir" class="form-control" required value="<?= sanitize($p['tanggal_lahir']) ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Kewarganegaraan</label><select name="kewarganegaraan" class="form-select"><option value="WNI" <?= ($p['kewarganegaraan'] ?? '') === 'WNI' ? 'selected' : '' ?>>WNI</option><option value="WNA" <?= ($p['kewarganegaraan'] ?? '') === 'WNA' ? 'selected' : '' ?>>WNA</option></select></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Email</label><input type="email" name="email" class="form-control" value="<?= sanitize($p['email'] ?? '') ?>"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Alamat <span class="text-danger">*</span></label><textarea name="alamat" class="form-control" rows="2" required><?= sanitize($p['alamat']) ?></textarea></div>
            <div class="col-md-3"><label class="form-label fw-semibold">No. HP</label><input type="text" name="no_hp" class="form-control" maxlength="15" value="<?= sanitize($p['no_hp']) ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Wali Santri</label><input type="text" name="wali_santri" class="form-control" value="<?= sanitize($p['wali_santri']) ?>"></div>
        </div>
    </div>

    <div class="form-card mb-3">
        <h6 class="border-bottom pb-2 mb-3" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#6b7280">Data Pesantren</h6>
        <div class="row g-3">
            <div class="col-md-3"><label class="form-label fw-semibold">Tanggal Masuk <span class="text-danger">*</span></label><input type="date" name="tanggal_masuk" class="form-control" required value="<?= sanitize($p['tanggal_masuk']) ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Kelas</label><select name="kelas_id" class="form-select"><option value="">-- Pilih --</option><?php foreach ($kelas as $k): ?><option value="<?= $k['id_kelas'] ?>" <?= $p['kelas_id'] === $k['id_kelas'] ? 'selected' : '' ?>><?= sanitize($k['nama_kelas']) ?></option><?php endforeach; ?></select></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Kamar</label><input type="text" name="kamar" class="form-control" value="<?= sanitize($p['kamar'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Status</label><div class="form-check form-switch mt-2"><input class="form-check-input" type="checkbox" name="status" value="1" <?= $p['status'] == 1 ? 'checked' : '' ?>><label class="form-check-label">Aktif</label></div></div>
        </div>
    </div>

    <div class="form-card mb-3">
        <h6 class="border-bottom pb-2 mb-3" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#6b7280">Data Ayah</h6>
        <div class="row g-3">
            <div class="col-md-4"><label class="form-label fw-semibold">Nama Ayah <small class="text-muted">*</small></label><input type="text" name="ayah_nama" class="form-control" value="<?= sanitize($ortu['ayah_nama'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Warga Negara</label><input type="text" name="ayah_warga_negara" class="form-control" value="<?= sanitize($ortu['ayah_warga_negara'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Tempat Lahir</label><input type="text" name="ayah_tempat_lahir" class="form-control" value="<?= sanitize($ortu['ayah_tempat_lahir'] ?? '') ?>"></div>
            <div class="col-md-2"><label class="form-label fw-semibold">Tgl Lahir</label><input type="date" name="ayah_tanggal_lahir" class="form-control" value="<?= sanitize($ortu['ayah_tanggal_lahir'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Pendidikan</label><input type="text" name="ayah_pendidikan_terakhir" class="form-control" value="<?= sanitize($ortu['ayah_pendidikan_terakhir'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Pekerjaan</label><input type="text" name="ayah_pekerjaan" class="form-control" value="<?= sanitize($ortu['ayah_pekerjaan'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">No. HP</label><input type="text" name="ayah_no_hp" class="form-control" maxlength="15" value="<?= sanitize($ortu['ayah_no_hp'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Alamat</label><textarea name="ayah_alamat" class="form-control" rows="2"><?= sanitize($ortu['ayah_alamat'] ?? '') ?></textarea></div>
        </div>
    </div>

    <div class="form-card mb-3">
        <h6 class="border-bottom pb-2 mb-3" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#6b7280">Data Ibu</h6>
        <div class="row g-3">
            <div class="col-md-4"><label class="form-label fw-semibold">Nama Ibu <small class="text-muted">*</small></label><input type="text" name="ibu_nama" class="form-control" value="<?= sanitize($ortu['ibu_nama'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Warga Negara</label><input type="text" name="ibu_warga_negara" class="form-control" value="<?= sanitize($ortu['ibu_warga_negara'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Tempat Lahir</label><input type="text" name="ibu_tempat_lahir" class="form-control" value="<?= sanitize($ortu['ibu_tempat_lahir'] ?? '') ?>"></div>
            <div class="col-md-2"><label class="form-label fw-semibold">Tgl Lahir</label><input type="date" name="ibu_tanggal_lahir" class="form-control" value="<?= sanitize($ortu['ibu_tanggal_lahir'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Pendidikan</label><input type="text" name="ibu_pendidikan_terakhir" class="form-control" value="<?= sanitize($ortu['ibu_pendidikan_terakhir'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Pekerjaan</label><input type="text" name="ibu_pekerjaan" class="form-control" value="<?= sanitize($ortu['ibu_pekerjaan'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">No. HP</label><input type="text" name="ibu_no_hp" class="form-control" maxlength="15" value="<?= sanitize($ortu['ibu_no_hp'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Alamat</label><textarea name="ibu_alamat" class="form-control" rows="2"><?= sanitize($ortu['ibu_alamat'] ?? '') ?></textarea></div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-1"></i>Update Semua</button>
        <a href="<?= BASE_URL ?>/santri/" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
