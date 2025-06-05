<?php
require_once 'koneksi.php';

// Ambil semua berita
function getAllBerita() {
  $conn = dbConnect();
  $sql = "SELECT * FROM tb_berita_artikel ORDER BY tanggal DESC";
  $result = $conn->query($sql);
  $berita = [];
  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $berita[] = $row;
    }
  }
  return $berita;
}

// Simpan berita baru
function tambahBerita($judul, $isi, $tanggal, $penulis, $gambar) {
  $conn = dbConnect();
  $stmt = $conn->prepare("INSERT INTO tb_berita_artikel (judul, isi, tanggal, penulis, gambar) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $judul, $isi, $tanggal, $penulis, $gambar);
  return $stmt->execute();
}

// Ambil berita terbaru untuk frontend (limit 3)
function getBeritaTerbaru($limit = 3) {
  $conn = dbConnect();
  $stmt = $conn->prepare("SELECT * FROM tb_berita_artikel ORDER BY tanggal DESC LIMIT ?");
  $stmt->bind_param("i", $limit);
  $stmt->execute();
  $result = $stmt->get_result();
  $berita = [];
  while ($row = $result->fetch_assoc()) {
    $berita[] = $row;
  }
  return $berita;
}

// === Proses Form Input dari Admin ===
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $judul = $_POST['judul'] ?? '';
  $isi = $_POST['isi'] ?? '';
  $tanggal = $_POST['tanggal'] ?? '';
  $penulis = $_POST['penulis'] ?? 'Admin';
  $gambar = '';

  // Proses upload gambar
  if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
    $target_dir = "../uploads/";
    if (!file_exists($target_dir)) {
      mkdir($target_dir, 0777, true);
    }

    $file_name = time() . "_" . basename($_FILES["gambar"]["name"]);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
      $gambar = $file_name;
    } else {
      echo "Gagal upload gambar.";
      exit;
    }
  }

  // Simpan ke database
  if (tambahBerita($judul, $isi, $tanggal, $penulis, $gambar)) {
    header("Location: ../tambah_artikel_berita.php?status=sukses");
    exit;
  } else {
    echo "Gagal menyimpan berita.";
    exit;
  }
}
// Hapus berita
if (isset($_GET['hapus'])) {
  $id = intval($_GET['hapus']);
  $conn = dbConnect();

  // Ambil nama file gambar (jika ada) untuk dihapus juga
  $query = $conn->prepare("SELECT gambar FROM tb_berita_artikel WHERE id_berita = ?");
  $query->bind_param("i", $id);
  $query->execute();
  $result = $query->get_result();
  $gambar = '';
  if ($row = $result->fetch_assoc()) {
    $gambar = $row['gambar'];
  }

  // Hapus dari database
  $stmt = $conn->prepare("DELETE FROM tb_berita_artikel WHERE id_berita = ?");
  $stmt->bind_param("i", $id);
  if ($stmt->execute()) {
    // Hapus gambar jika ada
    if (!empty($gambar)) {
      $filePath = "../uploads/" . $gambar;
      if (file_exists($filePath)) {
        unlink($filePath);
      }
    }
    header("Location: ../tambah_artikel_berita.php?deleted=1");
    exit;
  } else {
    header("Location: ../tambah_artikel_berita.php?error=1");
    exit;
  }
}
?>
