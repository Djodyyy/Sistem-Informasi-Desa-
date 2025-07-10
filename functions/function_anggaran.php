<?php
require_once 'koneksi.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// =====================
// Handle Hapus Anggaran
// =====================
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    if (hapusAnggaran($id)) {
        header("Location: ../tambah_anggaran.php?deleted=1");
    } else {
        header("Location: ../tambah_anggaran.php?error=1");
    }
    exit;
}

// ======================
// Get All Anggaran + Foto
// ======================
function getAllAnggaran()
{
    $conn = dbConnect();
    $query = "
        SELECT 
            a.id_anggaran,
            a.deskripsi,
            a.bulan,
            a.anggaran,
            a.realisasi,
            a.keterangan,
            GROUP_CONCAT(d.file_foto SEPARATOR ',') AS file_foto
        FROM tb_anggaran a
        LEFT JOIN tb_anggaran_dokumentasi d ON a.id_anggaran = d.id_anggaran
        GROUP BY a.id_anggaran
        ORDER BY STR_TO_DATE(
            TRIM(SUBSTRING_INDEX(a.bulan, 's/d', 1)), 
            '%M %Y'
        ) ASC
    ";

    $result = $conn->query($query);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $row['file_foto'] = !empty($row['file_foto']) ? explode(',', $row['file_foto']) : [];
        $data[] = $row;
    }

    return $data;
}




// =====================
// Tambah Anggaran
// =====================
function tambahAnggaran($data, $files)
{
    $conn = dbConnect();

    $deskripsi  = htmlspecialchars($data['deskripsi'] ?? '');
    $bulan      = htmlspecialchars($data['bulan'] ?? ''); // simpan sebagai string
    $anggaran   = (float)str_replace(['.', ','], '', $data['anggaran'] ?? '0');
    $realisasi  = (float)str_replace(['.', ','], '', $data['realisasi'] ?? '0');
    $keterangan = htmlspecialchars($data['keterangan'] ?? '');

    $stmt = $conn->prepare("INSERT INTO tb_anggaran (deskripsi, bulan, anggaran, realisasi, keterangan, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssdss", $deskripsi, $bulan, $anggaran, $realisasi, $keterangan);

    if ($stmt->execute()) {
        $id_anggaran = $stmt->insert_id;
        if (!empty($files['name'][0])) {
            uploadMultipleDokumentasi($files, $id_anggaran);
        }
        return true;
    }
    return false;
}

// =====================
// Update Anggaran
// =====================
function updateAnggaran($data, $files)
{
    $conn = dbConnect();
    $id         = $data['id_anggaran'];
    $deskripsi  = htmlspecialchars($data['deskripsi']);
    $bulan      = htmlspecialchars($data['bulan']);
    $anggaran   = str_replace(['.', ','], '', $data['anggaran']);
    $realisasi  = str_replace(['.', ','], '', $data['realisasi']);
    $keterangan = htmlspecialchars($data['keterangan']);

    $stmt = $conn->prepare("UPDATE tb_anggaran SET deskripsi=?, bulan=?, anggaran=?, realisasi=?, keterangan=? WHERE id_anggaran=?");
    $stmt->bind_param("ssdssi", $deskripsi, $bulan, $anggaran, $realisasi, $keterangan, $id);
    if ($stmt->execute()) {
        if (!empty($files['name'][0])) {
            uploadMultipleDokumentasi($files, $id);
        }
        return true;
    }
    return false;
}

// ==============================
// Upload Banyak Dokumentasi Foto
// ==============================
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

// ===================
// Hapus Anggaran + Foto
// ===================
function hapusAnggaran($id)
{
    $conn = dbConnect();

    $stmt = $conn->prepare("SELECT file_foto FROM tb_anggaran_dokumentasi WHERE id_anggaran = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $uploadDir = '../uploads/anggaran/';
    while ($row = $result->fetch_assoc()) {
        $filePath = $uploadDir . $row['file_foto'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    $stmt = $conn->prepare("DELETE FROM tb_anggaran_dokumentasi WHERE id_anggaran = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM tb_anggaran WHERE id_anggaran = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// ==================
// Handle POST Request
// ==================
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
