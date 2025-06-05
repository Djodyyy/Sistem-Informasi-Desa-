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
<html lang="en">

<head>
    <meta charset="utf-8">
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
    <meta content="Coderthemes" name="author">
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/logo/logo.png">

    <!-- third party css -->
    <link href="assets/css/vendor/fullcalendar.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/vendor/dataTables.bootstrap5.css" rel="stylesheet" type="text/css">
    <link href="assets/css/vendor/responsive.bootstrap5.css" rel="stylesheet" type="text/css">
    <link href="assets/css/vendor/buttons.bootstrap5.css" rel="stylesheet" type="text/css">
    <link href="assets/css/vendor/select.bootstrap5.css" rel="stylesheet" type="text/css">
    <link href="assets/css/vendor/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css">
    <!-- third party css end -->

    <!-- App css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/app.css" rel="stylesheet" type="text/css" id="light-style">
    <link href="assets/css/app-dark.min.css" rel="stylesheet" type="text/css" id="dark-style">

</head>

<body class="loading" data-layout="topnav" data-layout-config='{"layoutBoxed":false,"darkMode":false,"showRightSidebarOnStart": true}'>
    <!-- Begin page -->
    <div class="wrapper">

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">
                <!-- Topbar Start -->
                <div class="navbar-custom topnav-navbar">
                    <div class="container-fluid">

                        <!-- LOGO -->
                        <a href="" class="topnav-logo">
                            <span class="topnav-logo-lg">
                                <img src="assets/images/logo-light.png" alt="" height="16">
                            </span>
                            <span class="topnav-logo-sm">
                                <img src="assets/images/logo_sm_dark.png" alt="" height="16">
                            </span>
                        </a>

                        <ul class="list-unstyled topbar-menu float-end mb-0">
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
                </div>
                <!-- end Topbar -->