<?php
require_once 'koneksi.php';

function getAllPembangunan() {
    $conn = dbConnect();
    $result = mysqli_query($conn, "SELECT * FROM tb_pembangunan ORDER BY tahun DESC, id DESC");
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

function tambahPembangunan($data, $file) {
    $conn = dbConnect();

    $judul = isset($data['judul']) ? htmlspecialchars($data['judul']) : '';
    $lokasi = isset($data['lokasi']) ? htmlspecialchars($data['lokasi']) : '';
    $tahun = isset($data['tahun']) ? htmlspecialchars($data['tahun']) : '';
    $volume = isset($data['volume']) ? htmlspecialchars($data['volume']) : '';
    $anggaran = isset($data['anggaran']) ? htmlspecialchars($data['anggaran']) : '';
    $sumber_dana = isset($data['sumber_dana']) ? htmlspecialchars($data['sumber_dana']) : '';
    $keterangan = isset($data['keterangan']) ? htmlspecialchars($data['keterangan']) : '';

    // Upload foto
    $namaFoto = '';
    if (isset($file['foto']) && $file['foto']['error'] == 0) {
        $ext = strtolower(pathinfo($file['foto']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];
        if (in_array($ext, $allowed)) {
            $namaFoto = 'pembangunan_' . time() . '.' . $ext;
            move_uploaded_file($file['foto']['tmp_name'], '../uploads/pembangunan/' . $namaFoto);
        } else {
            return false; // ekstensi foto tidak diizinkan
        }
    }

    $query = "INSERT INTO tb_pembangunan (judul, lokasi, tahun, volume, anggaran, sumber_dana, keterangan, foto) 
              VALUES ('$judul', '$lokasi', '$tahun', '$volume', '$anggaran', '$sumber_dana', '$keterangan', '$namaFoto')";
    return mysqli_query($conn, $query);
}

function editPembangunan($data, $file) {
    $conn = dbConnect();

    $id = isset($data['id']) ? (int)$data['id'] : 0;
    $judul = isset($data['judul']) ? htmlspecialchars($data['judul']) : '';
    $lokasi = isset($data['lokasi']) ? htmlspecialchars($data['lokasi']) : '';
    $tahun = isset($data['tahun']) ? htmlspecialchars($data['tahun']) : '';
    $volume = isset($data['volume']) ? htmlspecialchars($data['volume']) : '';
    $anggaran = isset($data['anggaran']) ? htmlspecialchars($data['anggaran']) : '';
    $sumber_dana = isset($data['sumber_dana']) ? htmlspecialchars($data['sumber_dana']) : '';
    $keterangan = isset($data['keterangan']) ? htmlspecialchars($data['keterangan']) : '';

    $fotoBaru = '';
    if (isset($file['foto']) && $file['foto']['error'] == 0) {
        $ext = strtolower(pathinfo($file['foto']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];
        if (in_array($ext, $allowed)) {
            $fotoBaru = 'pembangunan_' . time() . '.' . $ext;
            move_uploaded_file($file['foto']['tmp_name'], '../uploads/pembangunan/' . $fotoBaru);

            // Hapus foto lama
            $old = mysqli_query($conn, "SELECT foto FROM tb_pembangunan WHERE id=$id");
            $oldData = mysqli_fetch_assoc($old);
            if ($oldData['foto'] && file_exists("../uploads/pembangunan/" . $oldData['foto'])) {
                unlink("../uploads/pembangunan/" . $oldData['foto']);
            }
        } else {
            return false; // ekstensi foto tidak valid
        }
    }

    $query = "UPDATE tb_pembangunan SET 
                judul='$judul', 
                lokasi='$lokasi', 
                tahun='$tahun',
                volume='$volume',
                anggaran='$anggaran',
                sumber_dana='$sumber_dana',
                keterangan='$keterangan'";

    if ($fotoBaru != '') {
        $query .= ", foto='$fotoBaru'";
    }

    $query .= " WHERE id=$id";

    return mysqli_query($conn, $query);
}

function hapusPembangunan($id) {
    $conn = dbConnect();

    $old = mysqli_query($conn, "SELECT foto FROM tb_pembangunan WHERE id=$id");
    $oldData = mysqli_fetch_assoc($old);
    if ($oldData['foto'] && file_exists("../uploads/pembangunan/" . $oldData['foto'])) {
        unlink("../uploads/pembangunan/" . $oldData['foto']);
    }

    return mysqli_query($conn, "DELETE FROM tb_pembangunan WHERE id=$id");
}

function getPembangunanById($id) {
    $conn = dbConnect();
    $result = mysqli_query($conn, "SELECT * FROM tb_pembangunan WHERE id=$id");
    return mysqli_fetch_assoc($result);
}

// Handle tambah data
if (isset($_POST['add_pembangunan'])) {
    $result = tambahPembangunan($_POST, $_FILES);
    if ($result) {
        header("Location: ../tambah_pembangunan.php?success=1");
    } else {
        header("Location: ../tambah_pembangunan.php?error=Gagal menambahkan data.");
    }
    exit;
}

// Handle edit data
if (isset($_POST['edit_pembangunan'])) {
    $result = editPembangunan($_POST, $_FILES);
    if ($result) {
        header("Location: ../tambah_pembangunan.php?updated=1");
    } else {
        header("Location: ../tambah_pembangunan.php?error=Gagal memperbarui data.");
    }
    exit;
}

// Handle hapus data
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $result = hapusPembangunan($id);
    if ($result) {
        header("Location: ../tambah_pembangunan.php?deleted=1");
    } else {
        header("Location: ../tambah_pembangunan.php?error=Gagal menghapus data.");
    }
    exit;
}
