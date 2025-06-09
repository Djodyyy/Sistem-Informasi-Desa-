<?php
include_once 'partials/header.php';

// Dummy data pengaduan masuk
$pengaduan_masuk = [
  ['id' => 1, 'nama' => 'Budi Santoso', 'email' => 'budi@mail.com', 'isi' => 'Jalan rusak di RT 03 RW 02.', 'status' => 'baru', 'tanggal' => '2025-06-07 09:15'],
  ['id' => 2, 'nama' => 'Siti Aminah', 'email' => 'siti@mail.com', 'isi' => 'Air PDAM sering mati.', 'status' => 'diproses', 'tanggal' => '2025-06-06 15:30'],
];

// Dummy data pengaduan selesai
$pengaduan_selesai = [
  ['id' => 3, 'nama' => 'Agus Wijaya', 'email' => 'agus@mail.com', 'isi' => 'Lampu jalan padam.', 'status' => 'selesai', 'tanggal' => '2025-06-05 10:00'],
];
?>

<div class="container-fluid my-4">
  <div class="card shadow-sm">
    <div class="card-body">
      <h4 class="text-center mb-4">PENGELOLAAN PENGADUAN WARGA</h4>

      <!-- Tab Navigation -->
      <ul class="nav nav-tabs" id="pengaduanTab" role="tablist">
        <li class="nav-item">
          <button class="nav-link active" id="masuk-tab" data-bs-toggle="tab" data-bs-target="#masuk" type="button" role="tab">Pengaduan Masuk</button>
        </li>
        <li class="nav-item">
          <button class="nav-link" id="riwayat-tab" data-bs-toggle="tab" data-bs-target="#riwayat" type="button" role="tab">Riwayat Pengaduan</button>
        </li>
      </ul>

      <!-- Tab Content -->
      <div class="tab-content mt-3" id="pengaduanTabContent">
        <!-- Tab 1: Pengaduan Masuk -->
        <div class="tab-pane fade show active" id="masuk" role="tabpanel">
          <div class="table-responsive">
            <table class="table table-bordered table-hover" style="font-size: 14px;">
              <thead class="table-dark">
                <tr>
                  <th>ID</th>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Isi Pengaduan</th>
                  <th>Status</th>
                  <th>Tanggal</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if (count($pengaduan_masuk) > 0): ?>
                  <?php foreach ($pengaduan_masuk as $p): ?>
                    <tr>
                      <td><?= htmlspecialchars($p['id']) ?></td>
                      <td><?= htmlspecialchars($p['nama']) ?></td>
                      <td><?= htmlspecialchars($p['email']) ?></td>
                      <td><?= htmlspecialchars($p['isi']) ?></td>
                      <td><span class="badge bg-primary text-capitalize"><?= htmlspecialchars($p['status']) ?></span></td>
                      <td><?= date('d-m-Y H:i', strtotime($p['tanggal'])) ?></td>
                      <td>
                        <button class="btn btn-sm btn-success" onclick="alert('Tandai selesai ID: <?= $p['id'] ?>')">Tandai Selesai</button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="7" class="text-center">Belum ada pengaduan baru.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Tab 2: Riwayat Pengaduan -->
        <div class="tab-pane fade" id="riwayat" role="tabpanel">
          <div class="table-responsive">
            <table class="table table-bordered table-hover" style="font-size: 14px;">
              <thead class="table-dark">
                <tr>
                  <th>ID</th>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Isi Pengaduan</th>
                  <th>Status</th>
                  <th>Tanggal</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if (count($pengaduan_selesai) > 0): ?>
                  <?php foreach ($pengaduan_selesai as $p): ?>
                    <tr>
                      <td><?= htmlspecialchars($p['id']) ?></td>
                      <td><?= htmlspecialchars($p['nama']) ?></td>
                      <td><?= htmlspecialchars($p['email']) ?></td>
                      <td><?= htmlspecialchars($p['isi']) ?></td>
                      <td><span class="badge bg-success text-capitalize"><?= htmlspecialchars($p['status']) ?></span></td>
                      <td><?= date('d-m-Y H:i', strtotime($p['tanggal'])) ?></td>
                      <td>
                        <button class="btn btn-sm btn-info" onclick="alert('Lihat detail ID: <?= $p['id'] ?>')">Lihat</button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="7" class="text-center">Belum ada riwayat pengaduan selesai.</td></tr>
                <?php endif; ?>
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
