<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login sebagai warga
if (!isset($_SESSION['nik']) || $_SESSION['role'] !== 'warga') {
    // Kalau belum login, arahkan ke login warga
    header("Location: login_warga.php?error=Silakan login terlebih dahulu.");
    exit;
}
?>
