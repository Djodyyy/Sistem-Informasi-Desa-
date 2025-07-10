<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'functions/function_pembangunan.php';
$dataPembangunan = getAllPembangunan();
?>

<?php include 'partials/header.php'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="container py-4">
  <h4 class="text-center mb-4">Kelola Data Pembangunan</h4>

  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Data berhasil ditambahkan.</div>
  <?php elseif (isset($_GET['updated'])): ?>
    <div class="alert alert-info">Data berhasil diperbarui.</div>
  <?php elseif (isset($_GET['deleted'])): ?>
    <div class="alert alert-warning">Data berhasil dihapus.</div>
  <?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-danger">Terjadi kesalahan. Coba lagi.</div>
  <?php endif; ?>

  <!-- Form Tambah -->
  <div class="card mb-4 shadow-sm">
    <div class="card-header bg-primary text-white">Tambah Pembangunan</div>
    <div class="card-body">
      <form method="POST" action="functions/function_pembangunan.php" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="judul" class="form-label">Judul</label>
          <input type="text" name="judul" class="form-control" required>
        </div>
        <div class="row">
          <div class="col-md-3">
            <label for="bulan_awal" class="form-label">Periode Awal</label>
            <input type="text" id="bulan_awal" class="form-control" placeholder="Pilih bulan awal" required>
          </div>
          <div class="col-md-3">
            <label for="bulan_akhir" class="form-label">Periode Akhir</label>
            <input type="text" id="bulan_akhir" class="form-control" placeholder="Pilih bulan akhir" required>
          </div>
          <div class="col-md-6">
            <label for="bulan" class="form-label">Periode (Otomatis)</label>
            <input type="text" name="bulan" id="bulan" class="form-control bg-light" readonly required>
          </div>
          <div class="col-md-4 mt-3">
            <label for="lokasi" class="form-label">Lokasi</label>
            <input type="text" name="lokasi" class="form-control" required>
          </div>
          <div class="col-md-4 mt-3">
            <label for="volume" class="form-label">Volume</label>
            <input type="text" name="volume" class="form-control" required>
          </div>
          <div class="col-md-4 mt-3">
            <label for="sumber_dana" class="form-label">Sumber Dana</label>
            <input type="text" name="sumber_dana" class="form-control" required>
          </div>
          <div class="col-md-6 mt-3">
            <label for="anggaran" class="form-label">Anggaran</label>
            <input type="text" name="anggaran" class="form-control" required>
          </div>
          <div class="col-md-6 mt-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <input type="text" name="keterangan" class="form-control">
          </div>
        </div>
        <div class="mt-3">
          <label for="foto" class="form-label">Upload Dokumentasi (bisa banyak)</label>
          <input type="file" name="foto[]" class="form-control" multiple accept="image/*">
        </div>
        <div class="mt-3 text-end">
          <button type="submit" name="add_pembangunan" class="btn btn-success">Tambah</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Tabel Data -->
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">Daftar Pembangunan</div>
    <div class="card-body table-responsive">
      <table class="table table-striped table-hover align-middle" style="font-size: 14px;">
        <thead class="table-dark">
          <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Periode</th>
            <th>Lokasi</th>
            <th>Volume</th>
            <th>Sumber Dana</th>
            <th>Anggaran</th>
            <th>Keterangan</th>
            <th>Dokumentasi</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($dataPembangunan)): ?>
            <?php $no = 1; foreach ($dataPembangunan as $row): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['judul']) ?></td>
                <td><?= htmlspecialchars($row['bulan']) ?></td>
                <td><?= htmlspecialchars($row['lokasi']) ?></td>
                <td><?= htmlspecialchars($row['volume']) ?></td>
                <td><?= htmlspecialchars($row['sumber_dana']) ?></td>
                <td><?= number_format($row['anggaran']) ?></td>
                <td><?= htmlspecialchars($row['keterangan']) ?></td>
                <td>
                  <?php
                    $fotos = json_decode($row['foto'], true);
                    if (is_array($fotos)):
                      foreach ($fotos as $foto): ?>
                        <a href="uploads/pembangunan/<?= htmlspecialchars($foto) ?>" target="_blank">📷</a>
                      <?php endforeach;
                    else: ?>
                      <span class="text-muted">Tidak ada</span>
                  <?php endif; ?>
                </td>
                <td>
                  <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">Edit</button>
                  <a href="functions/function_pembangunan.php?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
                </td>
              </tr>

              <!-- Modal Edit -->
              <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <form action="functions/function_pembangunan.php" method="POST" enctype="multipart/form-data">
                      <input type="hidden" name="id" value="<?= $row['id'] ?>">
                      <div class="modal-header">
                        <h5 class="modal-title">Edit Data Pembangunan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <div class="row g-3">
                          <div class="col-md-6">
                            <label class="form-label">Judul</label>
                            <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($row['judul']) ?>" required>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Periode</label>
                            <input type="text" name="bulan" class="form-control" value="<?= htmlspecialchars($row['bulan']) ?>" required>
                          </div>
                          <div class="col-md-4">
                            <label class="form-label">Lokasi</label>
                            <input type="text" name="lokasi" class="form-control" value="<?= htmlspecialchars($row['lokasi']) ?>" required>
                          </div>
                          <div class="col-md-4">
                            <label class="form-label">Volume</label>
                            <input type="text" name="volume" class="form-control" value="<?= htmlspecialchars($row['volume']) ?>" required>
                          </div>
                          <div class="col-md-4">
                            <label class="form-label">Sumber Dana</label>
                            <input type="text" name="sumber_dana" class="form-control" value="<?= htmlspecialchars($row['sumber_dana']) ?>" required>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Anggaran</label>
                            <input type="text" name="anggaran" class="form-control" value="<?= htmlspecialchars($row['anggaran']) ?>" required>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Keterangan</label>
                            <input type="text" name="keterangan" class="form-control" value="<?= htmlspecialchars($row['keterangan']) ?>">
                          </div>
                          <div class="col-12">
                            <label class="form-label">Ganti Foto (opsional)</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" name="edit_pembangunan" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="10" class="text-center">Belum ada data pembangunan.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
flatpickr("#bulan_awal", {
  dateFormat: "F Y",
  onChange: updatePeriode
});
flatpickr("#bulan_akhir", {
  dateFormat: "F Y",
  onChange: updatePeriode
});
function updatePeriode() {
  const awal = document.getElementById("bulan_awal").value;
  const akhir = document.getElementById("bulan_akhir").value;
  const output = document.getElementById("bulan");
  output.value = awal && akhir ? `${awal} s/d ${akhir}` : '';
}
</script>

<?php include 'partials/footer.php'; ?>
