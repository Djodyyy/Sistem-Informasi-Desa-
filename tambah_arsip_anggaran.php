<?php
include_once 'partials/header.php';
include_once 'functions/function_arsip_anggaran.php';

// Ambil tahun dari query param untuk detail
$tahunDipilih = isset($_GET['tahun']) ? intval($_GET['tahun']) : null;
?>

<div class="container py-4">
  <h4 class="text-center mb-4">Kelola Arsip Pembukuan Anggaran</h4>

  <!-- Form Tambah Arsip -->
  <div class="card mb-4 shadow-sm">
    <div class="card-header bg-primary text-white">
      Tambah Arsip Pembukuan Baru
    </div>
    <div class="card-body">
      <form action="functions/function_arsip_anggaran.php" method="POST" enctype="multipart/form-data">
        <div class="row g-3">
          <div class="col-md-3">
            <label for="tahun" class="form-label">Tahun</label>
            <input type="text" class="form-control" id="tahun" name="tahun_anggaran" placeholder="Contoh: 2024" required pattern="\d{4}">
          </div>
          <div class="col-md-5">
            <label for="nama_dokumen" class="form-label">Nama Dokumen</label>
            <input type="text" class="form-control" id="nama_dokumen" name="nama_dokumen" placeholder="Masukkan nama dokumen" required>
          </div>
          <div class="col-md-4">
            <label for="file_dokumen" class="form-label">Upload File Scan</label>
            <input type="file" class="form-control" id="file_dokumen" name="file_dokumen" accept=".pdf,.jpg,.jpeg,.png" required>
          </div>
        </div>
        <div class="mt-3 text-end">
          <button type="submit" name="add_arsip" class="btn btn-success">Tambah</button>
        </div>
      </form>
    </div>
  </div>

  <?php if (!$tahunDipilih): ?>
  <!-- List Tahun Arsip -->
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white">
      Daftar Tahun Arsip Pembukuan
    </div>
    <div class="card-body">
      <?php
      $tahunList = getAllTahunArsip();
      if (!empty($tahunList)) {
          echo "<ul class='list-group'>";
          foreach ($tahunList as $tahun) {
              echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
              echo "<a href='?tahun=$tahun' class='text-decoration-none'>Tahun $tahun</a>";
              echo "<span class='badge bg-primary rounded-pill'>" . count(getArsipByTahun($tahun)) . "</span>";
              echo "</li>";
          }
          echo "</ul>";
      } else {
          echo "<p class='text-center'>Belum ada arsip yang diunggah</p>";
      }
      ?>
    </div>
  </div>

  <?php else: ?>
  <!-- List Arsip Per Tahun -->
  <div class="card shadow-sm">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <span>Daftar Arsip Tahun <?= htmlspecialchars($tahunDipilih) ?></span>
      <a href="tambah_arsip_anggaran.php" class="btn btn-sm btn-warning">Kembali ke Daftar Tahun</a>
    </div>
    <div class="card-body table-responsive">
      <table class="table table-striped table-hover align-middle" style="font-size: 14px;">
        <thead class="table-dark">
          <tr>
            <th>No</th>
            <th>Nama Dokumen</th>
            <th>Deskripsi</th>
            <th>File</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $arsipList = getArsipByTahun($tahunDipilih);
          if (!empty($arsipList)) {
            $no = 1;
            foreach ($arsipList as $arsip) {
              $filePath = "uploads/arsip_anggaran/" . htmlspecialchars($arsip['tahun_anggaran']) . "/" . htmlspecialchars($arsip['file_scan']);
              echo "<tr>";
              echo "<td>{$no}</td>";
              echo "<td>" . htmlspecialchars($arsip['nama_dokumen']) . "</td>";
              echo "<td>" . htmlspecialchars($arsip['deskripsi']) . "</td>";
              echo "<td>
                      <a href='$filePath' target='_blank' class='btn btn-sm btn-info me-1'>Lihat</a>
                      <a href='$filePath' download class='btn btn-sm btn-success'>Download</a>
                    </td>";
              echo "<td>
                      <button class='btn btn-sm btn-primary' data-bs-toggle='modal' data-bs-target='#editModal{$arsip['id']}'>Edit</button>
                      <a href='functions/function_arsip_anggaran.php?hapus={$arsip['id']}' onclick=\"return confirm('Hapus arsip ini?')\" class='btn btn-sm btn-danger'>Hapus</a>
                    </td>";
              echo "</tr>";

              // Modal Edit per arsip
              ?>
              <div class="modal fade" id="editModal<?= $arsip['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $arsip['id'] ?>" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <form action="functions/function_arsip_anggaran.php" method="POST" enctype="multipart/form-data">
                      <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel<?= $arsip['id'] ?>">Edit Arsip</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $arsip['id'] ?>">
                        <div class="mb-3">
                          <label for="tahun<?= $arsip['id'] ?>" class="form-label">Tahun</label>
                          <input type="text" class="form-control" id="tahun<?= $arsip['id'] ?>" name="tahun_anggaran" value="<?= htmlspecialchars($arsip['tahun_anggaran']) ?>" required pattern="\d{4}">
                        </div>
                        <div class="mb-3">
                          <label for="nama_dokumen<?= $arsip['id'] ?>" class="form-label">Nama Dokumen</label>
                          <input type="text" class="form-control" id="nama_dokumen<?= $arsip['id'] ?>" name="nama_dokumen" value="<?= htmlspecialchars($arsip['nama_dokumen']) ?>" required>
                        </div>
                        <div class="mb-3">
                          <label for="deskripsi<?= $arsip['id'] ?>" class="form-label">Deskripsi</label>
                          <textarea class="form-control" id="deskripsi<?= $arsip['id'] ?>" name="deskripsi" rows="3"><?= htmlspecialchars($arsip['deskripsi']) ?></textarea>
                        </div>
                        <div class="mb-3">
                          <label for="file_dokumen<?= $arsip['id'] ?>" class="form-label">Upload File Baru (Opsional)</label>
                          <input type="file" class="form-control" id="file_dokumen<?= $arsip['id'] ?>" name="file_dokumen" accept=".pdf,.jpg,.jpeg,.png">
                          <small class="text-muted">Biarkan kosong jika tidak ingin mengubah file</small>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="edit_arsip" class="btn btn-primary">Simpan Perubahan</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <?php
              $no++;
            }
          } else {
            echo "<tr><td colspan='5' class='text-center'>Tidak ada arsip untuk tahun ini.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

</div>

<?php include_once 'partials/footer.php'; ?>
