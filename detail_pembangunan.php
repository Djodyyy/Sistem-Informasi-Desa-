<?php
require_once 'functions/koneksi.php';
require_once 'functions/function_pembangunan.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$data = getPembangunanById($id);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Detail Pembangunan - Desa Cibening</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <link rel="shortcut icon" href="assets/img/logo/logo_tab.png">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Poppins&display=swap" rel="stylesheet">

  <!-- Bootstrap + Icons -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">

  <style>
    .pembangunan-title {
      margin-top: 20px;
    }

    .pembangunan-img {
      max-height: 400px;
      object-fit: contain;
      width: 100%;
    }

    .pembangunan-content {
      margin-top: 20px;
    }

    .btn-kembali {
      background-color: #6c757d;
      color: #fff;
    }

    .btn-kembali:hover {
      background-color: #5a6268;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
      background-color: rgba(0, 0, 0, 0.5);
      border-radius: 50%;
    }

    .carousel-control-prev,
    .carousel-control-next {
      width: 6%;
    }
  </style>
</head>

<body class="index-page">
  <main class="main">
    <section class="section">
      <div class="container" data-aos="fade-up">

        <?php if ($data): ?>
          <div class="text-center mb-4">
            <a href="index.php"><img src="assets/img/logo/logo_home.png" alt="Logo Desa Cibening" height="60"></a>
            <h2 class="pembangunan-title"><?= htmlspecialchars($data['judul']) ?></h2>
            <p class="text-muted">
              <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($data['lokasi']) ?> &nbsp;|&nbsp;
              <?= htmlspecialchars($data['bulan']) ?>
            </p>
            <p class="text-muted">
              <i class="bi bi-graph-up"></i> Volume: <?= htmlspecialchars($data['volume']) ?> &nbsp;|&nbsp;
              <i class="bi bi-cash-stack"></i> Anggaran: Rp <?= number_format($data['anggaran'], 0, ',', '.') ?> &nbsp;|&nbsp;
              <i class="bi bi-bank"></i> Sumber Dana: <?= htmlspecialchars($data['sumber_dana']) ?>
            </p>
          </div>

          <?php
          $fotoArray = [];
          if (!empty($data['foto'])) {
            $decoded = json_decode($data['foto'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
              $fotoArray = $decoded;
            } else {
              $fotoArray[] = $data['foto'];
            }
          }
          ?>

          <?php if (count($fotoArray) > 1): ?>
            <div id="carouselPembangunan" class="carousel slide mb-4" data-bs-ride="carousel">
              <div class="carousel-inner">
                <?php foreach ($fotoArray as $index => $foto): ?>
                  <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <img src="uploads/pembangunan/<?= htmlspecialchars($foto) ?>" class="d-block w-100 pembangunan-img" alt="Foto <?= $index + 1 ?>">
                  </div>
                <?php endforeach; ?>
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#carouselPembangunan" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Sebelumnya</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#carouselPembangunan" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Selanjutnya</span>
              </button>
            </div>
          <?php elseif (count($fotoArray) === 1): ?>
            <img src="uploads/pembangunan/<?= htmlspecialchars($fotoArray[0]) ?>" class="pembangunan-img mb-4" alt="Foto Pembangunan">
          <?php endif; ?>

          <div class="pembangunan-content">
            <p><?= nl2br(htmlspecialchars($data['keterangan'])) ?></p>
          </div>
        <?php else: ?>
          <div class="text-center text-danger">
            <p>Data pembangunan tidak ditemukan.</p>
          </div>
        <?php endif; ?>

        <!-- Tombol Kembali -->
        <div class="text-center mt-4">
          <a href="pembangunan.php" class="btn btn-kembali"><i class="bi bi-arrow-left-circle"></i> Kembali</a>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="footer dark-background text-center">
      <div class="container">
        <div class="header-with-logo d-flex justify-content-center align-items-center mb-3">
          <img src="assets/img/logo/logo_home.png" alt="Logo Desa Cibening" width="40" height="40">
          <h3 class="sitename ms-3 mb-0">Sistem Informasi Desa Cibening</h3>
        </div>
        <div class="social-links d-flex justify-content-center mb-3">
          <a href="#"><i class="bi bi-twitter-x"></i></a>
          <a href="#"><i class="bi bi-facebook"></i></a>
          <a href="https://www.instagram.com/pemdes_cibening_berprestasi?igsh=MW5qdmJ3Y2Vyc3VpOQ=="><i class="bi bi-instagram"></i></a>
          <a href="#"><i class="bi bi-whatsapp"></i></a>
        </div>
        <div class="copyright">
          <span>&copy;</span> <strong class="px-1">Djody Rizaldi</strong> <span>All Rights Reserved</span>
        </div>
        <div class="credits">
          Designed by <a href="https://www.linkedin.com/in/djody-rizaldi-arifin-101b94299/">Djody Rizaldi</a>
        </div>
      </div>
    </footer>

    <a href="#" class="scroll-top d-flex align-items-center justify-content-center">
      <i class="bi bi-arrow-up-short"></i>
    </a>

    <div id="preloader"></div>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>
