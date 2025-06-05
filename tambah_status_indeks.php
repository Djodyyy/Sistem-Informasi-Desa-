<?php
include_once 'partials/header.php';
include_once 'functions/function_status_indeks.php'; // sesuaikan path

// Ambil semua data status indeks
$statusIndeksList = getDataStatusIndeks();

// Cari semua tahun unik
$tahunList = [];
foreach ($statusIndeksList as $item) {
    if (!in_array($item['tahun'], $tahunList)) {
        $tahunList[] = $item['tahun'];
    }
}
sort($tahunList);

// Ambil tahun yang dipilih lewat GET, default tahun terakhir jika ada, atau kosong
$tahunTerpilih = isset($_GET['tahun']) ? $_GET['tahun'] : (end($tahunList) ?: '');

?>

<div class="container py-4">
  <h4 class="text-center mb-4">Kelola Status Indeks Desa</h4>

  <!-- Form Tambah Status Indeks -->
  <div class="card mb-4 shadow-sm">
    <div class="card-header bg-primary text-white">
      Tambah Data Status Indeks Baru
    </div>
    <div class="card-body">
      <!-- FORM TAMBAH -->
      <form action="functions/function_status_indeks.php" method="POST">
        <div class="row g-3">
          <div class="col-md-2">
            <label for="tahun" class="form-label">Tahun</label>
            <input type="text" class="form-control" id="tahun" name="tahun" placeholder="Masukkan tahun" required pattern="\d{4}" title="Masukkan tahun (4 digit)">
          </div>
          <div class="col-md-3">
            <label for="nilai" class="form-label">Nilai Indeks</label>
            <input type="number" step="0.01" class="form-control" id="nilai" name="nilai" placeholder="0.00" min="0" max="100" required>
          </div>
          <div class="col-md-7">
            <label for="keterangan" class="form-label">Keterangan (opsional)</label>
            <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Masukkan keterangan...">
          </div>
        </div>
        <div class="mt-3 text-end">
          <button type="submit" name="add_indeks" class="btn btn-success">Tambah</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Dropdown Pilih Tahun -->
  <div class="mb-3">
    <form method="GET" action="">
      <label for="tahunSelect" class="form-label fw-bold">Pilih Tahun</label>
      <select id="tahunSelect" name="tahun" class="form-select" onchange="this.form.submit()">
        <option value="">-- Pilih Tahun --</option>
        <?php foreach ($tahunList as $tahun): ?>
          <option value="<?= htmlspecialchars($tahun) ?>" <?= ($tahun == $tahunTerpilih) ? 'selected' : '' ?>><?= htmlspecialchars($tahun) ?></option>
        <?php endforeach; ?>
      </select>
    </form>
  </div>

  <!-- Tabel Data Status Indeks Tahun Terpilih -->
  <?php if ($tahunTerpilih !== ''): ?>
    <?php
    // Filter data hanya untuk tahun terpilih
    $dataFiltered = array_filter($statusIndeksList, fn($item) => $item['tahun'] == $tahunTerpilih);
    ?>
    <div class="card shadow-sm">
      <div class="card-header bg-secondary text-white">
        Data Status Indeks Tahun <?= htmlspecialchars($tahunTerpilih) ?>
      </div>
      <div class="card-body table-responsive">
        <table class="table table-striped table-hover align-middle" style="font-size: 14px;">
          <thead class="table-dark">
            <tr>
              <th>No</th>
              <th>Tahun</th>
              <th>Nilai Indeks</th>
              <th>Status</th>
              <th>Keterangan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($dataFiltered)): ?>
              <?php
              $no = 1;
              foreach ($dataFiltered as $item):
                $nilai = floatval($item['nilai']);
                $status = $item['indeks']; // sudah ada di DB
              ?>
                <tr>
                  <td><?= $no ?></td>
                  <td><?= htmlspecialchars($item['tahun']) ?></td>
                  <td><?= number_format($nilai, 2) ?></td>
                  <td><?= htmlspecialchars($status) ?></td>
                  <td><?= !empty($item['keterangan']) ? htmlspecialchars($item['keterangan']) : '-' ?></td>
                  <td>
                    <a href="#" class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editStatusIndeksModal<?= $item['id'] ?>">Edit</a>
                    <a href="functions/function_status_indeks.php?hapus=<?= $item['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                  </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="editStatusIndeksModal<?= $item['id'] ?>" tabindex="-1" aria-labelledby="editStatusIndeksLabel<?= $item['id'] ?>" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="editStatusIndeksLabel<?= $item['id'] ?>">Edit Status Indeks Desa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form action="functions/function_status_indeks.php" method="POST">
                        <div class="modal-body">
                          <input type="hidden" name="id" value="<?= $item['id'] ?>">
                          <div class="mb-3">
                            <label class="form-label">Tahun</label>
                            <input type="text" name="tahun" class="form-control" value="<?= htmlspecialchars($item['tahun']) ?>" required pattern="\d{4}" title="Masukkan tahun (4 digit)">
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Nilai Indeks</label>
                            <input type="number" step="0.01" name="nilai" class="form-control" value="<?= htmlspecialchars($item['nilai']) ?>" min="0" max="100" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <input type="text" name="keterangan" class="form-control" value="<?= htmlspecialchars($item['keterangan']) ?>">
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="submit" name="edit_status_indeks" class="btn btn-primary">Simpan Perubahan</button>
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

              <?php
              $no++;
              endforeach;
              ?>
            <?php else: ?>
              <tr><td colspan="6" class="text-center">Data status indeks belum tersedia untuk tahun ini.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php else: ?>
    <div class="alert alert-info text-center">
      Silakan pilih tahun untuk menampilkan data status indeks.
    </div>
  <?php endif; ?>

</div>

<?php include_once 'partials/footer.php'; ?>
