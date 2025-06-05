<?php
require_once 'functions/koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo "ID galeri tidak valid.";
  exit;
}

$id = $_GET['id'];
$conn = dbConnect();

$stmt = $conn->prepare("SELECT * FROM tb_galeri_kegiatan WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$galeri = $result->fetch_assoc();

if (!$galeri) {
  echo "Galeri tidak ditemukan.";
  exit;
}

$judul = htmlspecialchars($galeri['judul']);
$tanggal = date('d M Y', strtotime($galeri['tanggal_kegiatan']));

// ✅ Perbaikan PATH sesuai folder upload
$gambar = !empty($galeri['file_gambar']) ? "uploads/galeri/{$galeri['file_gambar']}" : "assets/img/default-galeri.jpg";

$deskripsi = nl2br(htmlspecialchars($galeri['deskripsi']));
$currentUrl = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= $judul ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="assets/img/logo/logo_tab.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .galeri-wrapper {
      max-width: 900px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      margin-top: 50px;
      margin-bottom: 60px;
    }
    .galeri-img {
      width: 100%;
      height: auto;
      border-radius: 10px;
      margin-bottom: 25px;
    }
    .galeri-deskripsi {
      font-size: 1.05rem;
      line-height: 1.8;
    }
    .share-buttons a {
      margin-right: 10px;
    }
    .back-btn {
      display: inline-block;
      margin-top: 30px;
    }
    @media (max-width: 576px) {
      .galeri-wrapper {
        padding: 20px;
      }
      .galeri-deskripsi {
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>

<div class="container galeri-wrapper">
  <h1 class="mb-2"><?= $judul ?></h1>
  <p class="text-muted mb-4">Tanggal Kegiatan: <?= $tanggal ?></p>
  <img src="<?= $gambar ?>" alt="<?= $judul ?>" class="galeri-img">
  <div class="galeri-deskripsi mb-4">
    <?= $deskripsi ?>
  </div>

  <!-- Share Buttons -->
  <div class="share-buttons mb-4">
    <strong>Bagikan:</strong>
    <a href="https://wa.me/?text=<?= urlencode($judul . ' - ' . $currentUrl) ?>" class="btn btn-success btn-sm" target="_blank">WhatsApp</a>
    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($currentUrl) ?>" class="btn btn-primary btn-sm" target="_blank">Facebook</a>
  </div>

  <a href="index.php#galeri-kegiatan" class="btn btn-outline-secondary back-btn">← Kembali ke Galeri</a>
</div>

</body>
</html>
