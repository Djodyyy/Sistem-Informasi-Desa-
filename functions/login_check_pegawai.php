<?php
session_start();

if ($_POST) {
    require_once 'koneksi.php';
    $conn = dbConnect();

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT code_user, username, password, role_id FROM tb_user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Login berhasil
            $_SESSION['login'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['code_user'] = $user['code_user'];
            $_SESSION['role_id'] = $user['role_id'];

            header("Location: ../dashboard.php");
            exit();
        } else {
            $_SESSION['message'] = "Password salah!";
            header("Location: ../login_pegawai.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "Username tidak ditemukan!";
        header("Location: ../login_pegawai.php");
        exit();
    }
}

// Cegah akses langsung jika sudah login
if (isset($_SESSION['username'])) {
    header("Location: ../dashboard.php");
    exit();
}
