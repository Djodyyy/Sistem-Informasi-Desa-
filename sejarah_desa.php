<?php
require_once 'functions/koneksi.php';
$conn = dbConnect();

// Ambil konten 'Sejarah Desa'
$query = "SELECT content FROM tb_konten WHERE title = 'Sejarah Desa' LIMIT 1";
$result = $conn->query($query);
$sejarah_desa = '';

if ($result && $result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $sejarah_desa = $row['content'];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Sejarah Desa - Desa Cibening</title>

  <link rel="shortcut icon" href="assets/img/logo/logo_tab.png">
  <link href="assets/images/favicon.png" rel="icon">
  <link href="assets/images/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Poppins&family=Raleway&display=swap" rel="stylesheet">

  <!-- Vendor CSS -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS -->
  <link href="assets/css/main.css" rel="stylesheet">

  <style>
    .header {
      z-index: 9999;
    }

    section.section {
      scroll-margin-top: 100px;
    }

    .section-title img {
      height: 60px;
      margin-bottom: 10px;
    }

    .judul-wrapper h2 {
      margin-top: 10px;
      margin-bottom: 20px;
      position: relative;
      display: inline-block;
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

    .content {
      font-size: 1.05rem;
      line-height: 1.7;
      text-align: justify;
    }

    section#sejarahdesa {
      padding-top: 30px;
      padding-bottom: 40px;
    }
  </style>
</head>

<body class="index-page">
  <main class="main">

    <!-- Sejarah Desa -->
    <section id="sejarahdesa" class="about section">
      <div class="container" data-aos="fade-up">

        <!-- Logo + Judul -->
        <div class="section-title text-center">
          <a href="index.php">
            <img src="assets/img/logo/logo_home.png" alt="Logo Desa Cibening">
          </a>
          <div class="judul-wrapper">
            <h2>Sejarah Desa</h2>
          </div>
        </div>

        <!-- Konten -->
        <div class="row justify-content-center">
          <div class="col-lg-10 content" data-aos="fade-up" data-aos-delay="100">
            <?= nl2br($sejarah_desa); ?>
          </div>
        </div>

        <!-- Tombol Kembali -->
        <div class="text-center mt-4">
          <a href="index.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle"></i> Kembali
          </a>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer id="footer" class="footer dark-background">
      <div class="container text-center">
        <div class="header-with-logo d-flex align-items-center justify-content-center mb-3">
          <img src="assets/img/logo/logo_home.png" alt="Logo Desa Cibening" width="40" height="40" style="object-fit: contain;">
          <h3 class="sitename ms-3 mb-0">Sistem Informasi Desa Cibening</h3>
        </div>
        <div class="social-links d-flex justify-content-center mb-3">
          <a href="#"><i class="bi bi-twitter-x"></i></a>
          <a href="#"><i class="bi bi-facebook"></i></a>
          <a href="https://www.instagram.com/pemdes_cibening_berprestasi?igsh=MW5qdmJ3Y2Vyc3VpOQ=="><i class="bi bi-instagram"></i></a>
          <a href="#"><i class="bi bi-whatsapp"></i></a>
        </div>

        <div class="copyright">
          <span>&copy;</span> <strong class="px-1 sitename">Djody Rizaldi</strong> <span>All Rights Reserved</span>
        </div>

        <div class="credits">
          Designed by <a href="#">Djody Rizaldi</a>
        </div>
      </div>
    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
      <i class="bi bi-arrow-up-short"></i>
    </a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- JS Vendor -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>
  </main>
</body>

</html>
