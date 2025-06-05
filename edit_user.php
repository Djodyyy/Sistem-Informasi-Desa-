<?php
// Tampilkan semua error
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'partials/header_detail.php';
require 'functions/function_user.php';

$code_user = $_GET['code_user'] ?? null;

if (!$code_user) {
    echo "Parameter code_user tidak ditemukan!";
    exit;
}

$data = getData($code_user);
if (!$data) {
    echo "Data tidak ditemukan untuk code_user: $code_user";
    exit;
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">User</li>
                        <a href="data_user.php" class="breadcrumb-item">Data User</a>
                        <li class="breadcrumb-item active">Edit User</li>
                    </ol>
                </div>
                <a href="data_user.php">
                    <h4 class="page-title"><i class="uil-arrow-circle-left text-info"></i> Back</h4>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div>
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title text-center">FORM EDIT USER</h4>
                    <hr>
                    <form action="functions/function_user.php" method="POST" class="p-2">
                        <input type="hidden" name="code_user" value="<?= $data['code_user'] ?>">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="name" class="form-control" value="<?= $data['name'] ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" value="<?= $data['username'] ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Role</label>
                                <select name="role_id" class="form-select" required>
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
                                        10 => 'Operator Sistem',
                                    ];
                                    foreach ($roles as $id => $roleName) {
                                        $selected = $data['role_id'] == $id ? 'selected' : '';
                                        echo "<option value='$id' $selected>$roleName</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Password Baru (Kosongkan jika tidak diubah)</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                        </div>
                        <div class="text-end">
                            <input type="hidden" name="edit" value="1">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="data_user.php" class="btn btn-dark">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'partials/footer_detail.php'; ?>
