<?php
require_once 'functions/koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo "ID kegiatan tidak valid.";
  exit;
}

$id = (int)$_GET['id'];
$conn = dbConnect();

// Ambil data kegiatan
$stmt = $conn->prepare("SELECT * FROM tb_kegiatan WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$kegiatan = $result->fetch_assoc();

if (!$kegiatan) {
  echo "Kegiatan tidak ditemukan.";
  exit;
}

$judul     = htmlspecialchars($kegiatan['judul']);
$tanggal   = date('d M Y', strtotime($kegiatan['tanggal_kegiatan']));
$deskripsi = nl2br(htmlspecialchars($kegiatan['deskripsi']));
$currentUrl = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// Ambil gambar dokumentasi
$gambarStmt = $conn->prepare("SELECT file_gambar FROM tb_kegiatan_gambar WHERE kegiatan_id = ?");
$gambarStmt->bind_param("i", $id);
$gambarStmt->execute();
$gambarResult = $gambarStmt->get_result();

$gambarList = [];
while ($row = $gambarResult->fetch_assoc()) {
  $gambarList[] = $row['file_gambar'];
}
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
      max-height: 500px;
      object-fit: contain;
      border-radius: 10px;
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
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
      background-color: rgba(0, 0, 0, 0.5);
      border-radius: 50%;
      width: 40px;
      height: 40px;
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

  <?php if (!empty($gambarList)): ?>
    <div id="carouselGaleri" class="carousel slide mb-4" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php foreach ($gambarList as $index => $gambar): ?>
          <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
            <img src="uploads/galeri/<?= htmlspecialchars($gambar) ?>" class="d-block w-100 galeri-img" alt="Foto kegiatan <?= $index + 1 ?>">
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Panah Kiri -->
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselGaleri" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Sebelumnya</span>
      </button>

      <!-- Panah Kanan -->
      <button class="carousel-control-next" type="button" data-bs-target="#carouselGaleri" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Berikutnya</span>
      </button>
    </div>
  <?php else: ?>
    <img src="assets/img/default-galeri.jpg" alt="Galeri Kosong" class="galeri-img mb-4">
  <?php endif; ?>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
