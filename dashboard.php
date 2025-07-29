<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("location:header.php");
    exit;
}

// Mapping role ID ke nama role
$role_id = $_SESSION['role_id'];
$role_names = [
    1 => 'admin',
    2 => 'kepala_desa',
    3 => 'sekretaris_desa',
    4 => 'kaur_umum',
    5 => 'kaur_keuangan',
    6 => 'kaur_perencanaan',
    7 => 'kasi_pemerintahan',
    8 => 'kasi_kesejahteraan',
    9 => 'kasi_pelayanan',
    10 => 'operator_sistem'
];

$role = $role_names[$role_id] ?? 'unknown';

// Jika role tidak dikenali, kembalikan ke login
if ($role === 'unknown') {
    header("location:login_pegawai.php");
    exit;
}

include 'partials/header.php';

?>

<!-- DUMMY DASHBOARD CONTENT -->
<div class="container mt-4">
    <h2 class="mb-4 text-capitalize">Dashboard <?= str_replace('_', ' ', $role); ?></h2>

    <div class="row">
        <!-- Kotak dummy statistik -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5>Total Data</h5>
                    <p class="display-6">123</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5>Notifikasi Baru</h5>
                    <p class="display-6">5</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5>Permintaan Proses</h5>
                    <p class="display-6">12</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel dummy -->
    <div class="card mt-4">
        <div class="card-header">
            <strong>Log Aktivitas Terbaru</strong>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Aksi</th>
                        <th>Pengguna</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2025-07-28 09:10</td>
                        <td>Login ke sistem</td>
                        <td>admin</td>
                    </tr>
                    <tr>
                        <td>2025-07-28 09:12</td>
                        <td>Menambah data warga</td>
                        <td>kasi_pelayanan</td>
                    </tr>
                    <tr>
                        <td>2025-07-28 09:20</td>
                        <td>Menghapus data anggaran</td>
                        <td>kaur_keuangan</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>
