<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("location:header.php");
    exit;
}

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
if ($role === 'unknown') {
    header("location:login_pegawai.php");
    exit;
}

include 'partials/header.php';

$dashboard_file = "partials/dashboards/{$role}.php";
if (file_exists($dashboard_file)) {
    include $dashboard_file;
} else {
    echo "<div class='container mt-4'><h4>Dashboard belum tersedia untuk role ini.</h4></div>";
}

include 'partials/footer.php';
?>
