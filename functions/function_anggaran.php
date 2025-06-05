<?php
require_once 'koneksi.php';

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    if (hapusAnggaran($id)) {
        header("Location: ../tambah_anggaran.php?deleted=1");
    } else {
        header("Location: ../tambah_anggaran.php?error=1");
    }
    exit;
}


error_reporting(E_ALL);
ini_set('display_errors', 1);

function getAllAnggaran()
{
    $conn = dbConnect();
    $query = "SELECT * FROM tb_anggaran ORDER BY tahun DESC, id_anggaran DESC";
    $result = $conn->query($query);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $row['dokumentasi'] = getDokumentasiByAnggaran($conn, $row['id_anggaran']);
        $data[] = $row;
    }
    return $data;
}

function getDokumentasiByAnggaran($conn, $id_anggaran)
{
    $query = "SELECT * FROM tb_anggaran_dokumentasi WHERE id_anggaran = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_anggaran);
    $stmt->execute();
    $result = $stmt->get_result();

    $dokumentasi = [];
    while ($row = $result->fetch_assoc()) {
        $dokumentasi[] = $row;
    }

    return $dokumentasi;
}

function uploadMultipleDokumentasi($files, $id_anggaran)
{
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    $uploadDir = '../uploads/anggaran/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $conn = dbConnect();
    for ($i = 0; $i < count($files['name']); $i++) {
        if ($files['error'][$i] === 0 && in_array($files['type'][$i], $allowedTypes)) {
            $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
            $fileName = 'foto_' . time() . '_' . $i . '.' . $ext;
            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($files['tmp_name'][$i], $targetFile)) {
                $stmt = $conn->prepare("INSERT INTO tb_anggaran_dokumentasi (id_anggaran, file_foto, uploaded_at) VALUES (?, ?, NOW())");
                $stmt->bind_param("is", $id_anggaran, $fileName);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
    $conn->close();
}


function tambahAnggaran($data, $files)
{
    $conn = dbConnect();
    $deskripsi  = htmlspecialchars($data['deskripsi']);
    $tahun      = htmlspecialchars($data['tahun']);
    $anggaran   = str_replace('.', '', $data['anggaran']);
    $realisasi  = str_replace('.', '', $data['realisasi']);
    $keterangan = htmlspecialchars($data['keterangan']);

    $stmt = $conn->prepare("INSERT INTO tb_anggaran (deskripsi, tahun, anggaran, realisasi, keterangan, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssdds", $deskripsi, $tahun, $anggaran, $realisasi, $keterangan);
    if ($stmt->execute()) {
        $id_anggaran = $stmt->insert_id;
        if (!empty($files['name'][0])) {
            uploadMultipleDokumentasi($files, $id_anggaran);
        }
        return true;
    }
    return false;
}

function hapusAnggaran($id)
{
    $conn = dbConnect();

    // Ambil semua file dokumentasi untuk anggaran ini
    $stmt = $conn->prepare("SELECT file_foto FROM tb_anggaran_dokumen WHERE id_anggaran = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Hapus file-filenya dari folder
    $uploadDir = '../uploads/anggaran/';
    while ($row = $result->fetch_assoc()) {
        $filePath = $uploadDir . $row['file_foto'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // Hapus data dokumentasi dari database
    $stmt = $conn->prepare("DELETE FROM tb_anggaran_dokumen WHERE id_anggaran = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Hapus data anggaran utama
    $stmt = $conn->prepare("DELETE FROM tb_anggaran WHERE id_anggaran = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        return true;
    }
    return false;
}

function updateAnggaran($data, $files)
{
    $conn = dbConnect();
    $id         = $data['id_anggaran'];
    $deskripsi  = htmlspecialchars($data['deskripsi']);
    $tahun      = htmlspecialchars($data['tahun']);
    $anggaran   = str_replace('.', '', $data['anggaran']);
    $realisasi  = str_replace('.', '', $data['realisasi']);
    $keterangan = htmlspecialchars($data['keterangan']);

    $stmt = $conn->prepare("UPDATE tb_anggaran SET deskripsi=?, tahun=?, anggaran=?, realisasi=?, keterangan=? WHERE id_anggaran=?");
    $stmt->bind_param("ssddsi", $deskripsi, $tahun, $anggaran, $realisasi, $keterangan, $id);
    if ($stmt->execute()) {
        if (!empty($files['name'][0])) {
            uploadMultipleDokumentasi($files, $id);
        }
        return true;
    }
    return false;
}

// === HANDLE REQUEST ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_anggaran'])) {
        if (tambahAnggaran($_POST, $_FILES['dokumentasi_foto'] ?? [])) {
            header("Location: ../tambah_anggaran.php?success=1");
        } else {
            header("Location: ../tambah_anggaran.php?error=1");
        }
        exit;
    }

    if (isset($_POST['edit_anggaran'])) {
        if (updateAnggaran($_POST, $_FILES['dokumentasi_foto'] ?? [])) {
            header("Location: ../tambah_anggaran.php?updated=1");
        } else {
            header("Location: ../tambah_anggaran.php?error=1");
        }
        exit;
    }
}

// Handle hapus data via GET
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    if (hapusAnggaran($id)) {
        header("Location: ../tambah_anggaran.php?deleted=1");
    } else {
        header("Location: ../tambah_anggaran.php?error=1");
    }
    exit;
}
