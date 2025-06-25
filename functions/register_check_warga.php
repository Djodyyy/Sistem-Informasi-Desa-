<?php
include 'koneksi.php';
$conn = dbConnect();

$nik            = $_POST['nik'];
$nama           = $_POST['nama'];
$tanggal_lahir  = $_POST['tanggal_lahir'];
$password       = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash dulu

// Cek apakah warga dengan data ini terdaftar
$query = "SELECT * FROM tb_warga WHERE nik = ? AND nama = ? AND tanggal_lahir = ? AND desa = 'Cibening'";
$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $nik, $nama, $tanggal_lahir);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Cek apakah password-nya sudah pernah diisi
    $row = $result->fetch_assoc();
    if (!empty($row['password'])) {
        header("Location: ../register_warga.php?error=Akun sudah terdaftar.");
        exit;
    }

    // Update password di tb_warga
    $update = $conn->prepare("UPDATE tb_warga SET password = ? WHERE nik = ?");
    $update->bind_param("ss", $password, $nik);
    
    if ($update->execute()) {
        header("Location: ../register_warga.php?success=1");
        exit;
    } else {
        echo "Error saat menyimpan password: " . $update->error;
    }
} else {
    header("Location: ../register_warga.php?error=Data tidak ditemukan sebagai warga Desa Cibening.");
    exit;
}
?>
