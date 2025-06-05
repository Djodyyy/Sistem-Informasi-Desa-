  <?php
  include_once 'partials/header.php';
  include_once 'functions/function_struktur.php';
  ?>

  <div class="container py-4">
    <h4 class="text-center mb-4">Kelola Struktur Aparatur Desa</h4>

    <!-- Form Tambah Struktur -->
    <div class="card mb-4 shadow-sm">
      <div class="card-header bg-primary text-white">
        Tambah Data Struktur Baru
      </div>
      <div class="card-body">
        <form action="functions/function_struktur.php" method="POST" enctype="multipart/form-data">
          <div class="row g-3">
            <div class="col-md-5">
              <label for="nama" class="form-label">Nama Aparatur</label>
              <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="col-md-5">
              <label for="jabatan" class="form-label">Jabatan</label>
              <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Masukkan jabatan" required>
            </div>
            <div class="col-md-2">
              <label for="foto" class="form-label">Foto (JPG/JPEG)</label>
              <input type="file" class="form-control" id="foto" name="foto" accept=".jpg,.jpeg" required>
            </div>
          </div>
          <div class="mt-3 text-end">
            <button type="submit" name="add_struktur" class="btn btn-success">Tambah</button>
          </div>
        </form>
      </div>
    </div>

    <!-- List Struktur yang sudah ada -->
    <div class="card shadow-sm">
      <div class="card-header bg-secondary text-white">
        Data Struktur Aparatur Desa
      </div>
      <div class="card-body table-responsive">
        <table class="table table-striped table-hover align-middle" style="font-size: 14px;">
          <thead class="table-dark">
            <tr>
              <th>No</th>
              <th>Foto</th>
              <th>Nama</th>
              <th>Jabatan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $strukturList = getAllStruktur();
            if (!empty($strukturList)) {
              $no = 1;
              foreach ($strukturList as $str) {
                echo "<tr>";
                echo "<td>{$no}</td>";
                echo "<td><img src='assets/img/aparatur/{$str['foto']}' alt='{$str['nama']}' style='width: 70px; height: 70px; object-fit: cover; border-radius: 8px;'></td>";
                echo "<td>{$str['nama']}</td>";
                echo "<td>{$str['jabatan']}</td>";
                echo "<td>
                        <a href='#' class='btn btn-sm btn-primary me-1' data-bs-toggle='modal' data-bs-target='#editStrukturModal{$str['id']}'>Edit</a>
                        <a href='functions/function_struktur.php?hapus={$str['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Yakin ingin menghapus data ini?')\">Hapus</a>
                      </td>";
                echo "</tr>";

                // Modal Edit
                echo "
                <div class='modal fade' id='editStrukturModal{$str['id']}' tabindex='-1' aria-labelledby='editStrukturLabel{$str['id']}' aria-hidden='true'>
                  <div class='modal-dialog modal-dialog-centered'>
                    <div class='modal-content'>
                      <div class='modal-header'>
                        <h5 class='modal-title' id='editStrukturLabel{$str['id']}'>Edit Struktur Aparatur</h5>
                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                      </div>
                      <form action='functions/function_struktur.php' method='POST' enctype='multipart/form-data'>
                        <div class='modal-body'>
                          <input type='hidden' name='id' value='{$str['id']}'>
                          <div class='mb-3'>
                            <label class='form-label'>Nama</label>
                            <input type='text' name='nama' class='form-control' value='{$str['nama']}' required>
                          </div>
                          <div class='mb-3'>
                            <label class='form-label'>Jabatan</label>
                            <input type='text' name='jabatan' class='form-control' value='{$str['jabatan']}' required>
                          </div>
                          <div class='mb-3'>
                            <label class='form-label'>Foto (Kosongkan jika tidak ingin ganti)</label>
                            <input type='file' name='foto' class='form-control' accept='.jpg,.jpeg'>
                          </div>
                        </div>
                        <div class='modal-footer'>
                          <button type='submit' name='edit_struktur' class='btn btn-primary'>Simpan Perubahan</button>
                          <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Batal</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                ";

                $no++;
              }
            } else {
              echo "<tr><td colspan='5' class='text-center'>Data struktur belum tersedia</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <?php include_once 'partials/footer.php'; ?>