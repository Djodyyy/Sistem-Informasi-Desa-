<?php
include_once 'partials/header.php';
include_once 'functions/function_status_indeks.php';

// Proses request jika ada POST/GET
prosesRequestStatusIndeks();

// Ambil data tahun untuk dropdown filter dan data default
$tahunFilter = $_GET['tahun'] ?? null;
$tahunList = getAllTahunIndeks();
$dataIndeks = $tahunFilter ? getIndeksByTahun($tahunFilter) : (count($tahunList) > 0 ? getIndeksByTahun($tahunList[0]) : []);
?>

<div class="container py-4">
  <h4 class="text-center mb-4">Kelola Status Indeks Desa</h4>

  <!-- Form Tambah Status Indeks -->
  <div class="card mb-4 shadow-sm">
    <div class="card-header bg-primary text-white">
      Tambah Data Status Indeks Baru
    </div>
    <div class="card-body">
      <form action="functions/function_status_indeks.php" method="POST">
        <div class="row g-3">
          <div class="col-md-2">
            <label for="tahun" class="form-label">Tahun</label>
            <input type="text" class="form-control" id="tahun" name="tahun" placeholder="Masukkan tahun" required pattern="\d{4}" title="Masukkan tahun (4 digit)">
          </div>
          <div class="col-md-2">
            <label for="nilai" class="form-label">Nilai</label>
            <input type="number" step="0.01" class="form-control" id="nilai" name="nilai" placeholder="0" min="0" max="100" required>
          </div>
          <div class="col-md-8">
            <label for="keterangan" class="form-label">Keterangan (opsional)</label>
            <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Masukkan keterangan...">
          </div>
        </div>
        <div class="mt-3 text-end">
          <button type="submit" name="add_indeks" class="btn btn-success">Tambah</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Filter Tahun -->
  <div class="mb-3">
    <form method="GET" action="">
      <label for="tahunFilter" class="form-label">Filter Tahun:</label>
      <select id="tahunFilter" name="tahun" class="form-select" onchange="this.form.submit()">
        <option value="">-- Pilih Tahun --</option>
        <?php foreach ($tahunList as $tahunOpt): ?>
          <option value="<?= htmlspecialchars($tahunOpt) ?>" <?= $tahunOpt == $tahunFilter ? 'selected' : '' ?>><?= htmlspecialchars($tahunOpt) ?></option>
        <?php endforeach; ?>
      </select>
    </form>
  </div>

  <!-- List Status Indeks -->
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      Data Status Indeks Desa <?= $tahunFilter ? "Tahun $tahunFilter" : "" ?>
    </div>
    <div class="card-body table-responsive">
      <table class="table table-striped table-hover align-middle" style="font-size: 14px;">
        <thead class="table-dark">
          <tr>
            <th>No</th>
            <th>Tahun</th>
            <th>Nilai</th>
            <th>Indeks</th>
            <th>Keterangan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (!empty($dataIndeks)) {
            $no = 1;
            foreach ($dataIndeks as $item) {
              echo "<tr>";
              echo "<td>{$no}</td>";
              echo "<td>" . htmlspecialchars($item['tahun']) . "</td>";
              echo "<td>" . htmlspecialchars($item['nilai']) . "</td>";
              echo "<td>" . htmlspecialchars($item['indeks']) . "</td>";
              echo "<td>" . (!empty($item['keterangan']) ? htmlspecialchars($item['keterangan']) : '-') . "</td>";
              echo "<td>
                      <a href='#' class='btn btn-sm btn-primary me-1' data-bs-toggle='modal' data-bs-target='#editIndeksModal{$item['id']}'>Edit</a>
                      <a href='?hapus={$item['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Yakin ingin menghapus data ini?')\">Hapus</a>
                    </td>";
              echo "</tr>";

              // Modal Edit
              echo "
              <div class='modal fade' id='editIndeksModal{$item['id']}' tabindex='-1' aria-labelledby='editIndeksLabel{$item['id']}' aria-hidden='true'>
                <div class='modal-dialog modal-dialog-centered'>
                  <div class='modal-content'>
                    <div class='modal-header'>
                      <h5 class='modal-title' id='editIndeksLabel{$item['id']}'>Edit Status Indeks</h5>
                      <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                    </div>
                    <form action='functions/function_status_indeks.php' method='POST'>
                      <div class='modal-body'>
                        <input type='hidden' name='id' value='{$item['id']}'>
                        <div class='mb-3'>
                          <label class='form-label'>Tahun</label>
                          <input type='text' name='tahun' class='form-control' value='" . htmlspecialchars($item['tahun']) . "' required pattern='\\d{4}' title='Masukkan tahun (4 digit)'>
                        </div>
                        <div class='mb-3'>
                          <label class='form-label'>Nilai</label>
                          <input type='number' step='0.01' name='nilai' class='form-control' value='" . htmlspecialchars($item['nilai']) . "' min='0' max='100' required>
                        </div>
                        <div class='mb-3'>
                          <label class='form-label'>Keterangan</label>
                          <input type='text' name='keterangan' class='form-control' value='" . htmlspecialchars($item['keterangan']) . "'>
                        </div>
                      </div>
                      <div class='modal-footer'>
                        <button type='submit' name='edit_indeks' class='btn btn-primary'>Simpan Perubahan</button>
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
            echo "<tr><td colspan='6' class='text-center'>Data status indeks belum tersedia</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include_once 'partials/footer.php'; ?>
