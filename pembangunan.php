<?php
require_once 'functions/koneksi.php';
require_once 'functions/function_pembangunan.php';

$dataPembangunan = getAllPembangunan();
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Informasi Pembangunan - Desa Cibening</title>
  <link rel="shortcut icon" href="assets/img/logo/logo_tab.png" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Poppins&display=swap" rel="stylesheet" />

  <!-- Bootstrap + Icons -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />
  <link href="assets/css/main.css" rel="stylesheet" />

  <style>
    section.section {
      padding: 40px 0;
      scroll-margin-top: 100px;
    }

    .section-title img {
      height: 60px;
    }

    .judul-wrapper h2 {
      margin-top: 10px;
      margin-bottom: 30px;
      display: inline-block;
      position: relative;
      padding-bottom: 10px;
    }

    .judul-wrapper h2::after {
      content: "";
      display: block;
      width: 60px;
      height: 3px;
      background: #28a745;
      margin: 8px auto 0;
      border-radius: 2px;
    }

    .card-title {
      font-size: 1.1rem;
      font-weight: bold;
    }

    .card-text {
      font-size: 0.9rem;
      color: #555;
    }

    .btn-baca {
      background-color: #28a745;
      color: white;
      font-size: 0.85rem;
    }

    .btn-baca:hover {
      background-color: #218838;
    }
  </style>
</head>

<body class="index-page">
  <main class="main">
    <!-- Informasi Pembangunan Section -->
    <section id="informasi_pembangunan" class="section">
      <div class="container" data-aos="fade-up">
        <div class="section-title text-center">
          <a href="index.php"><img src="assets/img/logo/logo_home.png" alt="Logo Desa Cibening" /></a>
          <div class="judul-wrapper">
            <h2>Informasi Pembangunan Desa</h2>
          </div>
        </div>

        <div class="row">
          <?php if (!empty($dataPembangunan)): ?>
            <?php foreach ($dataPembangunan as $row): ?>
              <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                  <?php if (!empty($row['foto']) && file_exists('uploads/pembangunan/' . $row['foto'])): ?>
                    <img src="uploads/pembangunan/<?= htmlspecialchars($row['foto']) ?>" class="card-img-top" style="height: 180px; object-fit: cover;" alt="Foto Pembangunan" />
                  <?php else: ?>
                    <img src="assets/img/no-image.png" class="card-img-top" style="height: 180px; object-fit: cover;" alt="No Image" />
                  <?php endif; ?>
                  <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($row['judul']) ?></h5>
                    <p class="text-muted mb-1"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($row['lokasi']) ?></p>
                    <p class="text-muted mb-1"><i class="bi bi-calendar-event"></i> Tahun <?= htmlspecialchars($row['tahun']) ?></p>
                    <p class="card-text"><?= substr(strip_tags($row['keterangan']), 0, 100) ?>...</p>
                    <a href="detail_pembangunan.php?id=<?= htmlspecialchars($row['id']) ?>" class="btn btn-sm btn-baca mt-2">Baca Selengkapnya</a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12 text-center text-danger">
              <p>Belum ada data pembangunan.</p>
            </div>
          <?php endif; ?>
        </div>

        <!-- Tombol Kembali -->
        <div class="text-center mt-4">
          <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> Kembali</a>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="footer dark-background text-center">
      <div class="container">
        <div class="header-with-logo d-flex justify-content-center align-items-center mb-3">
          <img src="assets/img/logo/logo_home.png" alt="Logo Desa Cibening" width="40" height="40" />
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
          Designed by <a href="#">Djody Rizaldi</a>
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
  </main>
</body>

</html>
