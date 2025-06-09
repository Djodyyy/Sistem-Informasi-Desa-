<?php
include_once 'partials/header.php';

// Data dummy jadwal pelayanan
$jadwals = [
    [
        'id' => 1,
        'hari' => 'Senin',
        'jam_mulai' => '08:00',
        'jam_selesai' => '12:00',
        'jenis_pelayanan' => 'Administrasi Umum'
    ],
    [
        'id' => 2,
        'hari' => 'Selasa',
        'jam_mulai' => '08:00',
        'jam_selesai' => '12:00',
        'jenis_pelayanan' => 'Pelayanan Surat'
    ],
    [
        'id' => 3,
        'hari' => 'Rabu',
        'jam_mulai' => '08:00',
        'jam_selesai' => '12:00',
        'jenis_pelayanan' => 'Konsultasi Warga'
    ],
    [
        'id' => 4,
        'hari' => 'Kamis',
        'jam_mulai' => '08:00',
        'jam_selesai' => '12:00',
        'jenis_pelayanan' => 'Pelayanan Sosial'
    ],
    [
        'id' => 5,
        'hari' => 'Jumat',
        'jam_mulai' => '08:00',
        'jam_selesai' => '11:00',
        'jenis_pelayanan' => 'Pelayanan Kependudukan'
    ],
];
?>

<div class="container-fluid my-4">
  <div class="card shadow-sm">
    <div class="card-body">
      <h4 class="text-center mb-3">JADWAL PELAYANAN</h4>
      <hr>
      <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addJadwalModal">
          <i class="mdi mdi-plus-circle-outline"></i> Tambah Jadwal
        </button>
      </div>

      <div class="table-responsive">
        <table id="jadwalTable" class="table table-striped table-hover table-bordered nowrap" style="width:100%; font-size: 14px;">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Hari</th>
              <th>Jam Mulai</th>
              <th>Jam Selesai</th>
              <th>Jenis Pelayanan</th>
              <th style="width:120px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($jadwals as $j) : ?>
              <tr>
                <td><?= htmlspecialchars($j['id']); ?></td>
                <td><?= htmlspecialchars($j['hari']); ?></td>
                <td><?= htmlspecialchars($j['jam_mulai']); ?></td>
                <td><?= htmlspecialchars($j['jam_selesai']); ?></td>
                <td><?= htmlspecialchars($j['jenis_pelayanan']); ?></td>
                <td>
                  <button class="btn btn-sm btn-primary" disabled title="Edit"><i class="mdi mdi-pencil"></i></button>
                  <button class="btn btn-sm btn-danger" disabled title="Hapus"><i class="mdi mdi-delete"></i></button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal: Tambah Jadwal -->
  <div class="modal fade" id="addJadwalModal" tabindex="-1" aria-labelledby="addJadwalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <form action="#" method="POST" class="needs-validation" novalidate>
          <div class="modal-header">
            <h5 class="modal-title" id="addJadwalLabel">Tambah Jadwal Pelayanan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="hari" class="form-label">Hari</label>
              <select class="form-select" id="hari" name="hari" required>
                <option value="" selected disabled>-- Pilih Hari --</option>
                <option value="Senin">Senin</option>
                <option value="Selasa">Selasa</option>
                <option value="Rabu">Rabu</option>
                <option value="Kamis">Kamis</option>
                <option value="Jumat">Jumat</option>
              </select>
              <div class="invalid-feedback">Hari wajib dipilih.</div>
            </div>
            <div class="mb-3">
              <label for="jam_mulai" class="form-label">Jam Mulai</label>
              <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" required>
              <div class="invalid-feedback">Jam mulai wajib diisi.</div>
            </div>
            <div class="mb-3">
              <label for="jam_selesai" class="form-label">Jam Selesai</label>
              <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" required>
              <div class="invalid-feedback">Jam selesai wajib diisi.</div>
            </div>
            <div class="mb-3">
              <label for="jenis_pelayanan" class="form-label">Jenis Pelayanan</label>
              <input type="text" class="form-control" id="jenis_pelayanan" name="jenis_pelayanan" required>
              <div class="invalid-feedback">Jenis pelayanan wajib diisi.</div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" disabled>Simpan (dummy)</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Script untuk validasi Bootstrap -->
<script>
  (() => {
    'use strict'
    const forms = document.querySelectorAll('.needs-validation')
    Array.from(forms).forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
  })()
</script>

<!-- Optional: Init DataTables -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (typeof $.fn.DataTable !== 'undefined') {
      $('#jadwalTable').DataTable({
        scrollX: true,
        lengthChange: false,
        pageLength: 10,
        language: {
          search: "Cari:",
          zeroRecords: "Tidak ada data yang cocok",
          info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
          infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
          paginate: {
            previous: "Sebelumnya",
            next: "Berikutnya"
          }
        }
      });
    }
  });
</script>

<?php
include_once 'partials/footer.php';
?>
