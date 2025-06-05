<?php
require_once 'koneksi.php';

// Tambah Informasi Publik
function tambahInformasiPublik($data, $file)
{
    $conn = dbConnect();

    $judul    = htmlspecialchars($data['judul']);
    $kategori = htmlspecialchars($data['kategori']);
    $tahun    = htmlspecialchars($data['tahun'] ?? date('Y'));
    $tanggal_upload = $tahun . '-01-01'; // default tanggal dari tahun

    $fileName = '';
    if ($file['file']['name']) {
        $allowedExt = ['pdf'];
        $ext = strtolower(pathinfo($file['file']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExt)) {
            return ['status' => false, 'msg' => 'File harus berupa PDF'];
        }

        $fileName = uniqid() . '.' . $ext;
        move_uploaded_file($file['file']['tmp_name'], '../uploads/' . $fileName);
    }

    $stmt = $conn->prepare("INSERT INTO tb_informasi_publik (judul, kategori, tanggal_upload, tahun, file_path, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssss", $judul, $kategori, $tanggal_upload, $tahun, $fileName);

    if ($stmt->execute()) {
        return ['status' => true];
    } else {
        return ['status' => false, 'msg' => 'Gagal menyimpan data'];
    }
}

// Ambil Semua Informasi Publik
function getAllInformasiPublik()
{
    $conn = dbConnect();
    $result = $conn->query("SELECT * FROM tb_informasi_publik ORDER BY created_at DESC, id DESC");
    return $result;
}

// Ambil Satu Informasi Publik
function getInformasiPublikById($id)
{
    $conn = dbConnect();
    $stmt = $conn->prepare("SELECT * FROM tb_informasi_publik WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Edit Informasi Publik
function editInformasiPublik($id, $data, $file)
{
    $conn = dbConnect();
    $info = getInformasiPublikById($id);

    $judul    = htmlspecialchars($data['judul']);
    $kategori = htmlspecialchars($data['kategori']);
    $tahun    = htmlspecialchars($data['tahun'] ?? date('Y'));
    $tanggal_upload = $tahun . '-01-01';
    $fileName = $info['file_path'];

    if ($file['file']['name']) {
        $allowedExt = ['pdf'];
        $ext = strtolower(pathinfo($file['file']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExt)) {
            return ['status' => false, 'msg' => 'File harus berupa PDF'];
        }

        // Hapus file lama jika ada
        if (!empty($fileName) && file_exists('../uploads/' . $fileName)) {
            unlink('../uploads/' . $fileName);
        }

        $fileName = uniqid() . '.' . $ext;
        move_uploaded_file($file['file']['tmp_name'], '../uploads/' . $fileName);
    }

    $stmt = $conn->prepare("UPDATE tb_informasi_publik SET judul = ?, kategori = ?, tanggal_upload = ?, tahun = ?, file_path = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $judul, $kategori, $tanggal_upload, $tahun, $fileName, $id);

    if ($stmt->execute()) {
        return ['status' => true];
    } else {
        return ['status' => false, 'msg' => 'Gagal mengupdate data'];
    }
}

// Hapus Informasi Publik
function hapusInformasiPublik($id)
{
    $conn = dbConnect();
    $info = getInformasiPublikById($id);

    if (!empty($info['file_path']) && file_exists('../uploads/' . $info['file_path'])) {
        unlink('../uploads/' . $info['file_path']);
    }

    $stmt = $conn->prepare("DELETE FROM tb_informasi_publik WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// Eksekusi Tambah
if (isset($_POST['add_informasi_publik'])) {
    $result = tambahInformasiPublik($_POST, $_FILES);
    if ($result['status']) {
        header("Location: ../tambah_informasi_publik.php?success");
    } else {
        header("Location: ../tambah_informasi_publik.php?error=" . urlencode($result['msg']));
    }
    exit;
}

// Eksekusi Edit
if (isset($_POST['edit_informasi_publik'])) {
    $id = $_POST['id'] ?? null;
    $result = editInformasiPublik($id, $_POST, $_FILES);
    if ($result['status']) {
        header("Location: ../tambah_informasi_publik.php?updated");
    } else {
        header("Location: ../tambah_informasi_publik.php?error=" . urlencode($result['msg']));
    }
    exit;
}

// Eksekusi Hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    if (hapusInformasiPublik($id)) {
        header("Location: ../tambah_informasi_publik.php?deleted");
    } else {
        header("Location: ../tambah_informasi_publik.php?error=Gagal menghapus data");
    }
    exit;
}
