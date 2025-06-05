<?php
include_once 'partials/header.php';
require 'functions/function_user.php';
?>
<div class="container-fluid">
  <div class="row">
    <div>
      <div class="card">
        <div class="card-body">
          <h4 class="header-title text-center">DATA USER</h4>
          <hr>
          <div class="row mb-2">
            <div class="col-sm-12">
              <div class="text-sm-end">
                <button class="btn btn-sm btn-success mb-2" data-bs-toggle="modal" data-bs-target="#add-user"><i class="mdi mdi-plus-circle-outline"></i> Create New</button>
              </div>
            </div>
          </div>

          <div class="table-responsive-sm">
            <table style="font-size: 12px;" id="scroll-horizontal-datatable" class="table table-sm w-100 nowrap">
              <thead class="table-dark">
                <tr>
                  <th>Code User</th> <!-- Kolom baru -->
                  <th>Nama</th>
                  <th width="300px">Username</th>
                  <th width="100px">Role</th>
                  <th width="80px">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php $all = getData(); ?>
                <?php foreach ($all as $data) { ?>
                  <tr>
                    <td><?= $data['code_user'] ?></td> <!-- Tampilkan Code User -->
                    <td><?= $data['name'] ?></td>
                    <td><?= $data['username'] ?></td>
                    <td>
                      <?php
                      $roles = [
                        1 => 'Admin',
                        2 => 'Kepala Desa',
                        3 => 'Sekretaris Desa',
                        4 => 'Kaur Umum',
                        5 => 'Kaur Keuangan',
                        6 => 'Kaur Perencanaan',
                        7 => 'Kasi Pemerintahan',
                        8 => 'Kasi Kesejahteraan',
                        9 => 'Kasi Pelayanan',
                        10 => 'Operator Sistem'
                      ];
                      echo $roles[$data['role_id']] ?? 'Unknown';
                      ?>
                    </td>
                    <td class="table-action">
                      <a href="edit_user.php?code_user=<?= $data['code_user']; ?>" class="action-icon">
                        <i class="mdi mdi-pencil text-primary"></i>
                      </a>
                      <a href="functions/function_user.php?hapus=<?= $data['code_user']; ?>" class="action-icon" onclick="return confirm('Yakin hapus user ini?')">
                        <i class="mdi mdi-delete text-danger"></i>
                      </a>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Modal: Add User -->
      <div class="modal fade" id="add-user" tabindex="-1" aria-labelledby="NewUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Create New Data</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form action="functions/function_user.php" method="POST" class="p-2">
                <?php $genCode = 'USR' . rand(1000, 9999); ?>

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Code User</label>
                    <input type="text" name="code_user" id="code_user" class="form-control" readonly required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" required>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                      <input type="password" name="password" id="password" class="form-control" required>
                      <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="mdi mdi-eye-off" id="eyeIcon"></i>
                      </button>
                    </div>
                  </div>

                </div>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Role</label>
                    <select name="role_id" id="role_id" class="form-select" required>
                      <option value="1">Admin</option>
                      <option value="2">Kepala Desa</option>
                      <option value="3">Sekretaris Desa</option>
                      <option value="4">Kaur Umum</option>
                      <option value="5">Kaur Keuangan</option>
                      <option value="6">Kaur Perencanaan</option>
                      <option value="7">Kasi Pemerintahan</option>
                      <option value="8">Kasi Kesejahteraan</option>
                      <option value="9">Kasi Pelayanan</option>
                      <option value="10">Operator Sistem</option>
                    </select>
                  </div>
                </div>

                <div class="text-end">
                  <input type="hidden" name="add">
                  <button type="submit" class="btn btn-primary">Save</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- End Modal -->
      <script>
        const roleSelect = document.getElementById('role_id');
        const codeUserInput = document.getElementById('code_user');

        const rolePrefixes = {
          '1': 'ADM',
          '2': 'KDS',
          '3': 'SKD',
          '4': 'KUM',
          '5': 'KEU',
          '6': 'PRC',
          '7': 'PMP',
          '8': 'KSJ',
          '9': 'KPL',
          '10': 'OPS'
        };

        roleSelect.addEventListener('change', function() {
          const prefix = rolePrefixes[this.value] || 'USR';
          const random = Math.floor(1000 + Math.random() * 9000);
          codeUserInput.value = prefix + random;
        });
      </script>
      <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        togglePassword.addEventListener('click', function() {
          const type = passwordInput.type === 'password' ? 'text' : 'password';
          passwordInput.type = type;
          // Ganti ikon
          eyeIcon.classList.toggle('mdi-eye');
          eyeIcon.classList.toggle('mdi-eye-off');
        });
      </script>
    </div>
  </div>
</div>
<?php
include_once 'partials/footer.php';
?>