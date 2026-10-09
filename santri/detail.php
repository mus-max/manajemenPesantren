<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/database.php';
$pageTitle = 'Detail Santri';

$id = $_GET['id'] ?? null;
if (!$id) redirect(BASE_URL . '/santri/');

$stmt = $pdo->prepare("
    SELECT s.*, k.nama_kelas,
           ot.ayah_nama, ot.ayah_warga_negara, ot.ayah_tempat_lahir, ot.ayah_tanggal_lahir,
           ot.ayah_pendidikan_terakhir, ot.ayah_pekerjaan, ot.ayah_no_hp, ot.ayah_alamat,
           ot.ibu_nama, ot.ibu_warga_negara, ot.ibu_tempat_lahir, ot.ibu_tanggal_lahir,
           ot.ibu_pendidikan_terakhir, ot.ibu_pekerjaan, ot.ibu_no_hp, ot.ibu_alamat
    FROM santri s
    LEFT JOIN kelas k ON s.kelas_id = k.id_kelas
    LEFT JOIN orang_tua ot ON s.id_orang_tua = ot.id_orang_tua
    WHERE s.id_santri = ?
");
$stmt->execute([$id]);
$s = $stmt->fetch();
if (!$s) { setAlert('danger', 'Santri tidak ditemukan.'); redirect(BASE_URL . '/santri/'); }

$alert = getAlert();
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div><h4 class="mb-0"><?= sanitize($s['nama_santri']) ?></h4></div>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>/santri/edit.php?id=<?= $s['id_santri'] ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil me-1"></i>Edit</a>
        <a href="<?= BASE_URL ?>/santri/" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>
</div>

<?php if ($alert): ?><div class="alert alert-<?= $alert['type'] ?> alert-custom"><?= sanitize($alert['message']) ?></div><?php endif; ?>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Data Diri</h6></div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th width="140">NIS</th><td><?= sanitize($s['nis'] ?? '-') ?></td></tr>
                    <tr><th>NIK</th><td><?= sanitize($s['nik'] ?? '-') ?></td></tr>
                    <tr><th>Nama</th><td><?= sanitize($s['nama_santri']) ?></td></tr>
                    <tr><th>Jenis Kelamin</th><td><?= sanitize($s['jenis_kelamin']) ?></td></tr>
                    <tr><th>Tempat, Tgl Lahir</th><td><?= sanitize($s['tempat_lahir']) ?>, <?= formatDate($s['tanggal_lahir']) ?></td></tr>
                    <tr><th>Kewarganegaraan</th><td><?= sanitize($s['kewarganegaraan']) ?></td></tr>
                    <tr><th>Alamat</th><td><?= sanitize($s['alamat']) ?></td></tr>
                    <tr><th>No. HP</th><td><?= sanitize($s['no_hp']) ?></td></tr>
                    <tr><th>Email</th><td><?= sanitize($s['email'] ?? '-') ?></td></tr>
                    <tr><th>Wali</th><td><?= sanitize($s['wali_santri']) ?></td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Data Pesantren</h6></div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th width="140">Tanggal Masuk</th><td><?= formatDate($s['tanggal_masuk']) ?></td></tr>
                    <tr><th>Kelas</th><td><?= sanitize($s['nama_kelas'] ?? '-') ?></td></tr>
                    <tr><th>Kamar</th><td><?= sanitize($s['kamar'] ?? '-') ?></td></tr>
                    <tr><th>Status</th><td><?= $s['status'] == 1 ? '<span class="badge bg-success badge-status">Aktif</span>' : '<span class="badge bg-secondary badge-status">Nonaktif</span>' ?></td></tr>
                </table>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header"><h6 class="mb-0">Data Orang Tua</h6></div>
            <div class="card-body">
                <h6 class="mb-2" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#6b7280">Ayah</h6>
                <table class="table table-borderless mb-2">
                    <tr><th width="140">Nama</th><td><?= sanitize($s['ayah_nama'] ?? '-') ?></td></tr>
                    <tr><th>Tempat, Tgl Lahir</th><td><?= sanitize($s['ayah_tempat_lahir'] ?? '-') ?>, <?= formatDate($s['ayah_tanggal_lahir'] ?? '') ?></td></tr>
                    <tr><th>Pendidikan</th><td><?= sanitize($s['ayah_pendidikan_terakhir'] ?? '-') ?></td></tr>
                    <tr><th>Pekerjaan</th><td><?= sanitize($s['ayah_pekerjaan'] ?? '-') ?></td></tr>
                    <tr><th>No. HP</th><td><?= sanitize($s['ayah_no_hp'] ?? '-') ?></td></tr>
                    <tr><th>Alamat</th><td><?= sanitize($s['ayah_alamat'] ?? '-') ?></td></tr>
                </table>
                <hr class="my-2">
                <h6 class="mb-2" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.04em;color:#6b7280">Ibu</h6>
                <table class="table table-borderless mb-0">
                    <tr><th width="140">Nama</th><td><?= sanitize($s['ibu_nama'] ?? '-') ?></td></tr>
                    <tr><th>Tempat, Tgl Lahir</th><td><?= sanitize($s['ibu_tempat_lahir'] ?? '-') ?>, <?= formatDate($s['ibu_tanggal_lahir'] ?? '') ?></td></tr>
                    <tr><th>Pendidikan</th><td><?= sanitize($s['ibu_pendidikan_terakhir'] ?? '-') ?></td></tr>
                    <tr><th>Pekerjaan</th><td><?= sanitize($s['ibu_pekerjaan'] ?? '-') ?></td></tr>
                    <tr><th>No. HP</th><td><?= sanitize($s['ibu_no_hp'] ?? '-') ?></td></tr>
                    <tr><th>Alamat</th><td><?= sanitize($s['ibu_alamat'] ?? '-') ?></td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
