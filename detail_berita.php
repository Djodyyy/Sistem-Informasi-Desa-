<?php
require_once 'functions/koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo "ID berita tidak valid.";
  exit;
}

$id = $_GET['id'];
$conn = dbConnect();

$stmt = $conn->prepare("SELECT * FROM tb_berita_artikel WHERE id_berita = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$berita = $result->fetch_assoc();

if (!$berita) {
  echo "Berita tidak ditemukan.";
  exit;
}

$judul = htmlspecialchars($berita['judul']);
$tanggal = date('d M Y', strtotime($berita['tanggal']));
$gambar = !empty($berita['gambar']) ? "uploads/{$berita['gambar']}" : "assets/img/default-news.jpg";
$isi = nl2br(htmlspecialchars($berita['isi']));

// URL untuk share
$currentUrl = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $nama = trim($_POST['nama']);
  $komentar = trim($_POST['komentar']);

  if (!empty($nama) && !empty($komentar)) {
    $stmt = $conn->prepare("INSERT INTO tb_komentar (id_berita, nama, komentar) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $id, $nama, $komentar);
    $stmt->execute();
    header("Location: detail_berita.php?id=$id");
    exit;
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $nama = trim($_POST['nama']);
  $komentar = trim($_POST['komentar']);

  if (!empty($nama) && !empty($komentar)) {
    $stmt = $conn->prepare("INSERT INTO tb_komentar (id_berita, nama, komentar) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $id, $nama, $komentar);
    $stmt->execute();
    header("Location: detail_berita.php?id=$id");
    exit;
  }
}

// Ambil semua komentar
$stmt = $conn->prepare("SELECT * FROM tb_komentar WHERE id_berita = ? ORDER BY tanggal DESC");
$stmt->bind_param("i", $id);
$stmt->execute();
$komentarResult = $stmt->get_result();
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
    .berita-wrapper {
      max-width: 900px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      margin-top: 50px;
      margin-bottom: 60px;
    }
    .berita-img {
      width: 100%;
      height: auto;
      border-radius: 10px;
      margin-bottom: 25px;
    }
    .berita-isi {
      font-size: 1.05rem;
      line-height: 1.8;
    }
    .share-buttons a {
      margin-right: 10px;
    }
    .related-posts {
      margin-top: 50px;
    }
    .related-posts h5 {
      margin-bottom: 20px;
    }
    .related-item {
      margin-bottom: 15px;
    }
    .back-btn {
      display: inline-block;
      margin-top: 30px;
    }
    @media (max-width: 576px) {
      .berita-wrapper {
        padding: 20px;
      }
      .berita-isi {
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>

<div class="container berita-wrapper">
  <h1 class="mb-2"><?= $judul ?></h1>
  <p class="text-muted mb-4">Dipublikasikan pada: <?= $tanggal ?></p>
  <img src="<?= $gambar ?>" alt="<?= $judul ?>" class="berita-img">
  <div class="berita-isi mb-4">
    <?= $isi ?>
  </div>

  <!-- Share Buttons -->
  <div class="share-buttons mb-4">
    <strong>Bagikan:</strong>
    <a href="https://wa.me/?text=<?= urlencode($judul . ' - ' . $currentUrl) ?>" class="btn btn-success btn-sm" target="_blank">WhatsApp</a>
    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($currentUrl) ?>" class="btn btn-primary btn-sm" target="_blank">Facebook</a>
  </div>

  <!-- Form Komentar -->
  <h5>Tulis Komentar</h5>
  <form method="POST" class="komentar-form mb-4">
    <div class="mb-3">
      <input type="text" name="nama" class="form-control" placeholder="Nama Anda" required>
    </div>
    <div class="mb-3">
      <textarea name="komentar" class="form-control" rows="3" placeholder="Tulis komentar..." required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Kirim Komentar</button>
  </form>

  <!-- Daftar Komentar -->
  <h5>Komentar</h5>
  <?php if ($komentarResult->num_rows > 0): ?>
    <?php while ($row = $komentarResult->fetch_assoc()): ?>
      <div class="border rounded p-3 mb-3">
        <strong><?= htmlspecialchars($row['nama']) ?></strong>
        <span class="text-muted small d-block"><?= date('d M Y H:i', strtotime($row['tanggal'])) ?></span>
        <p class="mb-0"><?= nl2br(htmlspecialchars($row['komentar'])) ?></p>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p class="text-muted">Belum ada komentar.</p>
  <?php endif; ?>

  <a href="index.php" class="btn btn-outline-secondary back-btn">← Kembali ke Beranda</a>
</div>

</body>
</html>
