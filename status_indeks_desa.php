<?php
require_once 'functions/koneksi.php';
require_once 'functions/function_status_indeks.php';

// Ambil daftar tahun indeks dan data indeks untuk tahun terpilih
$tahunList = getAllTahunIndeks();
$tahunDipilih = isset($_GET['tahun']) ? intval($_GET['tahun']) : (count($tahunList) > 0 ? max($tahunList) : null);
$dataIndeks = $tahunDipilih ? getIndeksByTahun($tahunDipilih) : [];


function badgeStatus($status)
{
  switch ($status) {
    case 'Mandiri':
      return '<span class="badge bg-success">Mandiri</span>';
    case 'Maju':
      return '<span class="badge bg-primary">Maju</span>';
    case 'Berkembang':
      return '<span class="badge bg-info text-dark">Berkembang</span>';
    case 'Tertinggal':
      return '<span class="badge bg-warning text-dark">Tertinggal</span>';
    case 'Sangat Tertinggal':
      return '<span class="badge bg-danger">Sangat Tertinggal</span>';
    default:
      return '<span class="badge bg-secondary">Tidak Diketahui</span>';
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Status Indeks Desa - Desa Cibening</title>
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
  </style>
</head>

<body class="index-page">
  <main class="main">
    <!-- Status Indeks Desa Section -->
    <section id="status_indeks" class="section">
      <div class="container" data-aos="fade-up">
        <div class="section-title text-center">
          <a href="index.php"><img src="assets/img/logo/logo_home.png" alt="Logo Desa Cibening" /></a>
          <div class="judul-wrapper">
            <h2>Status Indeks Desa Tahun <?= $tahunDipilih ?? '-' ?></h2>
          </div>
        </div>

        <!-- Filter Tahun -->
        <form method="GET" class="mb-4 d-flex justify-content-center">
          <select name="tahun" class="form-select w-auto" onchange="this.form.submit()">
            <?php foreach ($tahunList as $tahun): ?>
              <option value="<?= $tahun ?>" <?= ($tahun == $tahunDipilih) ? 'selected' : '' ?>><?= $tahun ?></option>
            <?php endforeach; ?>
          </select>
        </form>

        <?php if ($tahunDipilih === null): ?>
          <div class="text-center text-danger mb-4">Belum ada data status indeks desa.</div>
        <?php else: ?>

          <?php
          // Hitung rata-rata nilai indeks dan status
          $nilaiRata = 0;
          if (count($dataIndeks) > 0) {
            $total = array_sum(array_column($dataIndeks, 'nilai'));
            $nilaiRata = $total / count($dataIndeks);
          }
          $statusRata = hitungIndeks($nilaiRata);
          ?>

          <div class="card mb-4 text-center shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Nilai Indeks Rata-rata</h5>
              <h1 class="display-4"><?= number_format($nilaiRata, 2) ?></h1>
              <p class="fs-5"><?= badgeStatus($statusRata) ?></p>
            </div>
          </div>

          <!-- Tabel Status Indeks -->
          <div class="table-responsive shadow-sm rounded">
            <table class="table table-bordered table-hover table-striped align-middle text-center mb-5">
              <thead class="table-success">
                <tr>
                  <th>No</th>
                  <th>Nilai Indeks</th>
                  <th>Status</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($dataIndeks)): ?>
                  <?php $no = 1;
                  foreach ($dataIndeks as $row): ?>
                    <tr>
                      <td><?= $no++ ?></td>
                      <td><?= number_format($row['nilai'], 2) ?></td>
                      <td><?= badgeStatus(hitungIndeks($row['nilai'])) ?></td>
                      <td title="<?= htmlspecialchars($row['keterangan']) ?>"><?= htmlspecialchars($row['keterangan']) ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="4" class="text-muted">Data tidak tersedia untuk tahun ini.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <!-- Tombol Kembali -->
          <div class="text-center mb-5">
            <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left-circle"></i> Kembali</a>
          </div>

        <?php endif; ?>
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