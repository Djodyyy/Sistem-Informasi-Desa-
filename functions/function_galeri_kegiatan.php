<?php
require_once "koneksi.php";

// Ambil semua data galeri & kegiatan
function getGaleriKegiatan() {
    $conn = dbConnect();
    $sql = "SELECT * FROM tb_galeri_kegiatan ORDER BY tanggal_kegiatan DESC";
    return mysqli_query($conn, $sql);
}

// Tambah data baru galeri & kegiatan
function tambahGaleriKegiatan($data, $file) {
    $conn = dbConnect();
    $judul = htmlspecialchars($data['judul']);
    $deskripsi = htmlspecialchars($data['deskripsi']);
    $tanggal_kegiatan = htmlspecialchars($data['tanggal']);
    $tanggal_upload = date('Y-m-d');

    $file_gambar = uploadFileGaleri($file);
    if (!$file_gambar) return false;

    $sql = "INSERT INTO tb_galeri_kegiatan (judul, deskripsi, file_gambar, tanggal_kegiatan, tanggal_upload)
            VALUES ('$judul', '$deskripsi', '$file_gambar', '$tanggal_kegiatan', '$tanggal_upload')";
    return mysqli_query($conn, $sql);
}

// Fungsi upload gambar galeri
function uploadFileGaleri($file) {
    $namaFile = $file['name'];
    $tmpName = $file['tmp_name'];
    $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
    $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($ext, $allowedExt)) {
        return false;
    }

    $namaBaru = uniqid('galeri_', true) . '.' . $ext;
    $tujuan = '../uploads/galeri/' . $namaBaru;

    if (move_uploaded_file($tmpName, $tujuan)) {
        return $namaBaru;
    }
    return false;
}

// Hapus data galeri
function hapusGaleriKegiatan($id) {
    $conn = dbConnect();
    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT file_gambar FROM tb_galeri_kegiatan WHERE id = $id"));
    if ($data) {
        $filePath = '../uploads/galeri/' . $data['file_gambar'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    return mysqli_query($conn, "DELETE FROM tb_galeri_kegiatan WHERE id = $id");
}

// Ambil satu data untuk edit
function getGaleriKegiatanById($id) {
    $conn = dbConnect();
    return mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tb_galeri_kegiatan WHERE id = $id"));
}

// Update data galeri
function updateGaleriKegiatan($id, $data, $file = null) {
    $conn = dbConnect();
    $judul = htmlspecialchars($data['judul']);
    $deskripsi = htmlspecialchars($data['deskripsi']);
    $tanggal_kegiatan = htmlspecialchars($data['tanggal']);

    if ($file && $file['name'] != '') {
        $gambarBaru = uploadFileGaleri($file);
        if (!$gambarBaru) return false;

        $lama = getGaleriKegiatanById($id);
        if ($lama && file_exists('../uploads/galeri/' . $lama['file_gambar'])) {
            unlink('../uploads/galeri/' . $lama['file_gambar']);
        }

        $sql = "UPDATE tb_galeri_kegiatan SET 
                judul = '$judul',
                deskripsi = '$deskripsi',
                file_gambar = '$gambarBaru',
                tanggal_kegiatan = '$tanggal_kegiatan'
                WHERE id = $id";
    } else {
        $sql = "UPDATE tb_galeri_kegiatan SET 
                judul = '$judul',
                deskripsi = '$deskripsi',
                tanggal_kegiatan = '$tanggal_kegiatan'
                WHERE id = $id";
    }

    return mysqli_query($conn, $sql);
}

// Proses Form Tambah / Edit / Hapus
if (isset($_POST['add_galeri'])) {
    if (tambahGaleriKegiatan($_POST, $_FILES['gambar'])) {
        header("Location: ../tambah_galeri_kegiatan.php?success");
    } else {
        header("Location: ../tambah_galeri_kegiatan.php?error");
    }
    exit;
}

if (isset($_POST['edit_galeri'])) {
    $id = $_POST['id_galeri'];
    if (updateGaleriKegiatan($id, $_POST, $_FILES['gambar'])) {
        header("Location: ../tambah_galeri_kegiatan.php?updated");
    } else {
        header("Location: ../tambah_galeri_kegiatan.php?error");
    }
    exit;
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    if (hapusGaleriKegiatan($id)) {
        header("Location: ../tambah_galeri_kegiatan.php?deleted");
    } else {
        header("Location: ../tambah_galeri_kegiatan.php?error");
    }
    exit;
}
?>
