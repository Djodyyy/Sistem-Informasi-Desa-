<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'partials/header.php';
include_once 'functions/function_berita_artikel.php';
?>

<div class="container py-4">
  <h4 class="text-center mb-4">Kelola Berita & Artikel</h4>

  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Berita berhasil ditambahkan.</div>
  <?php elseif (isset($_GET['updated'])): ?>
    <div class="alert alert-info">Berita berhasil diperbarui.</div>
  <?php elseif (isset($_GET['deleted'])): ?>
    <div class="alert alert-warning">Berita berhasil dihapus.</div>
  <?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-danger">Terjadi kesalahan. Silakan coba lagi.</div>
  <?php endif; ?>

  <!-- Form Tambah Berita -->
  <div class="card mb-4 shadow-sm">
    <div class="card-header bg-primary text-white">
      Tambah Berita / Artikel Baru
    </div>
    <div class="card-body">
      <form action="functions/function_berita_artikel.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="judul" class="form-label">Judul</label>
          <input type="text" class="form-control" id="judul" name="judul" required>
        </div>
        <div class="mb-3">
          <label for="isi" class="form-label">Isi Berita / Artikel</label>
          <textarea class="form-control" id="isi" name="isi" rows="4" required></textarea>
        </div>
        <div class="mb-3">
          <label for="tanggal" class="form-label">Tanggal</label>
          <input type="date" class="form-control" id="tanggal" name="tanggal" required value="<?= date('Y-m-d') ?>">
        </div>
        <div class="mb-3">
          <label for="gambar" class="form-label">Upload Gambar (opsional)</label>
          <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
        </div>
        <div class="text-end">
          <button type="submit" name="add_berita" class="btn btn-success">Tambah</button>
        </div>
      </form>
    </div>
  </div>

  <!-- List Berita -->
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      Daftar Berita & Artikel
    </div>
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
          $beritaList = getAllBerita();
          if (!empty($beritaList)) {
            $no = 1;
            foreach ($beritaList as $berita) {
              echo "<tr>";
              echo "<td>{$no}</td>";
              echo "<td>" . htmlspecialchars($berita['judul']) . "</td>";
              echo "<td>" . date('d M Y', strtotime($berita['tanggal'])) . "</td>";
              echo "<td>";
              if (!empty($berita['gambar'])) {
                echo "<img src='uploads/" . htmlspecialchars($berita['gambar']) . "' style='width:100px;height:auto;'>";
              } else {
                echo "-";
              }
              echo "</td>";
              echo "<td>
                      <a href='#editBeritaModal{$berita['id_berita']}' class='btn btn-sm btn-primary me-1' data-bs-toggle='modal'>Edit</a>
                      <a href='functions/function_berita_artikel.php?hapus=" . urlencode($berita['id_berita']) . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Yakin ingin menghapus berita ini?')\">Hapus</a>
                    </td>";
              echo "</tr>";
              $no++;
            }
          } else {
            echo "<tr><td colspan='5' class='text-center'>Belum ada data berita.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modals Edit Berita -->
  <?php if (!empty($beritaList)) : ?>
    <?php foreach ($beritaList as $berita) : ?>
      <div class="modal fade" id="editBeritaModal<?= $berita['id_berita'] ?>" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="functions/function_berita_artikel.php" method="POST" enctype="multipart/form-data">
              <div class="modal-header">
                <h5 class="modal-title">Edit Berita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <input type="hidden" name="id_berita" value="<?= $berita['id_berita'] ?>">
                <div class="mb-3">
                  <label class="form-label">Judul</label>
                  <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($berita['judul']) ?>" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Isi</label>
                  <textarea name="isi" class="form-control" rows="4" required><?= htmlspecialchars($berita['isi']) ?></textarea>
                </div>
                <div class="mb-3">
                  <label class="form-label">Tanggal</label>
                  <input type="date" name="tanggal" class="form-control" value="<?= htmlspecialchars($berita['tanggal']) ?>" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Gambar (kosongkan jika tidak diubah)</label>
                  <input type="file" name="gambar" class="form-control" accept="image/*">
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" name="edit_berita" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php include_once 'partials/footer.php'; ?>
