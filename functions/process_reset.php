<?php
include('koneksi.php'); // asumsi file ini ada di folder functions

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = dbConnect();

    $code_user = $_POST['code_user'];
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validasi kosong
    if (empty($code_user) || empty($new_password) || empty($confirm_password)) {
        echo "<script>alert('Semua kolom wajib diisi!'); window.history.back();</script>";
        exit;
    }

    // Validasi password minimal
    if (strlen($new_password) < 6) {
        echo "<script>alert('Password minimal 6 karakter!'); window.history.back();</script>";
        exit;
    }

    // Cek konfirmasi password
    if ($new_password !== $confirm_password) {
        echo "<script>alert('Konfirmasi password tidak sama!'); window.history.back();</script>";
        exit;
    }

    // Hash password
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Update password
    $stmt = $conn->prepare("UPDATE tb_user SET password=? WHERE code_user=?");
    $stmt->bind_param("ss", $hashed_password, $code_user);

    if ($stmt->execute()) {
        echo "<script>alert('Password berhasil diubah!'); window.location='../login_pegawai.php';</script>";
    } else {
        echo "<script>alert('Gagal mengubah password!'); window.history.back();</script>";
    }

    $stmt->close();
}
?>
