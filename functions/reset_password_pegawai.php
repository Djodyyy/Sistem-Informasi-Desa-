<?php
include('koneksi.php'); // koneksi.php dan file ini ada di folder functions

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code_user = $_POST['code_user'];

    // Gunakan koneksi dari singleton class
    $conn = dbConnect();

    // Gunakan prepared statement untuk keamanan
    $stmt = $conn->prepare("SELECT * FROM tb_user WHERE code_user = ?");
    $stmt->bind_param("s", $code_user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Tampilkan form ubah password
?>
        <!DOCTYPE html>
        <html>

        <head>
            <title>Reset Password</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        </head>

        <body class="container p-5">
            <h3>Reset Password untuk <?= htmlspecialchars($code_user) ?></h3>
            <form method="POST" action="process_reset.php">
                <input type="hidden" name="code_user" value="<?= htmlspecialchars($code_user) ?>">
                <div class="mb-3">
                    <label>Password Baru</label>
                    <input type="password" name="new_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Ubah Password</button>

            </form>
        </body>

        </html>
<?php
    } else {
        echo "<script>alert('User tidak ditemukan'); window.location='forgot_password.php';</script>";
    }

    $stmt->close();
}
?>