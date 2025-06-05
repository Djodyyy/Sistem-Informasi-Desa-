<?php
require_once 'koneksi.php';

// Tambah Produk Hukum
function tambahProdukHukum($data, $file)
{
    $conn = dbConnect();

    $judul     = htmlspecialchars($data['judul']);
    $kategori  = htmlspecialchars($data['kategori']);
    $tahun     = htmlspecialchars($data['tahun']);

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

    $stmt = $conn->prepare("INSERT INTO tb_produk_hukum (judul, kategori, tahun, file) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $judul, $kategori, $tahun, $fileName);

    if ($stmt->execute()) {
        return ['status' => true];
    } else {
        return ['status' => false, 'msg' => 'Gagal menyimpan data'];
    }
}

// Ambil Semua Produk Hukum
function getAllProdukHukum()
{
    $conn = dbConnect();
    $result = $conn->query("SELECT * FROM tb_produk_hukum ORDER BY tahun DESC, id_produk DESC");
    return $result;
}

// Ambil Satu Produk Hukum
function getProdukHukumById($id)
{
    $conn = dbConnect();
    $stmt = $conn->prepare("SELECT * FROM tb_produk_hukum WHERE id_produk = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Edit Produk Hukum
function editProdukHukum($id, $data, $file)
{
    $conn = dbConnect();
    $produk = getProdukHukumById($id);

    $judul    = htmlspecialchars($data['judul']);
    $kategori = htmlspecialchars($data['kategori']);
    $tahun    = htmlspecialchars($data['tahun']);
    $fileName = $produk['file'];

    if ($file['file']['name']) {
        $allowedExt = ['pdf'];
        $ext = strtolower(pathinfo($file['file']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExt)) {
            return ['status' => false, 'msg' => 'File harus berupa PDF'];
        }

        // Hapus file lama
        if (!empty($fileName) && file_exists('../uploads/' . $fileName)) {
            unlink('../uploads/' . $fileName);
        }

        $fileName = uniqid() . '.' . $ext;
        move_uploaded_file($file['file']['tmp_name'], '../uploads/' . $fileName);
    }

    $stmt = $conn->prepare("UPDATE tb_produk_hukum SET judul = ?, kategori = ?, tahun = ?, file = ? WHERE id_produk = ?");
    $stmt->bind_param("ssisi", $judul, $kategori, $tahun, $fileName, $id);

    if ($stmt->execute()) {
        return ['status' => true];
    } else {
        return ['status' => false, 'msg' => 'Gagal mengupdate data'];
    }
}

// Hapus Produk Hukum
function hapusProdukHukum($id)
{
    $conn = dbConnect();
    $produk = getProdukHukumById($id);

    if (!empty($produk['file']) && file_exists('../uploads/' . $produk['file'])) {
        unlink('../uploads/' . $produk['file']);
    }

    $stmt = $conn->prepare("DELETE FROM tb_produk_hukum WHERE id_produk = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// Eksekusi Tambah
if (isset($_POST['add_produk_hukum'])) {
    $result = tambahProdukHukum($_POST, $_FILES);
    if ($result['status']) {
        header("Location: ../tambah_produk_hukum.php?success");
    } else {
        header("Location: ../tambah_produk_hukum.php?error=" . urlencode($result['msg']));
    }
    exit;
}

// Eksekusi Edit
if (isset($_POST['edit_produk_hukum'])) {
    $id = $_POST['id_produk'] ?? null; // GANTI DARI id_produk_hukum
    $result = editProdukHukum($id, $_POST, $_FILES);
    if ($result['status']) {
        header("Location: ../tambah_produk_hukum.php?updated");
    } else {
        header("Location: ../tambah_produk_hukum.php?error=" . urlencode($result['msg']));
    }
    exit;
}

// Eksekusi Hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    if (hapusProdukHukum($id)) {
        header("Location: ../tambah_produk_hukum.php?deleted");
    } else {
        header("Location: ../tambah_produk_hukum.php?error=Gagal menghapus data");
    }
    exit;
}
