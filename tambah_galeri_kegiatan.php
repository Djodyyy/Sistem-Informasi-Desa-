<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once 'partials/header.php';
include_once 'functions/function_galeri_kegiatan.php';
$list = getGaleriKegiatan();
?>

<div class="container py-4">
  <h4 class="text-center mb-4">Kelola Galeri & Kegiatan</h4>

  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Data berhasil ditambahkan.</div>
  <?php elseif (isset($_GET['updated'])): ?>
    <div class="alert alert-info">Data berhasil diperbarui.</div>
  <?php elseif (isset($_GET['deleted'])): ?>
    <div class="alert alert-warning">Data berhasil dihapus.</div>
  <?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-danger">Terjadi kesalahan. Silakan coba lagi.</div>
  <?php endif; ?>

  <div class="card mb-4 shadow-sm">
    <div class="card-header bg-primary text-white">Tambah Galeri / Kegiatan Baru</div>
    <div class="card-body">
      <form action="functions/function_galeri_kegiatan.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="judul" class="form-label">Judul</label>
          <input type="text" class="form-control" id="judul" name="judul" required>
        </div>
        <div class="mb-3">
          <label for="deskripsi" class="form-label">Deskripsi</label>
          <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required></textarea>
        </div>
        <div class="mb-3">
          <label for="tanggal" class="form-label">Tanggal</label>
          <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="mb-3">
          <label for="gambar" class="form-label">Upload Gambar</label>
          <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" required>
        </div>
        <div class="text-end">
          <button type="submit" name="add_galeri" class="btn btn-success">Tambah</button>
        </div>
      </form>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">Daftar Galeri & Kegiatan</div>
    <div class="card-body table-responsive">
      <table class="table table-striped table-hover align-middle">
        <thead class="table-dark">
          <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Tanggal</th>
            <th>Gambar</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($list && mysqli_num_rows($list) > 0) {
            $no = 1;
            while ($item = mysqli_fetch_assoc($list)) {
              ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($item['judul']) ?></td>
                <td><?= date('d M Y', strtotime($item['tanggal_kegiatan'])) ?></td>
                <td>
                  <?php if (!empty($item['file_gambar'])): ?>
                    <img src="uploads/galeri/<?= htmlspecialchars($item['file_gambar']) ?>" style="width:100px;">
                  <?php else: ?>
                    -
                  <?php endif; ?>
                </td>
                <td>
                  <a href="#editGaleriModal<?= $item['id'] ?>" class="btn btn-sm btn-primary" data-bs-toggle="modal">Edit</a>
                  <a href="functions/function_galeri_kegiatan.php?hapus=<?= $item['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                </td>
              </tr>

              <!-- Modal Edit -->
              <div class="modal fade" id="editGaleriModal<?= $item['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form action="functions/function_galeri_kegiatan.php" method="POST" enctype="multipart/form-data">
                      <div class="modal-header">
                        <h5 class="modal-title">Edit Galeri / Kegiatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <input type="hidden" name="id_galeri" value="<?= $item['id'] ?>">
                        <div class="mb-3">
                          <label class="form-label">Judul</label>
                          <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($item['judul']) ?>" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Deskripsi</label>
                          <textarea name="deskripsi" class="form-control" rows="4" required><?= htmlspecialchars($item['deskripsi']) ?></textarea>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Tanggal</label>
                          <input type="date" name="tanggal" class="form-control" value="<?= $item['tanggal_kegiatan'] ?>" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Gambar Saat Ini</label><br>
                          <img src="uploads/galeri/<?= htmlspecialchars($item['file_gambar']) ?>" style="width:100px;">
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Ganti Gambar (opsional)</label>
                          <input type="file" name="gambar" class="form-control" accept="image/*">
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" name="edit_galeri" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <?php
            }
          } else {
            echo "<tr><td colspan='5' class='text-center'>Belum ada data.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include_once 'partials/footer.php'; ?>
