<?php
include_once 'partials/header.php';

// Data dummy permohonan, sama seperti sebelumnya
$permohonans = [
    ['id' => 1, 'nama_pemohon' => 'Andi Saputra', 'tanggal' => '2025-06-01', 'jenis_permohonan' => 'Surat Keterangan', 'status' => 'pending'],
    ['id' => 2, 'nama_pemohon' => 'Siti Aminah', 'tanggal' => '2025-06-02', 'jenis_permohonan' => 'Surat Pengantar', 'status' => 'approved'],
    ['id' => 3, 'nama_pemohon' => 'Budi Santoso', 'tanggal' => '2025-06-03', 'jenis_permohonan' => 'Izin Usaha', 'status' => 'approved'],
];

// Data dummy arsip surat
$arsip_surat = [
    ['id' => 101, 'nama_pemohon' => 'Siti Aminah', 'jenis_permohonan' => 'Surat Pengantar', 'tanggal_cetak' => '2025-06-04', 'nomor_surat' => 'SK-2025/001'],
    ['id' => 102, 'nama_pemohon' => 'Budi Santoso', 'jenis_permohonan' => 'Izin Usaha', 'tanggal_cetak' => '2025-06-05', 'nomor_surat' => 'SK-2025/002'],
];

?>

<div class="container-fluid my-4">
  <div class="card shadow-sm">
    <div class="card-body">
      <h4 class="text-center mb-4">PENGELOLAAN SURAT</h4>

      <!-- Nav tabs -->
      <ul class="nav nav-tabs" id="pengelolaanSuratTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="cetak-tab" data-bs-toggle="tab" data-bs-target="#cetak" type="button" role="tab" aria-controls="cetak" aria-selected="true">
            Cetak Surat Permohonan Disetujui
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="arsip-tab" data-bs-toggle="tab" data-bs-target="#arsip" type="button" role="tab" aria-controls="arsip" aria-selected="false">
            Arsip Surat Sudah Jadi
          </button>
        </li>
      </ul>

      <!-- Tab content -->
      <div class="tab-content mt-3" id="pengelolaanSuratTabContent">
        <!-- Cetak Surat Permohonan -->
        <div class="tab-pane fade show active" id="cetak" role="tabpanel" aria-labelledby="cetak-tab">
          <div class="table-responsive">
            <table class="table table-bordered table-hover" style="font-size: 14px;">
              <thead class="table-dark">
                <tr>
                  <th>ID</th>
                  <th>Nama Pemohon</th>
                  <th>Tanggal Permohonan</th>
                  <th>Jenis Permohonan</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $foundApproved = false;
                foreach ($permohonans as $p) {
                  if ($p['status'] === 'approved') {
                    $foundApproved = true;
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($p['id']) . '</td>';
                    echo '<td>' . htmlspecialchars($p['nama_pemohon']) . '</td>';
                    echo '<td>' . htmlspecialchars(date('d-m-Y', strtotime($p['tanggal']))) . '</td>';
                    echo '<td>' . htmlspecialchars($p['jenis_permohonan']) . '</td>';
                    echo '<td><span class="badge bg-success text-capitalize">' . htmlspecialchars($p['status']) . '</span></td>';
                    echo '<td><button class="btn btn-primary btn-sm" onclick="alert(\'Cetak Surat ID: ' . $p['id'] . '\')"><i class="mdi mdi-printer"></i> Cetak</button></td>';
                    echo '</tr>';
                  }
                }
                if (!$foundApproved) {
                  echo '<tr><td colspan="6" class="text-center">Tidak ada permohonan yang sudah disetujui.</td></tr>';
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Arsip Surat -->
        <div class="tab-pane fade" id="arsip" role="tabpanel" aria-labelledby="arsip-tab">
          <div class="table-responsive">
            <table class="table table-bordered table-hover" style="font-size: 14px;">
              <thead class="table-dark">
                <tr>
                  <th>Nomor Surat</th>
                  <th>Nama Pemohon</th>
                  <th>Jenis Permohonan</th>
                  <th>Tanggal Cetak</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if (count($arsip_surat) > 0) {
                  foreach ($arsip_surat as $arsip) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($arsip['nomor_surat']) . '</td>';
                    echo '<td>' . htmlspecialchars($arsip['nama_pemohon']) . '</td>';
                    echo '<td>' . htmlspecialchars($arsip['jenis_permohonan']) . '</td>';
                    echo '<td>' . htmlspecialchars(date('d-m-Y', strtotime($arsip['tanggal_cetak']))) . '</td>';
                    echo '<td><button class="btn btn-success btn-sm" onclick="alert(\'Lihat detail arsip surat nomor: ' . htmlspecialchars($arsip['nomor_surat']) . '\')"><i class="mdi mdi-eye"></i> Lihat</button></td>';
                    echo '</tr>';
                  }
                } else {
                  echo '<tr><td colspan="5" class="text-center">Belum ada arsip surat yang tersedia.</td></tr>';
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<?php
include_once 'partials/footer.php';
?>
