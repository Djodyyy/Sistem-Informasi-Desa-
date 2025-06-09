<?php
include_once 'partials/header.php';

// Data dummy pengaduan warga
$pengaduans = [
    ['id' => 1, 'nama' => 'Budi Santoso', 'email' => 'budi@mail.com', 'isi' => 'Jalan di depan rumah berlubang dan perlu diperbaiki segera.', 'status' => 'baru', 'tanggal' => '2025-06-07 09:15'],
    ['id' => 2, 'nama' => 'Siti Aminah', 'email' => 'siti@mail.com', 'isi' => 'Air PDAM sering mati di wilayah RW 05.', 'status' => 'diproses', 'tanggal' => '2025-06-06 15:30'],
    ['id' => 3, 'nama' => 'Agus Wijaya', 'email' => 'agus@mail.com', 'isi' => 'Permohonan lampu jalan di gang 3 sudah lama belum terpasang.', 'status' => 'selesai', 'tanggal' => '2025-06-05 10:00'],
];
?>

<div class="container-fluid my-4">
  <div class="card shadow-sm">
    <div class="card-body">
      <h4 class="text-center mb-4">DATA PENGADUAN WARGA (Dummy)</h4>

      <div class="table-responsive">
        <table class="table table-bordered table-hover" style="font-size: 14px;">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Isi Pengaduan</th>
              <th>Status</th>
              <th>Tanggal Pengaduan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (count($pengaduans) > 0) {
                foreach ($pengaduans as $p) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($p['id']) . '</td>';
                    echo '<td>' . htmlspecialchars($p['nama']) . '</td>';
                    echo '<td>' . htmlspecialchars($p['email']) . '</td>';
                    echo '<td>' . htmlspecialchars($p['isi']) . '</td>';
                    
                    $statusClass = '';
                    switch ($p['status']) {
                        case 'baru':
                            $statusClass = 'bg-primary';
                            break;
                        case 'diproses':
                            $statusClass = 'bg-warning text-dark';
                            break;
                        case 'selesai':
                            $statusClass = 'bg-success';
                            break;
                        default:
                            $statusClass = 'bg-secondary';
                    }
                    echo '<td><span class="badge ' . $statusClass . '">' . ucfirst($p['status']) . '</span></td>';
                    
                    echo '<td>' . htmlspecialchars(date('d-m-Y H:i', strtotime($p['tanggal']))) . '</td>';
                    echo '<td><button class="btn btn-info btn-sm" onclick="alert(\'Lihat detail pengaduan ID: ' . $p['id'] . '\')"><i class="mdi mdi-eye"></i> Detail</button></td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="7" class="text-center">Belum ada data pengaduan.</td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>

<?php
include_once 'partials/footer.php';
?>
