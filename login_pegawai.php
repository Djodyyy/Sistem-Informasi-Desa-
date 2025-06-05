<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>LOGIN - PEGAWAI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Sistem Login Pegawai Pemerintah Desa Cibening" name="description" />
    <meta content="DEENTTECH" name="author" />
    <link rel="shortcut icon" href="assets/img/logo/logo_tab.png">

    <!-- App css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/app.css" rel="stylesheet" type="text/css" id="light-style">
    <link rel="stylesheet" href="assets/css/toast.css">

    <!-- jQuery CDN (jika belum include di vendor.min.js) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            background-color: #e6f4ef !important;
        }
        .bg-primary-green {
            background-color: #009961 !important;
        }
        .text-primary-green {
            color: #009961 !important;
        }
        .btn-primary-green {
            background-color: #009961;
            border-color: #009961;
            color: #fff;
        }
        .btn-primary-green:hover {
            background-color: #007d4f;
            border-color: #007d4f;
        }
        .form-control:focus {
            border-color: #009961;
            box-shadow: 0 0 0 0.2rem rgba(0, 153, 97, 0.25);
        }
        .footer {
            background-color: #009961;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
    </style>
</head>

<body class="loading authentication-bg">
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">
                        <div class="card-header pt-2 pb-2 text-center bg-primary-green">
                            <a href="index.php">
                                <img src="assets/img/logo/logo_login.png" alt="" height="150">
                            </a>
                        </div>
                        <div class="card-body p-4">
                            <div class="text-center w-75 m-auto">
                                <h4 class="text-primary-black pb-0 fw-bold">LOGIN</h4>
                            </div>
                            <form action="functions/login_check_pegawai.php" method="POST" onsubmit="return validateForm();">
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input class="form-control" name="username" type="text" placeholder="Enter Username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                                        <div class="input-group-text" data-password="false">
                                            <span class="password-eye"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 text-center">
                                    <button class="btn btn-sm btn-primary-green">LOGIN</button>
                                </div>
                                <div class="mb-3 text-center">
                                    <a href="forgot_password.php" class="btn btn-link text-primary-green">Lupa Password?</a>
                                </div>
                                <div class="mb-3 text-center">
                                    <a href="index.php" class="btn btn-link text-muted">← Kembali Ke Halaman Utama</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer footer-alt">
        Copyright © 2025 Sistem Informasi Desa Cibening
    </footer>

    <!-- JS -->
    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.min.js"></script>
    <script src="assets/js/vendor/toast.js"></script>

    <script>
        function validateForm() {
            const username = document.querySelector('[name="username"]').value.trim();
            const password = document.querySelector('[name="password"]').value.trim();
            if (username === "" || password === "") {
                $.toast({
                    heading: 'Error',
                    text: 'Username dan Password wajib diisi!',
                    position: 'top-right',
                    icon: 'error',
                    hideAfter: 3000
                });
                return false;
            }
            return true;
        }
    </script>

    <?php
    if (isset($_SESSION['message'])) {
        $message = json_encode($_SESSION['message']);
        echo "
            <script>
                $(document).ready(function () {
                    $.toast({
                        heading: 'Login Gagal!',
                        text: $message,
                        position: 'top-right',
                        hideAfter: 3500,
                        textAlign: 'center',
                        icon: 'error'
                    });
                });
            </script>
        ";
        unset($_SESSION['message']);
    }
    ?>
</body>
</html>
