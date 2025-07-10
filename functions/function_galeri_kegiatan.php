<?php
require_once 'koneksi.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// =========================
// Get Semua Kegiatan + Foto
// =========================
function getAllKegiatan()
{
    $conn = dbConnect();
    $query = "
        SELECT 
            k.id,
            k.judul,
            k.deskripsi,
            k.tanggal_kegiatan,
            k.tanggal_upload,
            GROUP_CONCAT(g.file_gambar) AS file_gambar
        FROM tb_kegiatan k
        LEFT JOIN tb_kegiatan_gambar g ON k.id = g.kegiatan_id
        GROUP BY k.id
        ORDER BY k.tanggal_kegiatan DESC
    ";

    $result = $conn->query($query);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $row['file_gambar'] = !empty($row['file_gambar']) ? explode(',', $row['file_gambar']) : [];
        $data[] = $row;
    }

    return $data;
}

// ==========================
// Tambah Kegiatan + Upload Gambar
// ==========================
function tambahKegiatan($data, $files)
{
    $conn = dbConnect();

    $judul = htmlspecialchars($data['judul']);
    $deskripsi = htmlspecialchars($data['deskripsi']);
    $tanggal_kegiatan = $data['tanggal'];
    $tanggal_upload = date('Y-m-d');

    $stmt = $conn->prepare("INSERT INTO tb_kegiatan (judul, deskripsi, tanggal_kegiatan, tanggal_upload) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $judul, $deskripsi, $tanggal_kegiatan, $tanggal_upload);

    if ($stmt->execute()) {
        $kegiatan_id = $stmt->insert_id;

        if (!empty($files['name'][0])) {
            uploadMultipleKegiatanGambar($files, $kegiatan_id);
        }

        return true;
    }

    return false;
}

// ====================================
// Upload Banyak Gambar ke tb_kegiatan_gambar
// ====================================
function uploadMultipleKegiatanGambar($files, $kegiatan_id)
{
    $conn = dbConnect();
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    $uploadDir = '../uploads/galeri/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    for ($i = 0; $i < count($files['name']); $i++) {
        if ($files['error'][$i] === 0 && in_array($files['type'][$i], $allowedTypes)) {
            $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
            $fileName = 'kegiatan_' . time() . '_' . $i . '.' . $ext;
            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($files['tmp_name'][$i], $targetFile)) {
                $stmt = $conn->prepare("INSERT INTO tb_kegiatan_gambar (kegiatan_id, file_gambar, uploaded_at) VALUES (?, ?, NOW())");
                $stmt->bind_param("is", $kegiatan_id, $fileName);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
}

// ==========================
// Hapus Kegiatan + Semua Gambar
// ==========================
function hapusKegiatan($id)
{
    $conn = dbConnect();

    // Ambil semua nama file dulu
    $stmt = $conn->prepare("SELECT file_gambar FROM tb_kegiatan_gambar WHERE kegiatan_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $file = '../uploads/galeri/' . $row['file_gambar'];
        if (file_exists($file)) {
            unlink($file);
        }
    }

    // Hapus gambar
    $stmt = $conn->prepare("DELETE FROM tb_kegiatan_gambar WHERE kegiatan_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Hapus kegiatan
    $stmt = $conn->prepare("DELETE FROM tb_kegiatan WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// ===================
// Handle Request POST
// ===================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_kegiatan'])) {
        if (tambahKegiatan($_POST, $_FILES['gambar'] ?? [])) {
            header("Location: ../tambah_galeri_kegiatan.php?success=1");
        } else {
            header("Location: ../tambah_galeri_kegiatan.php?error=1");
        }
        exit;
    }

    if (isset($_POST['edit_kegiatan'])) {
        $conn = dbConnect(); 
        $id = intval($_POST['id'] ?? 0);
        $judul = htmlspecialchars($_POST['judul'] ?? '');
        $deskripsi = htmlspecialchars($_POST['deskripsi'] ?? '');
        $tanggal = $_POST['tanggal'] ?? '';

        $stmt = $conn->prepare("UPDATE tb_kegiatan SET judul = ?, deskripsi = ?, tanggal_kegiatan = ? WHERE id = ?");
        $stmt->bind_param("sssi", $judul, $deskripsi, $tanggal, $id);

        if ($stmt->execute()) {
            if (!empty($_FILES['gambar']['name'][0])) {
                uploadMultipleKegiatanGambar($_FILES['gambar'], $id);
            }
            header("Location: ../tambah_galeri_kegiatan.php?updated=1");
        } else {
            header("Location: ../tambah_galeri_kegiatan.php?error=1");
        }
        exit;
    }
}

// ===================
// Handle Request GET
// ===================
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    if (hapusKegiatan($id)) {
        header("Location: ../tambah_galeri_kegiatan.php?deleted=1");
    } else {
        header("Location: ../tambah_galeri_kegiatan.php?error=1");
    }
    exit;
}
