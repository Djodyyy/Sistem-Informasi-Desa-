<!-- forgot_password.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Lupa Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container p-5">

<h3>Lupa Password</h3>
<form method="POST" action="functions\reset_password_pegawai.php">
    <div class="mb-3">
        <label for="code_user" class="form-label">Kode User</label>
        <input type="text" class="form-control" id="code_user" name="code_user" required>
    </div>
    <button type="submit" class="btn btn-primary">Reset Password</button>
</form>

</body>
</html>
