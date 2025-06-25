  <?php
  include 'functions/auth_warga.php';
  include 'functions/function_pengajuan_surat.php';
  include 'partials/header_warga.php';
  ?>

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <h3 class="mb-4 text-center">Form Pengajuan Surat</h3>

        <?php if (isset($_SESSION['success'])): ?>
          <div class="alert alert-success">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
          </div>
        <?php endif; ?>

        <form action="proses_pengajuan.php" method="POST">
          <div class="mb-3">
            <label for="jenis_surat" class="form-label">Jenis Surat</label>
            <select name="jenis_surat" id="jenis_surat" class="form-select" required>
              <option value="">-- Pilih Jenis Surat --</option>
              <option value="surat_domisili">Surat Keterangan Domisili</option>
              <option value="surat_usaha">Surat Keterangan Usaha</option>
              <option value="sktm">Surat Keterangan Tidak Mampu</option>
              <option value="skck">Surat Pengantar SKCK</option>
              <option value="belum_menikah">Surat Keterangan Belum Menikah</option>
            </select>
          </div>

          <!-- Kontainer form tambahan berdasarkan jenis surat -->
          <div id="form_dinamis"></div>

          <div class="mb-3">
            <label for="keperluan" class="form-label">Keperluan</label>
            <textarea name="keperluan" id="keperluan" class="form-control" rows="3" required></textarea>
          </div>

          <button type="submit" class="btn btn-success w-100">Ajukan Surat</button>
        </form>
      </div>
    </div>
  </div>

  <script>
  document.getElementById('jenis_surat').addEventListener('change', function() {
    const jenis = this.value;
    const formContainer = document.getElementById('form_dinamis');
    formContainer.innerHTML = '';

    if (jenis === 'surat_usaha') {
      formContainer.innerHTML = `
        <div class="mb-3">
          <label for="nama_usaha" class="form-label">Nama Usaha</label>
          <input type="text" name="nama_usaha" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="alamat_usaha" class="form-label">Alamat Usaha</label>
          <input type="text" name="alamat_usaha" class="form-control" required>
        </div>
      `;
    } else if (jenis === 'sktm') {
      formContainer.innerHTML = `
        <div class="mb-3">
          <label for="alasan_sktm" class="form-label">Alasan Permohonan SKTM</label>
          <textarea name="alasan_sktm" class="form-control" required></textarea>
        </div>
      `;
    } else if (jenis === 'belum_menikah') {
      formContainer.innerHTML = `
        <div class="mb-3">
          <label for="tujuan_surat" class="form-label">Tujuan Surat</label>
          <input type="text" name="tujuan_surat" class="form-control" required>
        </div>
      `;
    }
  });
  </script>

  <?php include 'partials/footer_warga.php'; ?>