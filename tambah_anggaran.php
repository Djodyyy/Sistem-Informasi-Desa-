<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'functions/function_anggaran.php';
$dataAnggaran = getAllAnggaran();
?>

<?php include 'partials/header.php'; ?>

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

  <!-- Form Tambah Anggaran -->
  <div class="card mb-4 shadow-sm">
    <div class="card-header bg-primary text-white">Tambah Anggaran</div>
    <div class="card-body">
      <form method="POST" action="functions/function_anggaran.php" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="deskripsi" class="form-label">Deskripsi</label>
          <input type="text" name="deskripsi" class="form-control" required>
        </div>
        <div class="row">
          <div class="col-md-4">
            <label for="tahun" class="form-label">Tahun</label>
            <input type="text" name="tahun" class="form-control" required pattern="\d{4}">
          </div>
          <div class="col-md-4">
            <label for="anggaran" class="form-label">Anggaran</label>
            <input type="text" name="anggaran" class="form-control" required>
          </div>
          <div class="col-md-4">
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

  <!-- Tabel Anggaran -->
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">Daftar Anggaran</div>
    <div class="card-body table-responsive">
      <table class="table table-striped table-hover align-middle" style="font-size: 14px;">
        <thead class="table-dark">
          <tr>
            <th>No</th>
            <th>Deskripsi</th>
            <th>Tahun</th>
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
                <td><?= htmlspecialchars($row['tahun']) ?></td>
                <td><?= number_format($row['anggaran']) ?></td>
                <td><?= number_format($row['realisasi']) ?></td>
                <td><?= htmlspecialchars($row['keterangan']) ?></td>
                <td>
                  <?php foreach ($row['dokumentasi'] as $foto): ?>
                    <a href="uploads/anggaran/<?= $foto['file_foto'] ?>" target="_blank">📷</a>
                  <?php endforeach; ?>
                </td>
                <td>
                  <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id_anggaran'] ?>">Edit</button>
                  <a href="functions/function_anggaran.php?hapus=<?= $row['id_anggaran'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="8" class="text-center">Belum ada data anggaran.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Edit: ditempatkan di luar tabel -->
<?php if (!empty($dataAnggaran)): ?>
  <?php foreach ($dataAnggaran as $row): ?>
    <div class="modal fade" id="editModal<?= $row['id_anggaran'] ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="functions/function_anggaran.php" enctype="multipart/form-data" class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Edit Anggaran</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id_anggaran" value="<?= $row['id_anggaran'] ?>">
            <div class="mb-3">
              <label class="form-label">Deskripsi</label>
              <input type="text" name="deskripsi" class="form-control" value="<?= htmlspecialchars($row['deskripsi']) ?>" required>
            </div>
            <div class="row">
              <div class="col-md-4">
                <label class="form-label">Tahun</label>
                <input type="text" name="tahun" class="form-control" value="<?= htmlspecialchars($row['tahun']) ?>" required>
              </div>
              <div class="col-md-4">
                <label class="form-label">Anggaran</label>
                <input type="text" name="anggaran" class="form-control" value="<?= $row['anggaran'] ?>">
              </div>
              <div class="col-md-4">
                <label class="form-label">Realisasi</label>
                <input type="text" name="realisasi" class="form-control" value="<?= $row['realisasi'] ?>">
              </div>
            </div>
            <div class="mt-3">
              <label class="form-label">Keterangan</label>
              <textarea name="keterangan" class="form-control"><?= htmlspecialchars($row['keterangan']) ?></textarea>
            </div>
            <div class="mt-3">
              <label class="form-label">Tambah Dokumentasi Baru</label>
              <input type="file" name="dokumentasi_foto[]" class="form-control" multiple>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="edit_anggaran" class="btn btn-success">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<?php include 'partials/footer.php'; ?>
