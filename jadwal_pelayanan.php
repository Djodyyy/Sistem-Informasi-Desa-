<?php
include_once 'partials/header.php';

// Data dummy jadwal pelayanan
$jadwalPelayanan = [
    ['id' => 1, 'nama_kegiatan' => 'Vaksinasi Massal', 'hari' => 'Senin', 'jam' => '08:00 - 12:00', 'lokasi' => 'Balai Desa'],
    ['id' => 2, 'nama_kegiatan' => 'Pelayanan Kesehatan Keliling', 'hari' => 'Rabu', 'jam' => '09:00 - 13:00', 'lokasi' => 'Lapangan RW 03'],
    ['id' => 3, 'nama_kegiatan' => 'Pembuatan KTP Keliling', 'hari' => 'Jumat', 'jam' => '08:00 - 11:30', 'lokasi' => 'Halaman Kantor Desa'],
];
?>

<div class="container-fluid my-4">
  <div class="card shadow-sm">
    <div class="card-body">
      <h4 class="text-center mb-4">JADWAL PELAYANAN KHUSUS (Dummy)</h4>

      <div class="table-responsive">
        <table class="table table-bordered table-hover" style="font-size: 14px;">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Nama Kegiatan</th>
              <th>Hari</th>
              <th>Jam</th>
              <th>Lokasi</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (count($jadwalPelayanan) > 0) {
                foreach ($jadwalPelayanan as $j) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($j['id']) . '</td>';
                    echo '<td>' . htmlspecialchars($j['nama_kegiatan']) . '</td>';
                    echo '<td>' . htmlspecialchars($j['hari']) . '</td>';
                    echo '<td>' . htmlspecialchars($j['jam']) . '</td>';
                    echo '<td>' . htmlspecialchars($j['lokasi']) . '</td>';
                    echo '<td><button class="btn btn-info btn-sm" onclick="alert(\'Lihat detail jadwal ID: ' . $j['id'] . '\')"><i class="mdi mdi-eye"></i> Detail</button></td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="6" class="text-center">Belum ada jadwal pelayanan.</td></tr>';
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
