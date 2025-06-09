<?php
session_start();
if ($_SESSION['login'] != "yes") {
    header("location:login.php");
    exit;
}

$role_id = $_SESSION['role_id'];
$username = $_SESSION['username'];

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
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Sistem Informasi Desa Cibening</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/img/logo/logo_tab.png">

    <!-- CSS Vendor -->
    <link href="assets/css/vendor/fullcalendar.min.css" rel="stylesheet">
    <link href="assets/css/vendor/dataTables.bootstrap5.css" rel="stylesheet">
    <link href="assets/css/vendor/responsive.bootstrap5.css" rel="stylesheet">
    <link href="assets/css/vendor/buttons.bootstrap5.css" rel="stylesheet">
    <link href="assets/css/vendor/select.bootstrap5.css" rel="stylesheet">
    <link href="assets/css/vendor/jquery-jvectormap-1.2.2.css" rel="stylesheet">

    <!-- Icons & Style -->
    <link href="assets/css/icons.min.css" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet" id="light-style">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="assets/css/toast.css">
    <link rel="shortcut icon" href="assets/img/logo/logo_tab.png">

    <style>
        /* Ubah warna ungu jadi hijau */
        .leftside-menu,
        .btn-primary,
        .side-nav .side-nav-item>a.active {
            background-color: #28a745 !important;
            /* hijau */
        }

        .btn-primary {
            border-color: #28a745 !important;
        }

        .side-nav .side-nav-item>a {
            color: #fff !important;
        }

        .side-nav .side-nav-item>a:hover {
            background-color: #218838 !important;
        }
    </style>
</head>

<body class="loading">
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="leftside-menu">
            <a href="dashboard.php" class="logo text-center logo-light">
                <span class="logo-lg">
                    <img src="assets/img/logo/logo_login.png" alt="Logo" height="60">
                </span>
            </a>
            <div class="h-100" id="leftside-menu-container" data-simplebar>
                <ul class="side-nav">
                    <li class="side-nav-title side-nav-item text-white">Navigasi</li>
                    <li class="side-nav-item">
                        <a href="dashboard.php" class="side-nav-link">
                            <i class="uil-home-alt"></i>
                            <span> Dashboard </span>
                        </a>
                    </li>

                    <?php if ($role === 'admin'): ?>
                        <li class="side-nav-item">
                            <a href="data_user.php" class="side-nav-link">
                                <i class="uil-user-plus"></i>
                                <span> Tambah User </span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarTambahKonten" aria-expanded="false" aria-controls="sidebarTambahKonten" class="side-nav-link">
                                <i class="uil-file-plus"></i>
                                <span> Tambah Konten </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarTambahKonten">
                                <ul class="side-nav-second-level">
                                    <li><a href="tambah_pembangunan.php?halaman=pembangunan">Kelola Data Informasi Pembangunan</a></li>
                                    <li><a href="tambah_informasi_publik.php?halaman=publik">Kelola Data Informasi Publik</a></li>
                                    <li><a href="tambah_produk_hukum.php?halaman=hukum">Kelola Data Produk Hukum</a></li>
                                    <li><a href="tambah_status_indeks.php?halaman=indeks">Kelola Data Status Indeks</a></li>
                                    <li><a href="tambah_struktur.php?halaman=struktur">Strukur Aparatur</a></li>
                                    <li><a href="tambah_statistik.php?halaman=statistik">Statistik Data Penduduk</a></li>
                                    <li><a href="tambah_anggaran.php?halaman=anggaran">Transparasi Anggaran</a></li>
                                    <li><a href="tambah_artikel_berita.php?halaman=berita">Berita dan Artikel</a></li>
                                    <li><a href="tambah_galeri_kegiatan.php?halaman=galeri">Galeri dan Kegiatan</a></li>
                                </ul>
                            </div>
                        </li>

                    <?php elseif ($role === 'kepala_desa'): ?>
                        <li class="side-nav-item">
                            <a href="laporan.php" class="side-nav-link">
                                <i class="uil-file-info-alt"></i>
                                <span> Laporan </span>
                            </a>
                        </li>
                    <?php elseif ($role === 'kaur_keuangan'): ?>
                        <li class="side-nav-item">
                            <a href="tambah_anggaran.php" class="side-nav-link">
                                <i class="uil uil-file-alt"></i>
                                <span>Informasi Anggaran</span>
                            </a>
                            <a href="tambah_arsip_anggaran.php" class="side-nav-link">
                                <i class="uil uil-archive"></i>
                                <span>Arsip Anggaran</span>
                            </a>
                             <a href="tambah_pembangunan.php" class="side-nav-link">
                                <i class="uil uil-archive"></i>
                                <span>Informasi Pembangunan</span>
                            </a>
                        </li>
                           <?php elseif ($role === 'kasi_pelayanan'): ?>
                        <li class="side-nav-item">
                            <a href="data_permohonan.php" class="side-nav-link">
                                <i class="uil-file-info-alt"></i>
                                <span> Data Permohonan </span>
                            </a>
                             <a href="kelola_surat.php" class="side-nav-link">
                                <i class="uil-file-info-alt"></i>
                                <span> Pengelolaan Surat </span>
                            </a>
                            <a href="data_pengaduan.php" class="side-nav-link">
                                <i class="uil-file-info-alt"></i>
                                <span> Data Pengaduan </span>
                            </a>
                             <a href="jadwal_pelayanan.php" class="side-nav-link">
                                <i class="uil-file-info-alt"></i>
                                <span> Jadwal Pelayanan </span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- Konten -->
        <div class="content-page">
            <div class="content">
                <!-- Navbar -->
                <div class="navbar-custom d-flex justify-content-between align-items-center px-3">
                    <button class="button-menu-mobile open-left">
                        <i class="mdi mdi-menu"></i>
                    </button>
                    <ul class="list-unstyled topbar-menu d-flex mb-0">
                        <li class="dropdown notification-list">
                            <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" href="#">
                                <span class="account-user-avatar">
                                    <img src="assets/img/avatar.png" alt="user-image" class="rounded-circle">
                                </span>
                                <span class="ms-2" class="account-user-avatar">
                                    <span class="account-user-name"><?= htmlspecialchars($username) ?></span><br>
                                    <span class="account-position"><?= ucfirst(str_replace('_', ' ', $role)) ?></span>
                                </span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                                <a href="#" class="dropdown-item notify-item"><i class="mdi mdi-account-circle me-1"></i>Profile</a>
                                <a href="#" class="dropdown-item notify-item"><i class="mdi mdi-account-edit me-1"></i>Pengaturan</a>
                                <a href="functions/logout.php" class="dropdown-item notify-item"><i class="mdi mdi-logout me-1"></i>Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>