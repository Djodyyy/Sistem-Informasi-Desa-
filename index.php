<?php
require_once 'functions/koneksi.php';
$conn = dbConnect();

// Ambil semua konten aktif
$query = "SELECT * FROM tb_konten ORDER BY id ASC";
$result = $conn->query($query);
$konten = $result->fetch_all(MYSQLI_ASSOC);

//ambil data struktur 
$query = "SELECT * FROM struktur_aparatur ORDER BY id ASC";
$result = mysqli_query($conn, $query);
$slides = [];
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $slides[] = $row;
  }
}

// Ambil data statistik penduduk
$sql = "SELECT 
          SUM(jumlah_laki) AS total_laki, 
          SUM(jumlah_perempuan) AS total_perempuan,
          SUM(jumlah_ibu_hamil) AS total_ibu_hamil,
          SUM(jumlah_lansia) AS total_lansia,
          SUM(jumlah_balita) AS total_balita
        FROM tb_statistik";
$result = $conn->query($sql);

$laki = 0;
$perempuan = 0;
$ibu_hamil = 0;
$lansia = 0;
$balita = 0;

if ($result && $result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $laki = (int)$row['total_laki'];
  $perempuan = (int)$row['total_perempuan'];
  $ibu_hamil = (int)$row['total_ibu_hamil'];
  $lansia = (int)$row['total_lansia'];
  $balita = (int)$row['total_balita'];
}

//data anggaran
$query = "
  SELECT 
    a.id_anggaran,
    a.deskripsi,
    a.tahun,
    a.anggaran,
    a.realisasi,
    a.keterangan,
    GROUP_CONCAT(d.file_foto SEPARATOR ',') AS file_foto
  FROM tb_anggaran a
  LEFT JOIN tb_anggaran_dokumentasi d ON a.id_anggaran = d.id_anggaran
  GROUP BY a.id_anggaran
  ORDER BY a.tahun DESC, a.id_anggaran DESC
";
$result = mysqli_query($conn, $query);

