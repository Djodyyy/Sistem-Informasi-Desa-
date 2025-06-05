<?php
require_once 'functions/koneksi.php';
require_once 'functions/function_hukum.php';

$dataProduk = getAllProdukHukum();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Produk Hukum - Desa Cibening</title>
  <link rel="shortcut icon" href="assets/img/logo/logo_tab.png">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Poppins&family=Raleway&display=swap" rel="stylesheet">

  <!-- Bootstrap + Icons -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- DataTables CSS -->
  <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

  <link href="assets/css/main.css" rel="stylesheet">

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
      margin-bottom: 20px;
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

    .btn-download {
      background-color: #28a745;
      color: white;
      font-size: 0.85rem;
    }

    .btn-download:hover {
      background-color: #218838;
    }

    table.dataTable td {
      vertical-align: middle;
    }
  </style>
</head>

<body class="index-page">
  <main class="main">

    <!-- Produk Hukum Section -->
    <section id="produkhukum" class="section">
      <div class="container" data-aos="fade-up">

        <div class="section-title text-center">
          <a href="index.php"><img src="assets/img/logo/logo_home.png" alt="Logo Desa Cibening"></a>
          <div class="judul-wrapper">
            <h2>Produk Hukum</h2>
          </div>
        </div>

        <!-- Tombol Kembali -->
        <div class="mb-3">
          <a href="index.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle"></i> Kembali
          </a>
        </div>

        <!-- Tabel -->
        <div class="table-responsive">
          <table id="tabelProdukHukum" class="table table-striped table-bordered">
            <thead class="table-success text-center">
              <tr>
                <th>No</th>
                <th>Judul Produk Hukum</th>
                <th>Kategori</th>
                <th>Tahun</th>
                <th>File</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($dataProduk)): ?>
                <?php $no = 1; foreach ($dataProduk as $row): ?>
                  <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['kategori']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['tahun']) ?></td>
                    <td class="text-center">
                      <?php if (!empty($row['file'])): ?>
                        <a href="uploads/<?= htmlspecialchars($row['file']) ?>" class="btn btn-sm btn-download" target="_blank">
                          <i class="bi bi-file-earmark-pdf"></i> Unduh
                        </a>
                      <?php else: ?>
                        <span class="text-muted">Tidak ada file</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" class="text-center text-danger">Belum ada data produk hukum.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
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
          Designed by <a href="#">Djody Rizaldi</a>
        </div>
      </div>
    </footer>

    <a href="#" class="scroll-top d-flex align-items-center justify-content-center">
      <i class="bi bi-arrow-up-short"></i>
    </a>

    <div id="preloader"></div>

    <!-- JS -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/js/main.js"></script>

    <!-- DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
      $(document).ready(function () {
        $('#tabelProdukHukum').DataTable({
          responsive: true,
          language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
          }
        });
      });
    </script>
</body>
</html>
