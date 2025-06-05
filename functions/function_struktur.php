<?php
require_once('koneksi.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

function getAllStruktur() {
    $conn = dbConnect();
    $query = "SELECT * FROM struktur_aparatur ORDER BY id ASC";
    $result = mysqli_query($conn, $query);
    $data = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    return $data;
}

function getStrukturById($id) {
    $conn = dbConnect();
    $id = intval($id);
    $query = "SELECT * FROM struktur_aparatur WHERE id = $id LIMIT 1";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

function insertStruktur($nama, $jabatan, $fotoName) {
    $conn = dbConnect();
    $nama = mysqli_real_escape_string($conn, $nama);
    $jabatan = mysqli_real_escape_string($conn, $jabatan);
    $fotoName = mysqli_real_escape_string($conn, $fotoName);
    $query = "INSERT INTO struktur_aparatur (nama, jabatan, foto) VALUES ('$nama', '$jabatan', '$fotoName')";
    return mysqli_query($conn, $query);
}

function updateStruktur($id, $nama, $jabatan, $fotoName = null) {
    $conn = dbConnect();
    $id = intval($id);
    $nama = mysqli_real_escape_string($conn, $nama);
    $jabatan = mysqli_real_escape_string($conn, $jabatan);
    $query = "UPDATE struktur_aparatur SET nama='$nama', jabatan='$jabatan'";
    if ($fotoName) {
        $fotoName = mysqli_real_escape_string($conn, $fotoName);
        $query .= ", foto='$fotoName'";
    }
    $query .= " WHERE id=$id";
    return mysqli_query($conn, $query);
}

function deleteStruktur($id) {
    $conn = dbConnect();
    $id = intval($id);
    $query = "DELETE FROM struktur_aparatur WHERE id = $id";
    return mysqli_query($conn, $query);
}

// Proses tambah data dari form tambah_struktur.php
if (isset($_POST['add_struktur'])) {
    $nama = $_POST['nama'] ?? '';
    $jabatan = $_POST['jabatan'] ?? '';

    // Upload foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $uploadDir = '../assets/img/aparatur/';
        $fotoTmp = $_FILES['foto']['tmp_name'];
        $fotoName = basename($_FILES['foto']['name']);
        $fotoExt = strtolower(pathinfo($fotoName, PATHINFO_EXTENSION));
        $allowedExt = ['jpg', 'jpeg'];

        if (in_array($fotoExt, $allowedExt)) {
            $newFileName = uniqid('foto_') . '.' . $fotoExt;
            if (move_uploaded_file($fotoTmp, $uploadDir . $newFileName)) {
                if (insertStruktur($nama, $jabatan, $newFileName)) {
                    header('Location: ../tambah_struktur.php?msg=success');
                    exit;
                } else {
                    echo "Gagal menyimpan data ke database.";
                }
            } else {
                echo "Gagal upload file foto.";
            }
        } else {
            echo "Ekstensi file tidak diperbolehkan.";
        }
    } else {
        echo "Foto wajib diupload.";
    }
}

// Proses edit data dari modal edit
if (isset($_POST['edit_struktur'])) {
    $id = $_POST['id'] ?? 0;
    $nama = $_POST['nama'] ?? '';
    $jabatan = $_POST['jabatan'] ?? '';
    $fotoName = null;

    // Cek jika ada upload foto baru
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $uploadDir = '../assets/img/aparatur/';
        $fotoTmp = $_FILES['foto']['tmp_name'];
        $fotoNameOri = basename($_FILES['foto']['name']);
        $fotoExt = strtolower(pathinfo($fotoNameOri, PATHINFO_EXTENSION));
        $allowedExt = ['jpg', 'jpeg'];

        if (in_array($fotoExt, $allowedExt)) {
            $newFileName = uniqid('foto_') . '.' . $fotoExt;
            if (move_uploaded_file($fotoTmp, $uploadDir . $newFileName)) {
                $fotoName = $newFileName;
            } else {
                echo "Gagal upload file foto.";
            }
        } else {
            echo "Ekstensi file tidak diperbolehkan.";
        }
    }

    if (updateStruktur($id, $nama, $jabatan, $fotoName)) {
        header('Location: ../tambah_struktur.php?msg=updated');
        exit;
    } else {
        echo "Gagal update data.";
    }
}

// Proses hapus data lewat link GET
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    if (deleteStruktur($id)) {
        header('Location: ../tambah_struktur.php?msg=deleted');
        exit;
    } else {
        echo "Gagal hapus data.";
    }
}
