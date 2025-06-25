<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Dashboard Warga - Desa Cibening</title>
  <link rel="shortcut icon" href="../../assets/img/logo/logo_tab.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body>

  <!-- Navbar khusus warga -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="/Desa-Cibening/assets/img/logo/logo_tab.png" alt="Logo" width="35" height="35" class="me-2">
        <span>Desa Cibening</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link active" href="/Desa-Cibening/partials/dashboards/warga.php">Beranda</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/Desa-Cibening/pengajuan_surat.php">Ajukan Surat</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Riwayat</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Profil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-danger" href="../../functions/logout_warga.php">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Jam Digital Bisa Digeser -->
  <div id="jamDigitalWrapper" onmousedown="startDrag(event)">
    <div id="jamDigital"></div>
  </div>

  <style>
    #jamDigitalWrapper {
      position: absolute;
      top: 75px;
      left: 20px;
      /* POSISI AWAL DI KIRI */
      z-index: 9999;
      background-color: #ffffff;
      border: 2px solid #198754;
      padding: 6px 14px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      cursor: default;
      user-select: none;
    }

    #jamDigital {
      font-size: 1.2rem;
      font-weight: bold;
      color: #198754;
      font-family: 'Courier New', Courier, monospace;
    }
  </style>

  <script>
    // Jam realtime WIB
    function updateJam() {
      const waktu = new Date();
      const offset = 7 * 60;
      const localTime = new Date(waktu.getTime() + (offset + waktu.getTimezoneOffset()) * 60000);

      const jam = localTime.getHours().toString().padStart(2, '0');
      const menit = localTime.getMinutes().toString().padStart(2, '0');
      const detik = localTime.getSeconds().toString().padStart(2, '0');

      document.getElementById("jamDigital").innerText = `${jam}:${menit}:${detik} WIB`;
    }

    setInterval(updateJam, 1000);
    updateJam();

    // Fungsi drag
    let isDragging = false;
    let offsetX, offsetY;

    function startDrag(e) {
      isDragging = true;
      const el = document.getElementById('jamDigitalWrapper');
      offsetX = e.clientX - el.getBoundingClientRect().left;
      offsetY = e.clientY - el.getBoundingClientRect().top;

      document.addEventListener('mousemove', drag);
      document.addEventListener('mouseup', stopDrag);
    }

    function drag(e) {
      if (!isDragging) return;
      const el = document.getElementById('jamDigitalWrapper');

      el.style.transform = 'none'; // hapus transform kalau sebelumnya ada
      el.style.left = (e.clientX - offsetX) + 'px';
      el.style.top = (e.clientY - offsetY) + 'px';
      el.style.right = 'auto';
    }

    function stopDrag() {
      isDragging = false;
      document.removeEventListener('mousemove', drag);
      document.removeEventListener('mouseup', stopDrag);
    }
  </script>


  <div class="container mt-4">
    <!-- konten halaman dashboard warga di sini -->