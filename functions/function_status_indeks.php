<?php
require_once 'koneksi.php';

// Fungsi untuk menentukan status indeks dari nilai
function hitungIndeks($nilai) {
    if ($nilai >= 75) return "Mandiri";
    elseif ($nilai >= 65) return "Maju";
    elseif ($nilai >= 50) return "Berkembang";
    elseif ($nilai >= 35) return "Tertinggal";
    else return "Sangat Tertinggal";
}

// Tambah data status indeks
function tambahStatusIndeks($tahun, $nilai, $keterangan) {
    $conn = dbConnect();
    $indeks = hitungIndeks($nilai);
    $stmt = $conn->prepare("INSERT INTO tb_status_indeks (tahun, nilai, indeks, keterangan) VALUES (?, ?, ?, ?)");
    if (!$stmt) return false;
    $stmt->bind_param("sdss", $tahun, $nilai, $indeks, $keterangan);
    return $stmt->execute();
}

// Ambil semua tahun
function getAllTahunIndeks() {
    $conn = dbConnect();
    $result = $conn->query("SELECT DISTINCT tahun FROM tb_status_indeks ORDER BY tahun DESC");
    $tahunList = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $tahunList[] = $row['tahun'];
        }
    }
    return $tahunList;
}

// Ambil data per tahun
function getIndeksByTahun($tahun) {
    $conn = dbConnect();
    $stmt = $conn->prepare("SELECT * FROM tb_status_indeks WHERE tahun = ? ORDER BY id ASC");
    if (!$stmt) return [];
    $stmt->bind_param("s", $tahun);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

// Update data
function updateStatusIndeks($id, $tahun, $nilai, $keterangan) {
    $conn = dbConnect();
    $indeks = hitungIndeks($nilai);
    $stmt = $conn->prepare("UPDATE tb_status_indeks SET tahun = ?, nilai = ?, indeks = ?, keterangan = ? WHERE id = ?");
    if (!$stmt) return false;
    $stmt->bind_param("sdssi", $tahun, $nilai, $indeks, $keterangan, $id);
    return $stmt->execute();
}

// Hapus data
function deleteStatusIndeks($id) {
    $conn = dbConnect();
    $stmt = $conn->prepare("DELETE FROM tb_status_indeks WHERE id = ?");
    if (!$stmt) return false;
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
function getDataStatusIndeks() {
    $conn = dbConnect();
    $sql = "SELECT * FROM tb_status_indeks ORDER BY tahun DESC";
    $result = $conn->query($sql);
    $data = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
}


// Proses request
function prosesRequestStatusIndeks() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_indeks'])) {
            $tahun = trim($_POST['tahun'] ?? '');
            $nilai = trim($_POST['nilai'] ?? '');
            $keterangan = trim($_POST['keterangan'] ?? '');

            if ($tahun && is_numeric($nilai)) {
                if (tambahStatusIndeks($tahun, (float)$nilai, $keterangan)) {
                    header("Location: ../tambah_status_indeks.php?success=add");
                    exit;
                } else {
                    header("Location: ../tambah_status_indeks.php?error=add");
                    exit;
                }
            }
        }

        if (isset($_POST['edit_indeks'])) {
            $id = intval($_POST['id'] ?? 0);
            $tahun = trim($_POST['tahun'] ?? '');
            $nilai = trim($_POST['nilai'] ?? '');
            $keterangan = trim($_POST['keterangan'] ?? '');

            if ($id > 0 && $tahun && is_numeric($nilai)) {
                if (updateStatusIndeks($id, $tahun, (float)$nilai, $keterangan)) {
                    header("Location: ../tambah_status_indeks.php?tahun=$tahun&success=edit");
                    exit;
                } else {
                    header("Location: ../tambah_status_indeks.php?tahun=$tahun&error=edit");
                    exit;
                }
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['hapus'])) {
        $id = intval($_GET['hapus']);
        if ($id > 0 && deleteStatusIndeks($id)) {
            header("Location: ../tambah_status_indeks.php?success=delete");
            exit;
        } else {
            header("Location: ../tambah_status_indeks.php?error=delete");
            exit;
        }
    }
}
prosesRequestStatusIndeks(); // <== tambahkan ini biar request diproses
?>
