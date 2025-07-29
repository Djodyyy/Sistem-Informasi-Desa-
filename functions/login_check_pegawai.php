<?php
session_start();

if ($_POST) {
    require_once 'koneksi-dummy.php'; 
    $conn = dbConnect(); 

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        
        if (password_verify($password, $user['password_hash'])) {
            
            $_SESSION['login'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            header("Location: ../dashboard.php");
            exit();
        } else {
            $_SESSION['message'] = "Password salah";
            header("Location: ../login.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "User tidak ditemukan";
        header("Location: ../login.php");
        exit();
    }
}


if (isset($_SESSION['username'])) {
    header("Location: ../dashboard.php");
    exit();
}
