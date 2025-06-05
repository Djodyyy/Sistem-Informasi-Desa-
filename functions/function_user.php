<?php
require_once 'koneksi.php';
include_once 'helper.php';


function getData($code_user = null)
{
  try {
    $conn = dbConnect();
    if ($code_user) {
      $stmt = $conn->prepare("SELECT * FROM tb_user WHERE code_user = ?");
      $stmt->bind_param("s", $code_user);
      $stmt->execute();
      $result = $stmt->get_result();
      return $result->fetch_assoc();
    } else {
      $result = $conn->query("SELECT * FROM tb_user");
      return $result->fetch_all(MYSQLI_ASSOC);
    }
  } catch (Exception $e) {
    echo "Error getData: " . $e->getMessage();
    return null;
  }
}

function generateRandomPassword()
{
  $lowercase = 'abcdefghijklmnopqrstuvwxyz';
  $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $digits = '0123456789';
  $specials = '!@#$%^&*()_+{}[]:;<>,.?~\\-';
  $length = 8;

  $password = $lowercase[rand(0, strlen($lowercase) - 1)] .
    $uppercase[rand(0, strlen($uppercase) - 1)] .
    $digits[rand(0, strlen($digits) - 1)] .
    $specials[rand(0, strlen($specials) - 1)];

  $all = $lowercase . $uppercase . $digits . $specials;
  for ($i = 4; $i < $length; $i++) {
    $password .= $all[rand(0, strlen($all) - 1)];
  }

  return str_shuffle($password);
}

function addData($data)
{
  try {
    $conn = dbConnect();

    // Cek apakah username sudah digunakan
    $stmt = $conn->prepare("SELECT 1 FROM tb_user WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $data['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) throw new Exception("Username tidak tersedia!");

    // Pakai password dari input, kalau kosong generate random
    $pwd = !empty($data['password']) ? $data['password'] : generateRandomPassword();
    $hashPwd = password_hash($pwd, PASSWORD_ARGON2ID);

    $stmt = $conn->prepare("INSERT INTO tb_user (code_user, name, username, password, role_id, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssss", $data['code_user'], $data['name'], $data['username'], $hashPwd, $data['role_id']);
    $stmt->execute();

    $msg = [
      'icon' => 'success',
      'title' => 'Success!',
      'text' => 'User berhasil ditambahkan',
      'data' => ['password' => $pwd] // bisa dipakai untuk ditampilkan di UI
    ];
    $stmt->close();
  } catch (Exception $e) {
    $msg = [
      'icon' => 'error',
      'title' => 'Error!',
      'text' => "Terjadi kesalahan: " . $e->getMessage()
    ];
  }
  return $msg;
}


function deleteData($code_user)
{
  $conn = dbConnect();
  $stmt = $conn->prepare("DELETE FROM tb_user WHERE code_user = ?");
  $stmt->bind_param("s", $code_user);
  if (!$stmt) die("Query Error: " . $conn->error);
  $res = $stmt->execute();
  $stmt->close();

  if ($res) return ['icon' => 'success', 'title' => 'Success!', 'text' => 'Data berhasil dihapus!'];
  else return ['icon' => 'error', 'title' => 'Error!', 'text' => 'Terjadi kesalahan: Eksekusi gagal!'];
}

// Tambah User
if (isset($_POST['add'])) {
  $dataInput = [
    'code_user'    => $_POST['code_user'],
    'name'         => $_POST['name'],
    'password'     => $_POST['password'],
    'username'     => $_POST['username'],
    'role_id'      => $_POST['role_id']
  ];
  $add = addData($dataInput);
  session_start();
  $_SESSION['message'] = $add;
  header("location:../data_user.php?code_user=" . $dataInput['code_user']);
} elseif (isset($_GET['hapus'])) {
  $code_user = $_GET['hapus'];
  $delete = deleteData($code_user);
  session_start();
  $_SESSION['message'] = $delete;
  header("Location: ../data_user.php?notif=success");
}
if (isset($_POST['edit'])) {
  $conn = dbConnect();

  $code_user = $_POST['code_user'];
  $name = $_POST['name'];
  $username = $_POST['username'];
  $role_id = $_POST['role_id'];
  $password = $_POST['password'];

  try {
    if (!empty($password)) {
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
      $query = "UPDATE tb_user SET name = ?, username = ?, role_id = ?, password = ? WHERE code_user = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("ssiss", $name, $username, $role_id, $hashedPassword, $code_user);
    } else {
      $query = "UPDATE tb_user SET name = ?, username = ?, role_id = ? WHERE code_user = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("ssis", $name, $username, $role_id, $code_user);
    }

    $stmt->execute();
    $stmt->close();
    header("Location: ../data_user.php?update=success");
    exit;
  } catch (Exception $e) {
    die("Gagal update: " . $e->getMessage());
  }
}
