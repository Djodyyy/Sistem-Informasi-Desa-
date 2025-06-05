<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'partials/header.php';
include_once 'functions/function_hukum.php';
?>

<div class="container py-4">
  <h4 class="text-center mb-4">Kelola Produk Hukum</h4>

  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Data berhasil ditambahkan.</div>
  <?php elseif (isset($_GET['updated'])): ?>
    <div class="alert alert-info">Data berhasil diperbarui.</div>
  <?php elseif (isset($_GET['deleted'])): ?>
    <div class="alert alert-warning">Data berhasil dihapus.</div>
  <?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-danger">Terjadi kesalahan. Silakan coba lagi.</div>
  <?php endif; ?>

  <!-- Form Tambah Produk Hukum -->
  <div class="card mb-4 shadow-sm">
    <div class="card-header bg-primary text-white">Tambah Produk Hukum Baru</div>
    <div class="card-body">
      <form action="functions/function_hukum.php" method="POST" enctype="multipart/form-data">
        <div class="row g-3">
          <div class="col-md-4">
            <label for="judul" class="form-label">Judul</label>
            <input type="text" class="form-control" id="judul" name="judul" required>
          </div>
          <div class="col-md-4">
            <label for="kategori" class="form-label">Kategori</label>
            <input type="text" class="form-control" id="kategori" name="kategori" required>
          </div>
          <div class="col-md-4">
            <label for="tahun" class="form-label">Tahun</label>
            <input type="text" class="form-control" id="tahun" name="tahun" required pattern="\d{4}">
          </div>
        </div>
        <div class="mt-3">
          <label for="file" class="form-label">Upload File (PDF)</label>
          <input type="file" class="form-control" id="file" name="file" accept="application/pdf" required>
        </div>
        <div class="mt-3 text-end">
          <button type="submit" name="add_produk_hukum" class="btn btn-success">Tambah</button>
        </div>
      </form>
    </div>
  </div>

  <!-- List Produk Hukum -->
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">Daftar Produk Hukum</div>
    <div class="card-body table-responsive">
      <table class="table table-striped table-hover align-middle" style="font-size: 14px;">
        <thead class="table-dark">
          <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Kategori</th>
            <th>Tahun</th>
            <th>File</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $produkList = getAllProdukHukum();
          if (!empty($produkList)) {
            $no = 1;
            foreach ($produkList as $data) {
              echo "<tr>";
              echo "<td>{$no}</td>";
              echo "<td>" . htmlspecialchars($data['judul']) . "</td>";
              echo "<td>" . htmlspecialchars($data['kategori']) . "</td>";
              echo "<td>{$data['tahun']}</td>";
              echo "<td><a href='uploads/hukum/{$data['file']}' target='_blank'>Lihat</a></td>";
              echo "<td>
                      <a href='#' class='btn btn-sm btn-primary me-1' data-bs-toggle='modal' data-bs-target='#editHukumModal{$data['id_produk']}'>Edit</a>
                      <a href='functions/function_hukum.php?hapus={$data['id_produk']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Yakin ingin menghapus data ini?')\">Hapus</a>
                    </td>";
              echo "</tr>";

              // Modal Edit
              echo "
              <div class='modal fade' id='editHukumModal{$data['id_produk']}' tabindex='-1'>
                <div class='modal-dialog'>
                  <div class='modal-content'>
                    <form action='functions/function_hukum.php' method='POST' enctype='multipart/form-data'>
                      <div class='modal-header'>
                        <h5 class='modal-title'>Edit Produk Hukum</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                      </div>
                      <div class='modal-body'>
                        <input type='hidden' name='id_produk' value='{$data['id_produk']}'>
                        <div class='mb-3'>
                          <label class='form-label'>Judul</label>
                          <input type='text' name='judul' class='form-control' value='" . htmlspecialchars($data['judul']) . "' required>
                        </div>
                        <div class='mb-3'>
                          <label class='form-label'>Kategori</label>
                          <input type='text' name='kategori' class='form-control' value='" . htmlspecialchars($data['kategori']) . "' required>
                        </div>
                        <div class='mb-3'>
                          <label class='form-label'>Tahun</label>
                          <input type='text' name='tahun' class='form-control' value='{$data['tahun']}' required pattern='\\d{4}'>
                        </div>
                        <div class='mb-3'>
                          <label class='form-label'>File (kosongkan jika tidak diubah)</label>
                          <input type='file' name='file' class='form-control' accept='application/pdf'>
                        </div>
                      </div>
                      <div class='modal-footer'>
                        <button type='submit' name='edit_produk_hukum' class='btn btn-primary'>Simpan</button>
                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Batal</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>";
              $no++;
            }
          } else {
            echo "<tr><td colspan='6' class='text-center'>Belum ada data.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include_once 'partials/footer.php'; ?>
