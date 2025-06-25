<?php
session_start();
require_once 'functions/koneksi.php';
require_once 'functions/auth_warga.php';

if (!isset($_SESSION['nik'])) {
  $_SESSION['error'] = "Silakan login terlebih dahulu.";
  header("Location: login_warga.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $conn = dbConnect(); // Fungsi koneksi

  $nik         = $_SESSION['nik'];
  $jenis_surat = $_POST['jenis_surat'] ?? '';
  $keperluan   = $_POST['keperluan'] ?? '';
  $detail      = [];

  // Validasi input dasar
  if (empty($jenis_surat) || empty($keperluan)) {
    $_SESSION['error'] = "Lengkapi semua field yang wajib diisi.";
    header("Location: pengajuan_surat.php");
    exit;
  }

  // Ambil nama warga berdasarkan NIK
  $stmtUser = $conn->prepare("SELECT nama FROM tb_warga WHERE nik = ?");
  $stmtUser->bind_param("s", $nik);
  $stmtUser->execute();
  $result = $stmtUser->get_result();

  if ($result->num_rows === 0) {
    $_SESSION['error'] = "Data warga tidak ditemukan.";
    header("Location: pengajuan_surat.php");
    exit;
  }

  $row = $result->fetch_assoc();
  $nama = $row['nama'];
  $stmtUser->close();

  // Form dinamis berdasarkan jenis surat
  switch ($jenis_surat) {
    case 'surat_usaha':
      $detail['nama_usaha']   = $_POST['nama_usaha'] ?? '';
      $detail['alamat_usaha'] = $_POST['alamat_usaha'] ?? '';
      break;
    case 'sktm':
      $detail['alasan_sktm'] = $_POST['alasan_sktm'] ?? '';
      break;
    case 'belum_menikah':
      $detail['tujuan_surat'] = $_POST['tujuan_surat'] ?? '';
      break;
    case 'lahir':
      $detail['nama_bayi']     = $_POST['nama_bayi'] ?? '';
      $detail['tanggal_lahir'] = $_POST['tanggal_lahir'] ?? '';
      $detail['tempat_lahir']  = $_POST['tempat_lahir'] ?? '';
      break;
    case 'kematian':
      $detail['nama_almarhum']     = $_POST['nama_almarhum'] ?? '';
      $detail['tanggal_meninggal'] = $_POST['tanggal_meninggal'] ?? '';
      $detail['sebab_meninggal']   = $_POST['sebab_meninggal'] ?? '';
      break;
  }

  $keterangan = $keperluan . ' | Detail: ' . json_encode($detail);
  $status = 'Menunggu'; // Default status awal
  $tanggal = date('Y-m-d');

  $query = "INSERT INTO tb_pengajuan_surat (nik, nama, jenis_surat, keterangan, status, tanggal_pengajuan)
            VALUES (?, ?, ?, ?, ?, ?)";

  $stmt = $conn->prepare($query);
  if (!$stmt) {
    $_SESSION['error'] = "Prepare statement gagal: " . $conn->error;
    header("Location: pengajuan_surat.php");
    exit;
  }

  $stmt->bind_param("ssssss", $nik, $nama, $jenis_surat, $keterangan, $status, $tanggal);

  if ($stmt->execute()) {
    $_SESSION['success'] = "Pengajuan surat berhasil dikirim!";
  } else {
    $_SESSION['error'] = "Gagal menyimpan data: " . $stmt->error;
  }

  $stmt->close();
  $conn->close();
  header("Location: pengajuan_surat.php");
  exit;
} else {
  header("Location: pengajuan_surat.php");
  exit;
}
