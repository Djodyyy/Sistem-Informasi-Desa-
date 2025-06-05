<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('koneksi.php');
 // Ganti path ini kalau file kamu beda struktur

$conn = dbConnect();
if (!$conn) {
    die("Koneksi database gagal.");
}

// Fungsi ambil semua data
function getAllMenus() {
    global $conn;
    $sql = "SELECT * FROM tb_konten ORDER BY id DESC";
    $result = $conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

// Tambah atau Update konten
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $title = $_POST['title'];
    $sub_title = !empty($_POST['sub_title']) ? $_POST['sub_title'] : null;
    $link = $_POST['link'];
    $content = !empty($_POST['content']) ? $_POST['content'] : null;
    $now = date('Y-m-d H:i:s');

    // Cek apakah ada file di-upload
    $file_upload = null;
    if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['file_upload']['tmp_name'];
        $file_name = time() . "_" . basename($_FILES['file_upload']['name']);
        $target_path = "../uploads/" . $file_name;

        if (move_uploaded_file($file_tmp, $target_path)) {
            $file_upload = $file_name;
        }
    }

    // Jika ID ada, berarti update
    if ($id) {
        $sql = "UPDATE tb_konten SET title = ?, sub_title = ?, link = ?, content = ?, updated_at = ?";
        if ($file_upload) {
            $sql .= ", file = ?";
        }
        $sql .= " WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($file_upload) {
            $stmt->bind_param("ssssssi", $title, $sub_title, $link, $content, $now, $file_upload, $id);
        } else {
            $stmt->bind_param("sssssi", $title, $sub_title, $link, $content, $now, $id);
        }
    } else {
        // Tambah konten baru
        $sql = "INSERT INTO tb_konten (title, sub_title, link, content, file, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, 1, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $title, $sub_title, $link, $content, $file_upload, $now, $now);
    }

    if ($stmt->execute()) {
        header("Location: ../tambah_konten.php");
        exit;
    } else {
        die("Error saat menyimpan konten: " . $stmt->error);
    }
}

// Hapus konten
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $stmt = $conn->prepare("DELETE FROM tb_konten WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: ../tambah_konten.php");
        exit;
    } else {
        die("Error saat menghapus konten: " . $stmt->error);
    }
}
