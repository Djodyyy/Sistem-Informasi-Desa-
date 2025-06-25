<?php
include '../../functions/auth_warga.php';
include '../header_warga.php';
?>


<div class="container py-5">
  <!-- Judul Utama -->
  <div class="text-center mb-5">
    <h2 class="fw-bold">Selamat Datang, <?= $_SESSION['nama'] ?? 'Warga' ?>!</h2>
    <p class="text-muted">Silakan pilih layanan yang Anda butuhkan di bawah ini.</p>
  </div>

  <!-- Menu Utama -->
  <div class="row justify-content-center g-4">
    <!-- Pengajuan Surat -->
    <div class="col-12 col-sm-6 col-md-4">
      <div class="card shadow-lg h-100 text-center border-0 hover-shadow">
        <div class="card-body">
          <i class="fa fa-envelope fa-3x mb-3 text-success"></i>
          <h5 class="fw-bold">Pengajuan Surat</h5>
          <p>Ajukan surat keterangan secara online.</p>
          <a href="/Desa-Cibening/pengajuan_surat.php" class="btn btn-success w-100">Ajukan Surat</a>
        </div>
      </div>
    </div>

    <!-- Riwayat Permohonan -->
    <div class="col-12 col-sm-6 col-md-4">
      <div class="card shadow-lg h-100 text-center border-0">
        <div class="card-body">
          <i class="fa fa-history fa-3x mb-3 text-primary"></i>
          <h5 class="fw-bold">Riwayat Permohonan</h5>
          <p>Cek status surat yang telah diajukan.</p>
          <a href="riwayat_permohonan.php" class="btn btn-primary w-100">Lihat Riwayat</a>
        </div>
      </div>
    </div>

    <!-- Profil Saya -->
    <div class="col-12 col-sm-6 col-md-4">
      <div class="card shadow-lg h-100 text-center border-0">
        <div class="card-body">
          <i class="fa fa-user-cog fa-3x mb-3 text-warning"></i>
          <h5 class="fw-bold">Profil Saya</h5>
          <p>Lihat dan perbarui data diri Anda.</p>
          <a href="profil_saya.php" class="btn btn-warning text-white w-100">Kelola Profil</a>
        </div>
      </div>
    </div>

    <!-- Pengumuman Desa -->
    <div class="col-12 col-sm-6 col-md-4">
      <div class="card shadow-lg h-100 text-center border-0">
        <div class="card-body">
          <i class="fa fa-bullhorn fa-3x mb-3 text-info"></i>
          <h5 class="fw-bold">Pengumuman Desa</h5>
          <p>Info penting dan pengumuman dari kantor desa.</p>
          <a href="pengumuman.php" class="btn btn-info text-white w-100">Lihat Pengumuman</a>
        </div>
      </div>
    </div>

    <!-- Layanan Pengaduan -->
    <div class="col-12 col-sm-6 col-md-4">
      <div class="card shadow-lg h-100 text-center border-0">
        <div class="card-body">
          <i class="fa fa-comments fa-3x mb-3 text-danger"></i>
          <h5 class="fw-bold">Layanan Pengaduan</h5>
          <p>Sampaikan aspirasi atau laporan ke desa.</p>
          <a href="pengaduan.php" class="btn btn-danger w-100">Laporkan</a>
        </div>
      </div>
    </div>

    <!-- Data Keluarga -->
    <div class="col-12 col-sm-6 col-md-4">
      <div class="card shadow-lg h-100 text-center border-0">
        <div class="card-body">
          <i class="fa fa-users fa-3x mb-3 text-secondary"></i>
          <h5 class="fw-bold">Data Keluarga</h5>
          <p>Cek anggota keluarga dalam KK Anda.</p>
          <a href="data_keluarga.php" class="btn btn-secondary w-100">Lihat Data</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../footer_warga.php'; ?>