//data galeri kegiatan
function getAllGaleriKegiatan()
{
  global $conn;
  $query = "SELECT * FROM tb_galeri_kegiatan ORDER BY tanggal_upload DESC";
  $result = mysqli_query($conn, $query);
  $data = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }
  return $data;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>HOME - PEMERINTAHAN DESA CIBENING</title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <link rel="shortcut icon" href="assets/img/logo/logo_tab.png">


  <!-- Favicons -->
  <link href="assets/images/favicon.png" rel="icon">
  <link href="assets/images/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">


  <!-- Main CSS File -->
  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">


  <!-- =======================================================
  * Template Name: KnightOne
  * Template URL: https://bootstrapmade.com/knight-simple-one-page-bootstrap-template/
  * Updated: Oct 16 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">


  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container position-relative d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">
        <img src="assets/img/logo/logo_home.png" alt="Logo Desa"
          style="height: 40px; margin-right: 10px;">
        <h1 class="sitename mb-0">Desa Cibening</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li class="dropdown"><a href="#"><span>Pemerintahan Desa</span> <i
                class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="visidanmisi.php">Visi Dan Misi</a></li>
              <li><a href="sejarah_desa.php">Sejarah Desa</a></li>
              <li><a href="#struktur">Pemerintah Desa</a></li>
            </ul>
          </li>
          <li class="dropdown"><a href="#"><span>Regulasi</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="produk_hukum.php">Produk Hukum</a></li>
              <li><a href="informasi_publik.php">Informasi Publik</a></li>
            </ul>
          </li>
          <li><a href="status_indeks_desa.php">Status Indeks Desa</a></li>
          <li><a href="pembangunan.php">Pembangunan</a></li>
          <li><a href="#galeri-kegiatan">Galeri</a></li>
          <li><a href="#contact">Kontak</a></li>
          <li class="dropdown"><a href="#"><span>Login</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="login_pegawai.php">Login Pegawai</a></li>
              <li><a href="perbaikan.html">Login Warga</a></li>
            </ul>
          </li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

      <img src="assets/img/Background-Home.png" alt="" data-aos="fade-in">

      <div class="container d-flex flex-column align-items-center text-center">
        <h2 data-aos="fade-up" data-aos-delay="100">Sistem Informasi</h2>
        <p data-aos="fade-up" data-aos-delay="200">Desa Cibening</p>
        <div data-aos="fade-up" data-aos-delay="300">
          <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="glightbox pulsating-play-btn"></a>
        </div>
      </div>
    </section><!-- /Hero Section -->

    <!-- Section Struktur Aparatur -->
    <section id="struktur" class="struktur section py-5 bg-light">
      <style>
        .struktur .card {
          border: none;
          box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
          transition: transform 0.3s ease;
        }

        .struktur .card:hover {
          transform: translateY(-5px);
        }

        .struktur .card-img-top {
          object-fit: cover;
          height: 320px;
          border-top-left-radius: 0.5rem;
          border-top-right-radius: 0.5rem;
        }

        .struktur .card-title {
          font-weight: 600;
          font-size: 1.1rem;
        }

        .struktur .card-text {
          font-size: 0.9rem;
          color: #6c757d;
        }

        /* Fallback grid untuk layar kecil */
        @media (max-width: 768px) {
          #aparaturCarousel {
            display: none;
          }

          .struktur-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
          }

          .struktur-grid .card {
            width: 100%;
            max-width: 280px;
          }

          .struktur-grid .card-img-top {
            height: 260px;
          }
        }
      </style>

      <div class="container section-title text-center mb-5" data-aos="fade-up">
        <h2 class="fw-bold">Struktur Aparatur Desa Cibening</h2>
        <p class="text-muted">Berikut adalah susunan aparatur yang menjalankan pemerintahan desa cibening</p>
      </div>

      <!-- Carousel untuk desktop -->
      <div class="container d-none d-md-block" data-aos="fade-up" data-aos-delay="100">
        <div id="aparaturCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
          <div class="carousel-inner">

            <?php foreach ($slides as $index => $slide): ?>
              <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                <div class="d-flex justify-content-center">
                  <div class="card text-center">
                    <img src="assets/img/aparatur/<?= htmlspecialchars($slide['foto']) ?>"
                      class="card-img-top"
                      alt="<?= htmlspecialchars($slide['jabatan']) ?>">
                    <div class="card-body">
                      <h5 class="card-title"><?= htmlspecialchars($slide['nama']) ?></h5>
                      <p class="card-text"><?= htmlspecialchars($slide['jabatan']) ?></p>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>

          </div>

          <!-- Controls -->
          <button class="carousel-control-prev" type="button" data-bs-target="#aparaturCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#aparaturCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>

      <!-- Grid fallback untuk mobile -->
      <div class="container d-md-none" data-aos="fade-up" data-aos-delay="100">
        <div class="struktur-grid">
          <?php foreach ($slides as $slide): ?>
            <div class="card text-center">
              <img src="assets/img/aparatur/<?= htmlspecialchars($slide['foto']) ?>"
                class="card-img-top"
                alt="<?= htmlspecialchars($slide['jabatan']) ?>">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($slide['nama']) ?></h5>
                <p class="card-text"><?= htmlspecialchars($slide['jabatan']) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!--section statistik penduduk-->
    <section id="statistik-penduduk" class="statistik-penduduk section bg-gradient" style="padding: 60px 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff;">
      <div class="container">
        <div class="section-title text-center mb-5" data-aos="fade-up">
          <h2 style="font-weight: 700; letter-spacing: 1.2px;">Statistik Data Penduduk</h2>
          <p style="font-size: 1.1rem; max-width: 600px; margin: 0 auto;">Komposisi jumlah penduduk desa cibening secara visual dan detail.</p>
        </div>

        <div class="row gy-4 align-items-center">

          <!-- Card Table -->
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <div class="card shadow-lg rounded-4" style="background: rgba(255,255,255,0.15); border: none;">
              <div class="card-body">
                <h5 class="card-title fw-bold mb-4">Detail Data Penduduk Terkini</h5>
                <table class="table table-borderless text-white mb-0" style="font-size: 1.05rem;">
                  <thead>
                    <tr>
                      <th>Kategori</th>
                      <th class="text-end">Jumlah</th>
                      <th class="text-end">Persentase</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><i class="bi bi-gender-male me-2"></i> Laki-laki</td>
                      <td class="text-end" id="table-laki"><?= $laki ?></td>
                      <td class="text-end" id="percent-laki">-</td>
                    </tr>
                    <tr>
                      <td><i class="bi bi-gender-female me-2"></i> Perempuan</td>
                      <td class="text-end" id="table-perempuan"><?= $perempuan ?></td>
                      <td class="text-end" id="percent-perempuan">-</td>
                    </tr>
                    <tr>
                      <td><i class="bi bi-person-plus-fill me-2"></i> Ibu Hamil</td>
                      <td class="text-end" id="table-ibu-hamil"><?= $ibu_hamil ?></td>
                      <td class="text-end" id="percent-ibu-hamil">-</td>
                    </tr>
                    <tr>
                      <td><i class="bi bi-person-walking me-2"></i> Lansia</td>
                      <td class="text-end" id="table-lansia"><?= $lansia ?></td>
                      <td class="text-end" id="percent-lansia">-</td>
                    </tr>
                    <tr>
                      <td><i class="bi bi-person-bounding-box me-2"></i> Balita</td>
                      <td class="text-end" id="table-balita"><?= $balita ?></td>
                      <td class="text-end" id="percent-balita">-</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Card Chart -->
          <div class="col-lg-6 text-center" data-aos="fade-up" data-aos-delay="200">
            <div class="card shadow-lg rounded-4 p-4" style="background: rgba(255,255,255,0.15); border: none;">
              <h5 class="card-title fw-bold mb-4">Diagram Komposisi Penduduk</h5>
              <canvas id="pendudukChart" style="max-width: 100%; height: 250px; max-height: 250px;"></canvas>

              <!-- Keterangan Warna -->
              <div class="mt-4 d-flex flex-wrap justify-content-center gap-2">
                <div style="padding: 6px 12px; border-radius: 6px; background: linear-gradient(#667EEA, #764BA2); color: white; font-size: 0.9rem;">
                  Laki-laki
                </div>
                <div style="padding: 6px 12px; border-radius: 6px; background: linear-gradient(#F783AC, #CC3A71); color: white; font-size: 0.9rem;">
                  Perempuan
                </div>
                <div style="padding: 6px 12px; border-radius: 6px; background: linear-gradient(#FFD700, #FFA500); color: black; font-size: 0.9rem;">
                  Ibu Hamil
                </div>
                <div style="padding: 6px 12px; border-radius: 6px; background: linear-gradient(#7ED6DF, #22A6B3); color: black; font-size: 0.9rem;">
                  Lansia
                </div>
                <div style="padding: 6px 12px; border-radius: 6px; background: linear-gradient(#EAB543, #F19066); color: black; font-size: 0.9rem;">
                  Balita
                </div>
              </div>

            </div>
          </div>

          <!-- Bootstrap Icons CDN -->
          <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

          <!-- Chart.js CDN -->
          <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

          <script>
            document.addEventListener('DOMContentLoaded', function() {
              const laki = <?= $laki ?>;
              const perempuan = <?= $perempuan ?>;
              const ibuHamil = <?= $ibu_hamil ?>;
              const lansia = <?= $lansia ?>;
              const balita = <?= $balita ?>;

              const total = laki + perempuan + ibuHamil + lansia + balita;

              const updatePercent = (id, jumlah) => {
                const persen = total ? ((jumlah / total) * 100).toFixed(1) + '%' : '-';
                const el = document.getElementById(id);
                if (el) el.textContent = persen;
              };

              updatePercent('percent-laki', laki);
              updatePercent('percent-perempuan', perempuan);
              updatePercent('percent-ibu-hamil', ibuHamil);
              updatePercent('percent-lansia', lansia);
              updatePercent('percent-balita', balita);

              const ctx = document.getElementById('pendudukChart').getContext('2d');

              const gradients = [
                ['#667EEA', '#764BA2'], // Laki-laki
                ['#F783AC', '#CC3A71'], // Perempuan
                ['#FFD700', '#FFA500'], // Ibu Hamil
                ['#7ED6DF', '#22A6B3'], // Lansia
                ['#EAB543', '#F19066'] // Balita
              ].map(([start, end]) => {
                const gradient = ctx.createLinearGradient(0, 0, 0, 250);
                gradient.addColorStop(0, start);
                gradient.addColorStop(1, end);
                return gradient;
              });

              new Chart(ctx, {
                type: 'doughnut',
                data: {
                  labels: ['Laki-laki', 'Perempuan', 'Ibu Hamil', 'Lansia', 'Balita'],
                  datasets: [{
                    label: 'Statistik Penduduk',
                    data: [laki, perempuan, ibuHamil, lansia, balita],
                    backgroundColor: gradients,
                    borderColor: ['#667EEA', '#F783AC', '#FFD700', '#7ED6DF', '#EAB543'],
                    borderWidth: 3
                  }]
                },
                options: {
                  responsive: true,
                  animation: {
                    animateRotate: true,
                    duration: 1800,
                    easing: 'easeOutCubic'
                  },
                  cutout: '65%',
                  plugins: {
                    legend: {
                      display: false
                    },
                    tooltip: {
                      backgroundColor: 'rgba(0,0,0,0.7)',
                      cornerRadius: 4
                    }
                  }
                }
              });
            });
          </script>



          <!-- Transparansi Anggaran Section -->
          <section id="transparansi" class="section bg-light">
            <div class="container section-title" data-aos="fade-up">
              <h2>Transparansi Anggaran</h2>
              <p>Informasi anggaran desa yang dikelola secara terbuka dan akuntabel</p>
            </div>

            <style>
              .circular-chart {
                width: 60px;
                height: 60px;
              }

              .circular-chart.orange .circle {
                stroke: #FFA500;
              }

              .circular-chart.green .circle {
                stroke: #4CAF50;
              }

              .circular-chart .circle-bg {
                fill: none;
                stroke: #eee;
                stroke-width: 3.8;
              }

              .circular-chart .circle {
                fill: none;
                stroke-width: 2.8;
                stroke-linecap: round;
                animation: progress 1s ease-out forwards;
              }

              .circular-chart .percentage {
                fill: #333;
                font-size: 0.5em;
                text-anchor: middle;
              }

              .table-responsive {
                overflow-x: auto;
              }
            </style>

            <div class="container">
              <div class="table-responsive" data-aos="fade-up" data-aos-delay="100">
                <table class="table table-bordered align-middle">
                  <thead class="table-dark text-center">
                    <tr>
                      <th>No.</th>
                      <th>Deskripsi Anggaran</th>
                      <th>Tahun</th>
                      <th>Anggaran (Rp)</th>
                      <th>Realisasi (Rp)</th>
                      <th>Progres</th>
                      <th>% Realisasi</th>
                      <th>Dokumentasi</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                      $anggaran = $row['anggaran'] ?? 0;
                      $realisasi = $row['realisasi'] ?? 0;
                      $progres = $anggaran > 0 ? round(($realisasi / $anggaran) * 100) : 0;
                      $colorClass = ($progres >= 80) ? 'green' : 'orange';
                      $strokeDash = $progres . ", 100";
                      $formatAnggaran = number_format($anggaran, 0, ',', '.');
                      $formatRealisasi = number_format($realisasi, 0, ',', '.');

                      $file_foto = $row['file_foto'] ?? '';
                      $gambarList = !empty($file_foto) ? array_map('trim', explode(',', $file_foto)) : [];

                      echo "<tr>";
                      echo "<td class='text-center'>{$no}</td>";
                      echo "<td>" . htmlspecialchars($row['deskripsi'], ENT_QUOTES) . "</td>";
                      echo "<td class='text-center'>" . htmlspecialchars($row['tahun'], ENT_QUOTES) . "</td>";
                      echo "<td class='text-end'>{$formatAnggaran}</td>";
                      echo "<td class='text-end'>{$formatRealisasi}</td>";
                      echo "<td class='text-center'>
              <svg viewBox='0 0 36 36' class='circular-chart {$colorClass}'>
                <path class='circle-bg' d='M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 
                  a 15.9155 15.9155 0 0 1 0 -31.831' />
                <path class='circle' stroke-dasharray='{$strokeDash}' 
                  d='M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 
                  a 15.9155 15.9155 0 0 1 0 -31.831' />
                <text x='18' y='20' class='percentage'>{$progres}%</text>
              </svg>
            </td>";
                      echo "<td class='text-center fw-bold'>{$progres}%</td>";

                      echo "<td class='text-center'>";
                      if (!empty($gambarList)) {
                        foreach ($gambarList as $gambar) {
                          if (!empty($gambar)) {
                            $gambarEscaped = htmlspecialchars($gambar, ENT_QUOTES);
                            echo "<img src='uploads/anggaran/{$gambarEscaped}' alt='Dokumentasi' 
                          width='50' class='img-thumbnail me-1 mb-1'>";
                          }
                        }
                      } else {
                        echo "<span class='text-muted'>Tidak ada</span>";
                      }
                      echo "</td>";

                      echo "<td class='text-center'>
              <button type='button' class='btn btn-sm btn-primary'
                data-bs-toggle='modal'
                data-bs-target='#modalDetail'
                data-deskripsi='" . htmlspecialchars($row['deskripsi'], ENT_QUOTES) . "'
                data-tahun='" . htmlspecialchars($row['tahun'], ENT_QUOTES) . "'
                data-anggaran='{$formatAnggaran}'
                data-realisasi='{$formatRealisasi}'
                data-keterangan='" . htmlspecialchars($row['keterangan'], ENT_QUOTES) . "'
                data-gambar='" . htmlspecialchars($file_foto, ENT_QUOTES) . "'>
                Detail
              </button>
            </td>";
                      echo "</tr>";
                      $no++;
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </section>

          <!-- Modal Detail -->
          <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalDetailLabel">Detail Anggaran</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                  <p><strong>Deskripsi:</strong> <span id="detailDeskripsi"></span></p>
                  <p><strong>Tahun:</strong> <span id="detailTahun"></span></p>
                  <p><strong>Anggaran:</strong> Rp <span id="detailAnggaran"></span></p>
                  <p><strong>Realisasi:</strong> Rp <span id="detailRealisasi"></span></p>
                  <p><strong>Keterangan:</strong> <span id="detailKeterangan"></span></p>
                  <hr>
                  <h6>Dokumentasi:</h6>
                  <div id="detailGambar" class="row g-2"></div>
                </div>
              </div>
            </div>
          </div>

          <script>
            const modalDetail = document.getElementById('modalDetail');
            modalDetail.addEventListener('show.bs.modal', function(event) {
              const button = event.relatedTarget;
              document.getElementById('detailDeskripsi').textContent = button.getAttribute('data-deskripsi');
              document.getElementById('detailTahun').textContent = button.getAttribute('data-tahun');
              document.getElementById('detailAnggaran').textContent = button.getAttribute('data-anggaran');
              document.getElementById('detailRealisasi').textContent = button.getAttribute('data-realisasi');
              document.getElementById('detailKeterangan').textContent = button.getAttribute('data-keterangan');

              const gambarContainer = document.getElementById('detailGambar');
              gambarContainer.innerHTML = '';

              const gambarData = button.getAttribute('data-gambar');
              if (gambarData) {
                const gambarArray = gambarData.split(',');
                gambarArray.forEach(function(filename) {
                  const trimmed = filename.trim();
                  if (trimmed) {
                    const img = document.createElement('img');
                    img.src = 'uploads/anggaran/' + trimmed;
                    img.className = 'col-md-3 img-fluid rounded border';
                    img.alt = 'Dokumentasi';
                    gambarContainer.appendChild(img);
                  }
                });
              } else {
                gambarContainer.innerHTML = "<p class='text-muted'>Tidak ada dokumentasi tersedia.</p>";
              }
            });
          </script>

          <!-- Lokasi Desa Section -->
          <section id="lokasi-desa" class="lokasi-desa section" style="position: relative; background-color: #f8f9fa; padding: 60px 0;">
            <div class="container">
              <div class="row" data-aos="fade-up" data-aos-delay="100">

                <!-- Info Lokasi -->
                <div class="col-lg-5 mb-4 mb-lg-0 d-flex flex-column justify-content-center">
                  <h3>Lokasi Desa Cibening Bungursari Kab.Purwakarta</h3>
                  <p>Alamat: Kantor Kepala Desa Cibening, Kec. Bungursari, Kab. Purwakarta, Jawa Barat</p>
                  <p>Telepon: </p>
                  <p>Email: </p>
                  <p>Jam Kerja: Senin - Jumat, 08:00 - 16:00</p>
                  <a href="https://www.google.com/maps/place/Kantor+Kepala+Desa+Cibening/@-6.4971327,107.472435,20.05z" target="_blank" class="btn btn-primary mt-3" style="width: fit-content;">
                    Lihat di Google Maps
                  </a>
                </div>

                <!-- Google Maps Embed -->
                <div class="col-lg-7">
                  <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3958.145768297014!2d107.4706438151855!3d-6.494436087849455!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e690d8668b23c41%3A0x21b0977126fbc548!2sKantor%20Kepala%20Desa%20Cibening!5e0!3m2!1sen!2sid!4v1684298999999!5m2!1sen!2sid"
                    width="100%"
                    height="350"
                    style="border:0; border-radius: 8px;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>

              </div>
            </div>
          </section>

          <?php
          include_once 'functions/function_berita_artikel.php';
          $beritaTerbaru = getBeritaTerbaru(3); // Ambil 3 berita terbaru
          ?>

          <!-- Berita & Artikel Section -->
          <section id="berita" class="berita section">
            <div class="container">
              <div class="row gy-4">

                <!-- Gambar Banner atau Ilustrasi -->
                <div class="berita-image col-lg-6 order-lg-2" data-aos="fade-up" data-aos-delay="100">
                  <img src="assets/img/news-banner.jpg" alt="Berita & Artikel" class="img-fluid rounded shadow">
                  <!-- Jika belum punya gambarnya, pakai placeholder ini:
        <img src="https://via.placeholder.com/900x600?text=Banner+Berita" alt="Berita & Artikel" class="img-fluid rounded shadow"> -->
                </div>

                <!-- Daftar Berita & Artikel -->
                <div class="col-lg-6 order-lg-1">
                  <h2 class="section-title" data-aos="fade-up" data-aos-delay="150">Berita & Artikel Terbaru</h2>

                  <div class="berita-list mt-4">
                    <?php
                    if (!empty($beritaTerbaru)) {
                      $delay = 200;
                      foreach ($beritaTerbaru as $berita) {
                        $gambar = !empty($berita['gambar']) ? "uploads/{$berita['gambar']}" : "assets/img/default-news.jpg";
                        $judul = htmlspecialchars($berita['judul']);
                        $isiSingkat = strip_tags(substr($berita['isi'], 0, 100)) . "...";
                        $tanggal = date("d M Y", strtotime($berita['tanggal']));
                        echo "
              <article class='berita-item d-flex mb-4' data-aos='fade-up' data-aos-delay='{$delay}'>
                <div class='berita-thumb flex-shrink-0 me-3'>
                  <img src='{$gambar}' alt='{$judul}' class='rounded' style='width: 100px; height: 70px; object-fit: cover;'>
                </div>
        <div>
           <h5>
              <a href='detail_berita.php?id={$berita['id_berita']}' class='text-decoration-none'>
              {$judul}
              </a>
            </h5>
              <p class='text-muted mb-0' style='font-size: 0.9rem;'>{$isiSingkat}</p>
            <small class='text-muted'>{$tanggal}</small>
        </div>
              </article>
              ";
                        $delay += 100;
                      }
                    } else {
                      echo "<p class='text-muted'>Belum ada berita tersedia.</p>";
                    }
                    ?>
                    <a href="semua_berita.php" class="btn btn-primary mt-3" data-aos="fade-up" data-aos-delay="500">Lihat Semua Berita</a>
                  </div>
                </div>

              </div>
            </div>
          </section>
          <!--End Section Berita dan Artikel-->

          <!-- Galeri & Kegiatan Section -->
          <section id="galeri-kegiatan" class="section bg-light">

            <div class="container section-title" data-aos="fade-up">
              <h2>Galeri & Kegiatan</h2>
              <p>Dokumentasi kegiatan dan momen penting Desa Cibening</p>
            </div>

            <div class="container">
              <div class="row gy-4">
                <?php
                include 'functions/function_galeri_kegiatan.php';
                $listGaleri = getAllGaleriKegiatan();

                if ($listGaleri && count($listGaleri) > 0) {
                  $delay = 100;
                  foreach ($listGaleri as $item) {
                    $id = (int)$item['id'];
                    $judul = htmlspecialchars($item['judul']);
                    $deskripsi = htmlspecialchars($item['deskripsi']);
                    $fileGambar = htmlspecialchars($item['file_foto']);

                    // ✅ Path gambar sesuai folder upload
                    $gambar = !empty($fileGambar) ? 'uploads/galeri/' . $fileGambar : 'assets/img/default-galeri.jpg';

                    echo '<div class="col-lg-4 col-md-6" data-aos="zoom-in" data-aos-delay="' . $delay . '">
            <a href="detail_galeri.php?id=' . $id . '" class="text-decoration-none text-dark">
              <div class="card h-100 border-0 shadow-sm">
                <img src="' . $gambar . '" class="card-img-top" alt="' . $judul . '">
                <div class="card-body">
                  <h5 class="card-title">' . $judul . '</h5>
                  <p class="card-text">' . $deskripsi . '</p>
                </div>
              </div>
            </a>
          </div>';
                    $delay += 100;
                  }
                } else {
                  echo '<div class="col-12"><p class="text-center">Belum ada galeri atau kegiatan yang ditampilkan.</p></div>';
                }
                ?>
              </div>
            </div>

          </section>
          <!-- /Galeri & Kegiatan Section -->
          <!-- Contact Dan Pengadun -->
          <section id="contact" class="contact section light-background">

            <div class="container section-title" data-aos="fade-up">
              <h2>Hubungi Kami</h2>
              <p>Silakan hubungi kami untuk pertanyaan, pengaduan, atau permohonan surat di Desa Kami.</p>
            </div>

            <div class="container" data-aos="fade" data-aos-delay="100">
              <div class="row gy-4">

                <div class="col-lg-4">
                  <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="200">
                    <i class="bi bi-geo-alt flex-shrink-0"></i>
                    <div>
                      <h3>Alamat Kantor Desa Cibening</h3>
                      <p>Kantor Kepala Desa Cibening, Kec. Bungursari, Kab. Purwakarta, Jawa Barat</p>
                    </div>
                  </div>

                  <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
                    <i class="bi bi-telephone flex-shrink-0"></i>
                    <div>
                      <h3>Telepon</h3>
                      <p>+62 (Kantor Desa)</p>
                    </div>
                  </div>

                  <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
                    <i class="bi bi-envelope flex-shrink-0"></i>
                    <div>
                      <h3>Email</h3>
                      <p>-</p>
                    </div>
                  </div>
                </div>

                <div class="col-lg-8">
                  <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
                    <div class="row gy-4">

                      <div class="col-md-6">
                        <input type="text" name="name" class="form-control" placeholder="Nama Anda" required>
                      </div>

                      <div class="col-md-6">
                        <input type="email" name="email" class="form-control" placeholder="Email Anda" required>
                      </div>

                      <div class="col-md-12">
                        <select name="jenis_pesan" class="form-control" required>
                          <option value="" disabled selected>Jenis Pesan</option>
                          <option value="pertanyaan">Pertanyaan</option>
                          <option value="pengaduan">Pengaduan</option>
                          <option value="permohonan_surat">Permohonan Surat</option>
                          <option value="lainnya">Lainnya</option>
                        </select>
                      </div>

                      <div class="col-md-12">
                        <textarea name="message" rows="6" class="form-control" placeholder="Isi Pesan" required></textarea>
                      </div>

                      <div class="col-md-12 text-center">
                        <div class="loading">Loading</div>
                        <div class="error-message"></div>
                        <div class="sent-message">Pesan Anda telah terkirim. Terima kasih!</div>

                        <button type="submit">Kirim Pesan</button>
                      </div>

                    </div>
                  </form>
                </div>

              </div>
            </div>

          </section>
          <!-- End Section Contact Pengaduan -->


  </main>

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

  <!-- Vendor JS -->
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
</body>

</html>