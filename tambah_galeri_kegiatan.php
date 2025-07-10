<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'functions/function_galeri_kegiatan.php';
$dataKegiatan = getAllKegiatan();
?>

<?php include 'partials/header.php'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="container py-4">
  <h4 class="text-center mb-4">Kelola Galeri & Kegiatan</h4>

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
    <div class="card-header bg-primary text-white">Tambah Kegiatan</div>
    <div class="card-body">
      <form method="POST" action="functions/function_galeri_kegiatan.php" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="judul" class="form-label">Judul</label>
          <input type="text" name="judul" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="deskripsi" class="form-label">Deskripsi</label>
          <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-3">
          <label for="tanggal" class="form-label">Tanggal Kegiatan</label>
          <input type="text" id="tanggal" name="tanggal" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="gambar" class="form-label">Upload Gambar (bisa lebih dari satu)</label>
          <input type="file" name="gambar[]" class="form-control" multiple accept="image/*">
        </div>
        <div class="text-end">
          <button type="submit" name="add_kegiatan" class="btn btn-success">Tambah</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Tabel Data -->
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">Daftar Kegiatan</div>
    <div class="card-body table-responsive">
      <table class="table table-striped table-hover align-middle" style="font-size: 14px;">
        <thead class="table-dark">
          <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Tanggal</th>
            <th>Deskripsi</th>
            <th>Gambar</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($dataKegiatan)): ?>
            <?php $no = 1; foreach ($dataKegiatan as $row): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['judul']) ?></td>
                <td><?= htmlspecialchars($row['tanggal_kegiatan']) ?></td>
                <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                <td>
                  <?php if (!empty($row['file_gambar'])): ?>
                    <?php foreach ($row['file_gambar'] as $foto): ?>
                      <a href="uploads/galeri/<?= htmlspecialchars($foto) ?>" target="_blank">📷</a>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <span class="text-muted">Tidak ada</span>
                  <?php endif; ?>
                </td>
                <td>
                  <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">Edit</button>
                  <a href="functions/function_galeri_kegiatan.php?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus kegiatan ini?')">Hapus</a>
                </td>
              </tr>

              <!-- Modal Edit -->
              <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form action="functions/function_galeri_kegiatan.php" method="POST" enctype="multipart/form-data">
                      <input type="hidden" name="id" value="<?= $row['id'] ?>">
                      <div class="modal-header">
                        <h5 class="modal-title">Edit Kegiatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <div class="mb-3">
                          <label class="form-label">Judul</label>
                          <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($row['judul']) ?>" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Deskripsi</label>
                          <textarea name="deskripsi" class="form-control" required><?= htmlspecialchars($row['deskripsi']) ?></textarea>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Tanggal Kegiatan</label>
                          <input type="text" name="tanggal" class="form-control" value="<?= htmlspecialchars($row['tanggal_kegiatan']) ?>" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Tambah Gambar Baru (opsional)</label>
                          <input type="file" name="gambar[]" class="form-control" multiple accept="image/*">
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" name="edit_kegiatan" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="6" class="text-center">Belum ada data kegiatan.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  flatpickr("#tanggal", {
    dateFormat: "Y-m-d"
  });
</script>

<?php include 'partials/footer.php'; ?>
