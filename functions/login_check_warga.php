<?php
session_start();
include 'koneksi.php';

$conn = dbConnect(); // Tambahkan koneksi aktif

$nik = $_POST['nik'];
$password = $_POST['password'];

// Ambil data warga berdasarkan NIK
$query = "SELECT * FROM tb_warga WHERE nik = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $nik);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();

    // Cek apakah sudah punya password
    if (!empty($data['password']) && password_verify($password, $data['password'])) {
        // Login berhasil → simpan ke session
        $_SESSION['nik']  = $data['nik'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['role'] = 'warga'; // bisa digunakan untuk validasi role

           header("Location: ../partials/dashboards/warga.php");
        exit;
    } else {
        // Password salah
        header("Location: ../login_warga.php?error=Password salah.");
        exit;
    }
} else {
    // NIK tidak ditemukan
    header("Location: ../login_warga.php?error=NIK tidak terdaftar.");
    exit;
}
?>
