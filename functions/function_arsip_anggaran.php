<?php
require_once 'koneksi.php'; // koneksi database

// Ambil semua tahun arsip unik, urut dari terbaru
function getAllTahunArsip()
{
    $conn = dbConnect();
    $sql = "SELECT DISTINCT tahun_anggaran FROM tb_arsip_anggaran ORDER BY tahun_anggaran DESC";
    $result = mysqli_query($conn, $sql);
    $tahunList = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $tahunList[] = $row['tahun_anggaran'];
        }
    }
    return $tahunList;
}

// Ambil arsip berdasarkan tahun
function getArsipByTahun($tahun)
{
    $conn = dbConnect();
    $tahun = intval($tahun);
    $stmt = $conn->prepare("SELECT * FROM tb_arsip_anggaran WHERE tahun_anggaran = ? ORDER BY tanggal_upload DESC");
    $stmt->bind_param("i", $tahun);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

// Tambah arsip
function tambahArsipAnggaran($data, $file)
{
    $conn = dbConnect();

    $nama_dokumen = htmlspecialchars($data['nama_dokumen']);
    $tahun = htmlspecialchars($data['tahun_anggaran']);
    $deskripsi = htmlspecialchars($data['deskripsi']);

    $target_dir = "../uploads/arsip_anggaran/$tahun/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $nama_file = basename($file['name']);
    $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
    $allowed = ['pdf', 'jpg', 'jpeg', 'png'];

    if (!in_array($ext, $allowed)) {
        return false; // ekstensi tidak diperbolehkan
    }

    $target_file = $target_dir . $nama_file;

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO tb_arsip_anggaran (nama_dokumen, tahun_anggaran, deskripsi, file_scan, tanggal_upload) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("siss", $nama_dokumen, $tahun, $deskripsi, $nama_file);
        return $stmt->execute();
    }

    return false;
}

// Ambil arsip berdasarkan ID
function getArsipById($id)
{
    $conn = dbConnect();
    $id = intval($id);
    $result = mysqli_query($conn, "SELECT * FROM tb_arsip_anggaran WHERE id = $id");
    return mysqli_fetch_assoc($result);
}

// Update arsip
function updateArsipAnggaran($data, $file = null)
{
    $conn = dbConnect();
    $id = intval($data['id']);
    $nama_dokumen = htmlspecialchars($data['nama_dokumen']);
    $tahun = htmlspecialchars($data['tahun_anggaran']);
    $deskripsi = htmlspecialchars($data['deskripsi']);

    $arsipLama = getArsipById($id);
    $nama_file = $arsipLama['file_scan'];

    // Jika ada file baru diupload
    if ($file && $file['name']) {
        $target_dir = "../uploads/arsip_anggaran/$tahun/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Hapus file lama
        $old_path = "../uploads/arsip_anggaran/{$arsipLama['tahun_anggaran']}/{$arsipLama['file_scan']}";
        if (file_exists($old_path)) {
            unlink($old_path);
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['pdf', 'jpg', 'jpeg', 'png'];

        if (!in_array($ext, $allowed)) {
            return false;
        }

        $nama_file = basename($file['name']);
        $target_file = $target_dir . $nama_file;
        move_uploaded_file($file['tmp_name'], $target_file);
    }

    $stmt = $conn->prepare("UPDATE tb_arsip_anggaran SET nama_dokumen = ?, tahun_anggaran = ?, deskripsi = ?, file_scan = ?, tanggal_upload = NOW() WHERE id = ?");
    $stmt->bind_param("sissi", $nama_dokumen, $tahun, $deskripsi, $nama_file, $id);
    return $stmt->execute();
}

// Hapus arsip
function hapusArsipAnggaran($id)
{
    $conn = dbConnect();
    $arsip = getArsipById($id);

    if ($arsip) {
        $filepath = "../uploads/arsip_anggaran/{$arsip['tahun_anggaran']}/{$arsip['file_scan']}";
        if (file_exists($filepath)) {
            unlink($filepath);
        }

        return mysqli_query($conn, "DELETE FROM tb_arsip_anggaran WHERE id = $id");
    }

    return false;
}

// Proses aksi POST dan GET dari form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_arsip'])) {
        $res = tambahArsipAnggaran($_POST, $_FILES['file_dokumen']);
        if ($res) {
            header('Location: ../tambah_arsip_anggaran.php?success=1');
        } else {
            header('Location: ../tambah_arsip_anggaran.php?error=1');
        }
        exit;
    }

    if (isset($_POST['edit_arsip'])) {
        $res = updateArsipAnggaran($_POST, $_FILES['file_dokumen']);
        if ($res) {
            header('Location: ../tambah_arsip_anggaran.php?success=2');
        } else {
            header('Location: ../tambah_arsip_anggaran.php?error=2');
        }
        exit;
    }
}

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $res = hapusArsipAnggaran($id);
    if ($res) {
        header('Location: ../tambah_arsip_anggaran.php?success=3');
    } else {
        header('Location: ../tambah_arsip_anggaran.php?error=3');
    }
    exit;
}
?>
