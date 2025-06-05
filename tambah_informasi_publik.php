<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'partials/header.php';
include_once 'functions/function_informasi_publik.php';
?>

<div class="container py-4">
  <h4 class="text-center mb-4">Kelola Informasi Publik</h4>

  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Data berhasil ditambahkan.</div>
  <?php elseif (isset($_GET['updated'])): ?>
    <div class="alert alert-info">Data berhasil diperbarui.</div>
  <?php elseif (isset($_GET['deleted'])): ?>
    <div class="alert alert-warning">Data berhasil dihapus.</div>
  <?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-danger">Terjadi kesalahan: <?= htmlspecialchars($_GET['error']) ?></div>
  <?php endif; ?>

  <!-- Form Tambah -->
  <div class="card mb-4 shadow-sm">
    <div class="card-header bg-primary text-white">Tambah Informasi Baru</div>
    <div class="card-body">
      <form action="functions/function_informasi_publik.php" method="POST" enctype="multipart/form-data">
        <div class="row g-3">
          <div class="col-md-4">
            <label for="judul" class="form-label">Judul</label>
            <input type="text" id="judul" name="judul" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label for="kategori" class="form-label">Kategori</label>
            <input type="text" id="kategori" name="kategori" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label for="tahun" class="form-label">Tahun</label>
            <input type="text" id="tahun" name="tahun" class="form-control" required pattern="\d{4}" title="Format tahun harus 4 digit angka">
          </div>
        </div>
        <div class="mt-3">
          <label for="file" class="form-label">Upload File (PDF, DOC, DOCX)</label>
          <input type="file" id="file" name="file" class="form-control" accept=".pdf,.doc,.docx" required>
        </div>
        <div class="mt-3 text-end">
          <button type="submit" name="add_informasi_publik" class="btn btn-success">Tambah</button>
        </div>
      </form>
    </div>
  </div>

  <!-- List Data -->
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">Daftar Informasi Publik</div>
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
          $dataList = getAllInformasiPublik();
          if ($dataList && $dataList->num_rows > 0) {
            $no = 1;
            while ($data = $dataList->fetch_assoc()) {
              $fileUrl = "uploads/informasi_publik/" . htmlspecialchars($data['file_path']);
              echo "<tr>
                      <td>{$no}</td>
                      <td>" . htmlspecialchars($data['judul']) . "</td>
                      <td>" . htmlspecialchars($data['kategori']) . "</td>
                      <td>{$data['tanggal_upload']}</td>
                      <td><a href='{$fileUrl}' target='_blank'>Lihat</a></td>
                      <td>
                        <button type='button' class='btn btn-sm btn-primary me-1' data-bs-toggle='modal' data-bs-target='#editModal{$data['id']}'>Edit</button>
                        <a href='functions/function_informasi_publik.php?hapus={$data['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Yakin ingin menghapus data ini?')\">Hapus</a>
                      </td>
                    </tr>";

              // Modal Edit
              echo "
              <div class='modal fade' id='editModal{$data['id']}' tabindex='-1' aria-labelledby='editModalLabel{$data['id']}' aria-hidden='true'>
                <div class='modal-dialog'>
                  <div class='modal-content'>
                    <form action='functions/function_informasi_publik.php' method='POST' enctype='multipart/form-data'>
                      <div class='modal-header'>
                        <h5 class='modal-title' id='editModalLabel{$data['id']}'>Edit Informasi Publik</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                      </div>
                      <div class='modal-body'>
                        <input type='hidden' name='id' value='{$data['id']}'>
                        <div class='mb-3'>
                          <label for='judulEdit{$data['id']}' class='form-label'>Judul</label>
                          <input type='text' id='judulEdit{$data['id']}' name='judul' class='form-control' value='" . htmlspecialchars($data['judul']) . "' required>
                        </div>
                        <div class='mb-3'>
                          <label for='kategoriEdit{$data['id']}' class='form-label'>Kategori</label>
                          <input type='text' id='kategoriEdit{$data['id']}' name='kategori' class='form-control' value='" . htmlspecialchars($data['kategori']) . "' required>
                        </div>
                        <div class='mb-3'>
                          <label for='tanggalUploadEdit{$data['id']}' class='form-label'>Tanggal Upload</label>
                          <input type='date' id='tanggalUploadEdit{$data['id']}' name='tanggal_upload' class='form-control' value='{$data['tanggal_upload']}' required>
                        </div>
                        <div class='mb-3'>
                          <label for='fileEdit{$data['id']}' class='form-label'>File (kosongkan jika tidak diubah)</label>
                          <input type='file' id='fileEdit{$data['id']}' name='file' class='form-control' accept='.pdf,.doc,.docx'>
                        </div>
                      </div>
                      <div class='modal-footer'>
                        <button type='submit' name='edit_informasi_publik' class='btn btn-primary'>Simpan</button>
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
