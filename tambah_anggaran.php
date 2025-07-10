<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'functions/function_anggaran.php';
$dataAnggaran = getAllAnggaran();
?>

<?php include 'partials/header.php'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="container py-4">
  <h4 class="text-center mb-4">Kelola Data Anggaran</h4>

  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Data berhasil ditambahkan.</div>
  <?php elseif (isset($_GET['updated'])): ?>
    <div class="alert alert-info">Data berhasil diperbarui.</div>
  <?php elseif (isset($_GET['deleted'])): ?>
    <div class="alert alert-warning">Data berhasil dihapus.</div>
  <?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-danger">Terjadi kesalahan. Coba lagi.</div>
  <?php endif; ?>

  <div class="card mb-4 shadow-sm">
    <div class="card-header bg-primary text-white">Tambah Anggaran</div>
    <div class="card-body">
      <form method="POST" action="functions/function_anggaran.php" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="deskripsi" class="form-label">Deskripsi</label>
          <input type="text" name="deskripsi" class="form-control" required>
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
            <label for="bulan" class="form-label">Periode (Akan terisi otomatis)</label>
            <input type="text" name="bulan" id="bulan" class="form-control bg-light" readonly required>
          </div>
          <div class="col-md-3 mt-3">
            <label for="anggaran" class="form-label">Anggaran</label>
            <input type="text" name="anggaran" class="form-control" required>
          </div>
          <div class="col-md-3 mt-3">
            <label for="realisasi" class="form-label">Realisasi</label>
            <input type="text" name="realisasi" class="form-control" required>
          </div>
        </div>
        <div class="mt-3">
          <label for="keterangan" class="form-label">Keterangan</label>
          <textarea name="keterangan" class="form-control"></textarea>
        </div>
        <div class="mt-3">
          <label for="dokumentasi_foto" class="form-label">Upload Dokumentasi (bisa banyak)</label>
          <input type="file" name="dokumentasi_foto[]" class="form-control" multiple accept="image/*">
        </div>
        <div class="mt-3 text-end">
          <button type="submit" name="add_anggaran" class="btn btn-success">Tambah</button>
        </div>
      </form>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">Daftar Anggaran</div>
    <div class="card-body table-responsive">
      <table class="table table-striped table-hover align-middle" style="font-size: 14px;">
        <thead class="table-dark">
          <tr>
            <th>No</th>
            <th>Deskripsi</th>
            <th>Periode</th>
            <th>Anggaran</th>
            <th>Realisasi</th>
            <th>Keterangan</th>
            <th>Dokumentasi</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($dataAnggaran)): ?>
            <?php $no = 1; foreach ($dataAnggaran as $row): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                <td><?= htmlspecialchars($row['bulan']) ?></td>
                <td><?= number_format($row['anggaran']) ?></td>
                <td><?= number_format($row['realisasi']) ?></td>
                <td><?= htmlspecialchars($row['keterangan']) ?></td>
                <td>
                  <?php if (!empty($row['file_foto']) && is_array($row['file_foto'])):
                    foreach ($row['file_foto'] as $foto): ?>
                      <a href="uploads/anggaran/<?= htmlspecialchars($foto) ?>" target="_blank">📷</a>
                    <?php endforeach;
                  else: ?>
                    <span class="text-muted">Tidak ada</span>
                  <?php endif; ?>
                </td>
                <td>
                  <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id_anggaran'] ?>">Edit</button>
                  <a href="functions/function_anggaran.php?hapus=<?= $row['id_anggaran'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
                </td>
              </tr>
              <div class="modal fade" id="editModal<?= $row['id_anggaran'] ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                  <form method="POST" action="functions/function_anggaran.php" enctype="multipart/form-data">
                    <input type="hidden" name="id_anggaran" value="<?= $row['id_anggaran'] ?>">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Edit Anggaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body row g-3">
                        <div class="col-md-6">
                          <label>Deskripsi</label>
                          <input type="text" name="deskripsi" class="form-control" value="<?= htmlspecialchars($row['deskripsi']) ?>">
                        </div>
                        <div class="col-md-6">
                          <label>Periode (otomatis)</label>
                          <div class="row g-2">
                            <div class="col-md-6">
                              <input type="text" id="edit_bulan_awal<?= $row['id_anggaran'] ?>" class="form-control" placeholder="Bulan awal">
                            </div>
                            <div class="col-md-6">
                              <input type="text" id="edit_bulan_akhir<?= $row['id_anggaran'] ?>" class="form-control" placeholder="Bulan akhir">
                            </div>
                            <div class="col-md-12 mt-2">
                              <input type="text" name="bulan" id="edit_bulan<?= $row['id_anggaran'] ?>" class="form-control bg-light" readonly value="<?= htmlspecialchars($row['bulan']) ?>">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <label>Anggaran</label>
                          <input type="text" name="anggaran" class="form-control" value="<?= $row['anggaran'] ?>">
                        </div>
                        <div class="col-md-4">
                          <label>Realisasi</label>
                          <input type="text" name="realisasi" class="form-control" value="<?= $row['realisasi'] ?>">
                        </div>
                        <div class="col-md-4">
                          <label>Keterangan</label>
                          <input type="text" name="keterangan" class="form-control" value="<?= htmlspecialchars($row['keterangan']) ?>">
                        </div>
                        <div class="col-md-12">
                          <label>Upload Foto Baru (jika ada)</label>
                          <input type="file" name="dokumentasi_foto[]" class="form-control" multiple accept="image/*">
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" name="edit_anggaran" class="btn btn-primary">Simpan Perubahan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <script>
              flatpickr("#edit_bulan_awal<?= $row['id_anggaran'] ?>", {
                dateFormat: "F Y",
                onChange: () => updateEdit<?= $row['id_anggaran'] ?>()
              });
              flatpickr("#edit_bulan_akhir<?= $row['id_anggaran'] ?>", {
                dateFormat: "F Y",
                onChange: () => updateEdit<?= $row['id_anggaran'] ?>()
              });
              function updateEdit<?= $row['id_anggaran'] ?>() {
                const awal = document.getElementById('edit_bulan_awal<?= $row['id_anggaran'] ?>').value;
                const akhir = document.getElementById('edit_bulan_akhir<?= $row['id_anggaran'] ?>').value;
                document.getElementById('edit_bulan<?= $row['id_anggaran'] ?>').value = awal && akhir ? `${awal} s/d ${akhir}` : '';
              }
              </script>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="9" class="text-center">Belum ada data anggaran.</td></tr>
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
  document.getElementById("bulan").value = awal && akhir ? `${awal} s/d ${akhir}` : '';
}
</script>

<?php include 'partials/footer.php'; ?>