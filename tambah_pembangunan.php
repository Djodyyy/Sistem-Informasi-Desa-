<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'partials/header.php';
include_once 'functions/function_pembangunan.php';
?>

<div class="container py-4">
  <h4 class="text-center mb-4">Kelola Informasi Pembangunan</h4>

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
    <div class="card-header bg-primary text-white">Tambah Pembangunan Baru</div>
    <div class="card-body">
      <form action="functions/function_pembangunan.php" method="POST" enctype="multipart/form-data">
        <div class="row g-3">
          <div class="col-md-4">
            <label for="judul" class="form-label">Judul</label>
            <input type="text" id="judul" name="judul" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label for="lokasi" class="form-label">Lokasi</label>
            <input type="text" id="lokasi" name="lokasi" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label for="tahun" class="form-label">Tahun</label>
            <input type="text" id="tahun" name="tahun" class="form-control" required pattern="\d{4}" title="Format tahun harus 4 digit angka">
          </div>
        </div>

        <div class="row g-3 mt-2">
          <div class="col-md-3">
            <label for="volume" class="form-label">Volume</label>
            <input type="text" id="volume" name="volume" class="form-control">
          </div>
          <div class="col-md-3">
            <label for="anggaran" class="form-label">Anggaran (Rp)</label>
            <input type="number" id="anggaran" name="anggaran" class="form-control">
          </div>
          <div class="col-md-3">
            <label for="sumber_dana" class="form-label">Sumber Dana</label>
            <input type="text" id="sumber_dana" name="sumber_dana" class="form-control">
          </div>
          <div class="col-md-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <input type="text" id="keterangan" name="keterangan" class="form-control">
          </div>
        </div>

        <div class="mt-3">
          <label for="foto" class="form-label">Upload Foto (JPG, JPEG, PNG)</label>
          <input type="file" id="foto" name="foto" class="form-control" accept=".jpg,.jpeg,.png" required>
        </div>

        <div class="mt-3 text-end">
          <button type="submit" name="add_pembangunan" class="btn btn-success">Tambah</button>
        </div>
      </form>
    </div>
  </div>

  <!-- List Data -->
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">Daftar Informasi Pembangunan</div>
    <div class="card-body table-responsive">
      <table class="table table-striped table-hover align-middle" style="font-size: 14px;">
        <thead class="table-dark">
          <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Lokasi</th>
            <th>Tahun</th>
            <th>Volume</th>
            <th>Anggaran</th>
            <th>Sumber Dana</th>
            <th>Keterangan</th>
            <th>Foto</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $dataList = getAllPembangunan();
          if ($dataList && count($dataList) > 0) {
            $no = 1;
            foreach ($dataList as $data) {
              $fotoUrl = "uploads/pembangunan/" . htmlspecialchars($data['foto'] ?? '');
              echo "<tr>
                      <td>{$no}</td>
                      <td>" . htmlspecialchars($data['judul'] ?? '') . "</td>
                      <td>" . htmlspecialchars($data['lokasi'] ?? '') . "</td>
                      <td>" . htmlspecialchars($data['tahun'] ?? '') . "</td>
                      <td>" . htmlspecialchars($data['volume'] ?? '') . "</td>
                      <td>Rp " . number_format($data['anggaran'] ?? 0, 0, ',', '.') . "</td>
                      <td>" . htmlspecialchars($data['sumber_dana'] ?? '') . "</td>
                      <td>" . htmlspecialchars($data['keterangan'] ?? '') . "</td>
                      <td>";
              if (!empty($data['foto']) && file_exists($fotoUrl)) {
                  echo "<a href='{$fotoUrl}' target='_blank'>Lihat Foto</a>";
              } else {
                  echo "Tidak ada foto";
              }
              echo "</td>
                      <td>
                        <button type='button' class='btn btn-sm btn-primary me-1' data-bs-toggle='modal' data-bs-target='#editModal{$data['id']}'>Edit</button>
                        <a href='functions/function_pembangunan.php?hapus={$data['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Yakin ingin menghapus data ini?')\">Hapus</a>
                      </td>
                    </tr>";

              // Modal Edit
              echo "
              <div class='modal fade' id='editModal{$data['id']}' tabindex='-1' aria-labelledby='editModalLabel{$data['id']}' aria-hidden='true'>
                <div class='modal-dialog'>
                  <div class='modal-content'>
                    <form action='functions/function_pembangunan.php' method='POST' enctype='multipart/form-data'>
                      <div class='modal-header'>
                        <h5 class='modal-title' id='editModalLabel{$data['id']}'>Edit Informasi Pembangunan</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                      </div>
                      <div class='modal-body'>
                        <input type='hidden' name='id' value='" . htmlspecialchars($data['id'] ?? '') . "'>
                        <div class='mb-2'>
                          <label class='form-label'>Judul</label>
                          <input type='text' name='judul' class='form-control' value='" . htmlspecialchars($data['judul'] ?? '') . "' required>
                        </div>
                        <div class='mb-2'>
                          <label class='form-label'>Lokasi</label>
                          <input type='text' name='lokasi' class='form-control' value='" . htmlspecialchars($data['lokasi'] ?? '') . "' required>
                        </div>
                        <div class='mb-2'>
                          <label class='form-label'>Tahun</label>
                          <input type='text' name='tahun' class='form-control' value='" . htmlspecialchars($data['tahun'] ?? '') . "' required pattern='\\d{4}'>
                        </div>
                        <div class='mb-2'>
                          <label class='form-label'>Volume</label>
                          <input type='text' name='volume' class='form-control' value='" . htmlspecialchars($data['volume'] ?? '') . "'>
                        </div>
                        <div class='mb-2'>
                          <label class='form-label'>Anggaran</label>
                          <input type='number' name='anggaran' class='form-control' value='" . htmlspecialchars($data['anggaran'] ?? '') . "'>
                        </div>
                        <div class='mb-2'>
                          <label class='form-label'>Sumber Dana</label>
                          <input type='text' name='sumber_dana' class='form-control' value='" . htmlspecialchars($data['sumber_dana'] ?? '') . "'>
                        </div>
                        <div class='mb-2'>
                          <label class='form-label'>Keterangan</label>
                          <input type='text' name='keterangan' class='form-control' value='" . htmlspecialchars($data['keterangan'] ?? '') . "'>
                        </div>
                        <div class='mb-2'>
                          <label class='form-label'>Foto (kosongkan jika tidak diubah)</label>
                          <input type='file' name='foto' class='form-control' accept='.jpg,.jpeg,.png'>
                        </div>
                      </div>
                      <div class='modal-footer'>
                        <button type='submit' name='edit_pembangunan' class='btn btn-primary'>Simpan</button>
                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Batal</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>";
              $no++;
            }
          } else {
            echo "<tr><td colspan='10' class='text-center'>Belum ada data.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include_once 'partials/footer.php'; ?>
