<?php
include_once 'partials/header.php';

// Data dummy permohonan sesuai jenis yang kamu minta
$permohonans = [
    [
        'id' => 1,
        'nama_pemohon' => 'Andi Saputra',
        'tanggal' => '2025-06-01',
        'jenis_permohonan' => 'Surat Keterangan Domisili',
        'status' => 'pending'
    ],
    [
        'id' => 2,
        'nama_pemohon' => 'Siti Aminah',
        'tanggal' => '2025-06-02',
        'jenis_permohonan' => 'Surat Keterangan Tidak Mampu',
        'status' => 'approved'
    ],
    [
        'id' => 3,
        'nama_pemohon' => 'Budi Santoso',
        'tanggal' => '2025-06-03',
        'jenis_permohonan' => 'Surat Pengantar KTP/Kartu Keluarga',
        'status' => 'rejected'
    ],
    [
        'id' => 4,
        'nama_pemohon' => 'Rina Marlina',
        'tanggal' => '2025-06-04',
        'jenis_permohonan' => 'Izin Usaha Mikro',
        'status' => 'pending'
    ],
    [
        'id' => 5,
        'nama_pemohon' => 'Hadi Pratama',
        'tanggal' => '2025-06-05',
        'jenis_permohonan' => 'Permohonan Lainnya',
        'status' => 'approved'
    ],
];
?>

<div class="container-fluid my-4">
  <div class="card shadow-sm">
    <div class="card-body">
      <h4 class="text-center mb-3">DATA PERMOHONAN</h4>
      <hr>
      <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addPermohonanModal">
          <i class="mdi mdi-plus-circle-outline"></i> Tambah Permohonan
        </button>
      </div>

      <div class="table-responsive">
        <table id="permohonanTable" class="table table-striped table-hover table-bordered nowrap" style="width:100%; font-size: 14px;">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Nama Pemohon</th>
              <th>Tanggal Permohonan</th>
              <th>Jenis Permohonan</th>
              <th>Status</th>
              <th style="width:120px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($permohonans as $p) : ?>
              <tr>
                <td><?= htmlspecialchars($p['id']); ?></td>
                <td><?= htmlspecialchars($p['nama_pemohon']); ?></td>
                <td><?= htmlspecialchars(date('d-m-Y', strtotime($p['tanggal']))); ?></td>
                <td><?= htmlspecialchars($p['jenis_permohonan']); ?></td>
                <td>
                  <?php
                  $statusBadge = [
                    'pending' => 'badge bg-warning text-dark',
                    'approved' => 'badge bg-success',
                    'rejected' => 'badge bg-danger'
                  ];
                  $statusClass = $statusBadge[$p['status']] ?? 'badge bg-secondary';
                  echo "<span class=\"$statusClass text-capitalize\">{$p['status']}</span>";
                  ?>
                </td>
                <td>
                  <!-- Tombol Edit dan Hapus dimatikan dulu -->
                  <button class="btn btn-sm btn-primary" disabled title="Edit">
                    <i class="mdi mdi-pencil"></i>
                  </button>
                  <button class="btn btn-sm btn-danger" disabled title="Hapus">
                    <i class="mdi mdi-delete"></i>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal: Add Permohonan -->
  <div class="modal fade" id="addPermohonanModal" tabindex="-1" aria-labelledby="addPermohonanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <form action="#" method="POST" class="needs-validation" novalidate>
          <div class="modal-header">
            <h5 class="modal-title" id="addPermohonanLabel">Tambah Data Permohonan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="nama_pemohon" class="form-label">Nama Pemohon</label>
              <input type="text" class="form-control" id="nama_pemohon" name="nama_pemohon" required>
              <div class="invalid-feedback">Nama Pemohon wajib diisi.</div>
            </div>
            <div class="mb-3">
              <label for="tanggal" class="form-label">Tanggal Permohonan</label>
              <input type="date" class="form-control" id="tanggal" name="tanggal" required>
              <div class="invalid-feedback">Tanggal wajib diisi.</div>
            </div>
            <div class="mb-3">
              <label for="jenis_permohonan" class="form-label">Jenis Permohonan</label>
              <select class="form-select" id="jenis_permohonan" name="jenis_permohonan" required>
                <option value="" selected disabled>-- Pilih Jenis Permohonan --</option>
                <option value="Surat Keterangan Domisili">Surat Keterangan Domisili</option>
                <option value="Surat Keterangan Tidak Mampu">Surat Keterangan Tidak Mampu</option>
                <option value="Surat Pengantar KTP/Kartu Keluarga">Surat Pengantar KTP/Kartu Keluarga</option>
                <option value="Izin Usaha Mikro">Izin Usaha Mikro</option>
                <option value="Permohonan Lainnya">Permohonan Lainnya</option>
              </select>
              <div class="invalid-feedback">Jenis Permohonan wajib dipilih.</div>
            </div>
            <div class="mb-3">
              <label for="status" class="form-label">Status</label>
              <select class="form-select" id="status" name="status" required>
                <option value="pending" selected>Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
              </select>
              <div class="invalid-feedback">Status wajib dipilih.</div>
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
      $('#permohonanTable').DataTable({
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
