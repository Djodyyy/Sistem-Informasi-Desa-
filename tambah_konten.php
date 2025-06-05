<?php
include_once 'partials/header.php';

// Contoh data total konten dari fungsi (ganti sesuai database)
// Biasanya ini kamu ambil dari query, misal:
// $totalUtama = getTotalKonten('utama');
// $totalStruktur = getTotalKonten('struktur');
// dst.
$totalUtama = 12;
$totalStruktur = 5;
$totalBerita = 20;
$totalGaleri = 8;
?>

<style>
  .stat-card {
    border-radius: 0.75rem;
    box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 0.15);
    padding: 1.5rem;
    background: #fff;
    transition: transform 0.3s ease;
  }
  .stat-card:hover {
    transform: translateY(-5px);
  }
  .stat-number {
    font-size: 3rem;
    font-weight: 700;
    color: #007bff;
  }
  .stat-label {
    font-size: 1.2rem;
    font-weight: 500;
    color: #555;
  }
</style>

<div class="container py-4">
  <h4 class="text-center mb-4">Total Konten per Kategori</h4>
  <div class="row g-4 justify-content-center">

    <div class="col-6 col-md-3">
      <div class="stat-card text-center">
        <div class="stat-number" data-target="<?= $totalUtama ?>">0</div>
        <div class="stat-label">Halaman Utama</div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="stat-card text-center">
        <div class="stat-number" data-target="<?= $totalStruktur ?>">0</div>
        <div class="stat-label">Struktur Aparatur</div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="stat-card text-center">
        <div class="stat-number" data-target="<?= $totalBerita ?>">0</div>
        <div class="stat-label">Berita & Artikel</div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="stat-card text-center">
        <div class="stat-number" data-target="<?= $totalGaleri ?>">0</div>
        <div class="stat-label">Galeri & Kegiatan</div>
      </div>
    </div>

  </div>
</div>

<script>
// Animasi Count Up sederhana
const counters = document.querySelectorAll('.stat-number');

counters.forEach(counter => {
  const updateCount = () => {
    const target = +counter.getAttribute('data-target');
    const count = +counter.innerText;

    const increment = Math.ceil(target / 100);

    if(count < target) {
      counter.innerText = count + increment;
      setTimeout(updateCount, 20);
    } else {
      counter.innerText = target;
    }
  };
  updateCount();
});
</script>

<?php
include_once 'partials/footer.php';
?>
