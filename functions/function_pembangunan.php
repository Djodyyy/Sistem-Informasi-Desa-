<?php
require_once 'koneksi.php';

function getAllPembangunan()
{
    $conn = dbConnect();
    $result = mysqli_query($conn, "SELECT * FROM tb_pembangunan ORDER BY STR_TO_DATE(bulan, '%M %Y') DESC, id DESC");
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

function tambahPembangunan($data, $file)
{
    $conn = dbConnect();

    $judul = htmlspecialchars($data['judul'] ?? '');
    $lokasi = htmlspecialchars($data['lokasi'] ?? '');
    $bulan = htmlspecialchars($data['bulan'] ?? '');
    $volume = htmlspecialchars($data['volume'] ?? '');
    $anggaran = htmlspecialchars($data['anggaran'] ?? '');
    $sumber_dana = htmlspecialchars($data['sumber_dana'] ?? '');
    $keterangan = htmlspecialchars($data['keterangan'] ?? '');

    // Upload foto
    $fotoArray = [];

    if (isset($file['foto'])) {
        $totalFiles = count($file['foto']['name']);
        for ($i = 0; $i < $totalFiles; $i++) {
            if ($file['foto']['error'][$i] === 0) {
                $ext = strtolower(pathinfo($file['foto']['name'][$i], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png'];
                if (in_array($ext, $allowed)) {
                    $namaFile = 'pembangunan_' . time() . '_' . $i . '.' . $ext;
                    move_uploaded_file($file['foto']['tmp_name'][$i], '../uploads/pembangunan/' . $namaFile);
                    $fotoArray[] = $namaFile;
                }
            }
        }
    }

    $fotoJSON = json_encode($fotoArray);

    $query = "INSERT INTO tb_pembangunan (judul, lokasi, bulan, volume, anggaran, sumber_dana, keterangan, foto, tanggal_upload)
          VALUES ('$judul', '$lokasi', '$bulan', '$volume', '$anggaran', '$sumber_dana', '$keterangan', '$fotoJSON', NOW())";


    return mysqli_query($conn, $query);
}

function editPembangunan($data, $file)
{
    $conn = dbConnect();

    $id = (int)($data['id'] ?? 0);
    $judul = htmlspecialchars($data['judul'] ?? '');
    $lokasi = htmlspecialchars($data['lokasi'] ?? '');
    $bulan = htmlspecialchars($data['bulan'] ?? '');
    $volume = htmlspecialchars($data['volume'] ?? '');
    $anggaran = htmlspecialchars($data['anggaran'] ?? '');
    $sumber_dana = htmlspecialchars($data['sumber_dana'] ?? '');
    $keterangan = htmlspecialchars($data['keterangan'] ?? '');

    $fotoBaru = '';
    if (isset($file['foto']) && $file['foto']['error'] === 0) {
        $ext = strtolower(pathinfo($file['foto']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];
        if (in_array($ext, $allowed)) {
            $fotoBaru = 'pembangunan_' . time() . '.' . $ext;
            move_uploaded_file($file['foto']['tmp_name'], '../uploads/pembangunan/' . $fotoBaru);

            $old = mysqli_query($conn, "SELECT foto FROM tb_pembangunan WHERE id=$id");
            $oldData = mysqli_fetch_assoc($old);
            if (!empty($oldData['foto']) && file_exists("../uploads/pembangunan/" . $oldData['foto'])) {
                unlink("../uploads/pembangunan/" . $oldData['foto']);
            }
        } else {
            return false;
        }
    }

    $query = "UPDATE tb_pembangunan SET 
                judul='$judul', 
                lokasi='$lokasi', 
                bulan='$bulan',
                volume='$volume',
                anggaran='$anggaran',
                sumber_dana='$sumber_dana',
                keterangan='$keterangan'";

    if (!empty($fotoBaru)) {
        $query .= ", foto='$fotoBaru'";
    }

    $query .= " WHERE id=$id";

    return mysqli_query($conn, $query);
}

function hapusPembangunan($id)
{
    $conn = dbConnect();

    $old = mysqli_query($conn, "SELECT foto FROM tb_pembangunan WHERE id=$id");
    $oldData = mysqli_fetch_assoc($old);
    if (!empty($oldData['foto']) && file_exists("../uploads/pembangunan/" . $oldData['foto'])) {
        unlink("../uploads/pembangunan/" . $oldData['foto']);
    }

    return mysqli_query($conn, "DELETE FROM tb_pembangunan WHERE id=$id");
}

function getPembangunanById($id)
{
    $conn = dbConnect();
    $id = (int)$id; // sanitasi biar aman
    $result = mysqli_query($conn, "SELECT * FROM tb_pembangunan WHERE id = $id");

    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);

        // Decode JSON jika ada file foto
        if (!empty($data['file_foto'])) {
            $decoded = json_decode($data['file_foto'], true);

            // Cek apakah decode berhasil dan array
            $data['file_foto'] = is_array($decoded) ? $decoded : [];
        } else {
            $data['file_foto'] = [];
        }

        return $data;
    }

    return null; // return null kalau data tidak ditemukan
}

if (isset($_POST['add_pembangunan'])) {
    $result = tambahPembangunan($_POST, $_FILES);
    if ($result) {
        header("Location: ../tambah_pembangunan.php?success=1");
    } else {
        header("Location: ../tambah_pembangunan.php?error=Gagal menambahkan data.");
    }
    exit;
}

if (isset($_POST['edit_pembangunan'])) {
    $result = editPembangunan($_POST, $_FILES);
    if ($result) {
        header("Location: ../tambah_pembangunan.php?updated=1");
    } else {
        header("Location: ../tambah_pembangunan.php?error=Gagal memperbarui data.");
    }
    exit;
}

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
