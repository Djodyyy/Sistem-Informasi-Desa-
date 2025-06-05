<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/koneksi.php';

function getAllStatistik() {
    $conn = dbConnect();
    $query = "SELECT * FROM tb_statistik ORDER BY tahun DESC";
    $result = $conn->query($query);
    $data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    } else {
        echo "Error getAllStatistik: " . $conn->error;
    }
    return $data;
}

function getStatistikById($id_statistik) {
    $conn = dbConnect();
    $id_statistik = intval($id_statistik);
    $query = "SELECT * FROM tb_statistik WHERE id_statistik = $id_statistik LIMIT 1";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

function insertStatistik($tahun, $jumlah_penduduk, $jumlah_laki, $jumlah_perempuan, $jumlah_ibu_hamil, $jumlah_lansia, $jumlah_balita, $keterangan = null) {
    $conn = dbConnect();

    $tahun = $conn->real_escape_string($tahun);
    $jumlah_penduduk = intval($jumlah_penduduk);
    $jumlah_laki = intval($jumlah_laki);
    $jumlah_perempuan = intval($jumlah_perempuan);
    $jumlah_ibu_hamil = intval($jumlah_ibu_hamil);
    $jumlah_lansia = intval($jumlah_lansia);
    $jumlah_balita = intval($jumlah_balita);
    $keterangan = $conn->real_escape_string($keterangan);

    $query = "INSERT INTO tb_statistik 
        (tahun, jumlah_penduduk, jumlah_laki, jumlah_perempuan, jumlah_ibu_hamil, jumlah_lansia, jumlah_balita, keterangan) 
        VALUES 
        ('$tahun', $jumlah_penduduk, $jumlah_laki, $jumlah_perempuan, $jumlah_ibu_hamil, $jumlah_lansia, $jumlah_balita, '$keterangan')";

    if (!$conn->query($query)) {
        echo "Error insertStatistik: " . $conn->error;
        return false;
    }
    return true;
}

function updateStatistik($id_statistik, $tahun, $jumlah_penduduk, $jumlah_laki, $jumlah_perempuan, $jumlah_ibu_hamil, $jumlah_lansia, $jumlah_balita, $keterangan = null) {
    $conn = dbConnect();

    $id_statistik = intval($id_statistik);
    $tahun = $conn->real_escape_string($tahun);
    $jumlah_penduduk = intval($jumlah_penduduk);
    $jumlah_laki = intval($jumlah_laki);
    $jumlah_perempuan = intval($jumlah_perempuan);
    $jumlah_ibu_hamil = intval($jumlah_ibu_hamil);
    $jumlah_lansia = intval($jumlah_lansia);
    $jumlah_balita = intval($jumlah_balita);
    $keterangan = $conn->real_escape_string($keterangan);

    $query = "UPDATE tb_statistik SET 
                tahun='$tahun',
                jumlah_penduduk=$jumlah_penduduk,
                jumlah_laki=$jumlah_laki,
                jumlah_perempuan=$jumlah_perempuan,
                jumlah_ibu_hamil=$jumlah_ibu_hamil,
                jumlah_lansia=$jumlah_lansia,
                jumlah_balita=$jumlah_balita,
                keterangan='$keterangan'
              WHERE id_statistik=$id_statistik";

    if (!$conn->query($query)) {
        echo "Error updateStatistik: " . $conn->error;
        return false;
    }
    return true;
}

function deleteStatistik($id_statistik) {
    $conn = dbConnect();
    $id_statistik = intval($id_statistik);

    $query = "DELETE FROM tb_statistik WHERE id_statistik = $id_statistik";

    if (!$conn->query($query)) {
        echo "Error deleteStatistik: " . $conn->error;
        return false;
    }
    return true;
}

// Proses tambah data dari form tambah_statistik.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_statistik'])) {
        $tahun = $_POST['tahun'] ?? '';
        $jumlah_penduduk = $_POST['jumlah_penduduk'] ?? 0;
        $jumlah_laki = $_POST['jumlah_laki'] ?? 0;
        $jumlah_perempuan = $_POST['jumlah_perempuan'] ?? 0;
        $jumlah_ibu_hamil = $_POST['jumlah_ibu_hamil'] ?? 0;
        $jumlah_lansia = $_POST['jumlah_lansia'] ?? 0;
        $jumlah_balita = $_POST['jumlah_balita'] ?? 0;
        $keterangan = $_POST['keterangan'] ?? '';

        if (insertStatistik($tahun, $jumlah_penduduk, $jumlah_laki, $jumlah_perempuan, $jumlah_ibu_hamil, $jumlah_lansia, $jumlah_balita, $keterangan)) {
            header('Location: ../tambah_statistik.php?msg=success');
            exit;
        } else {
            echo "Gagal menyimpan data ke database.";
        }
    }

    if (isset($_POST['edit_statistik'])) {
        $id_statistik = $_POST['id_statistik'] ?? 0;
        $tahun = $_POST['tahun'] ?? '';
        $jumlah_penduduk = $_POST['jumlah_penduduk'] ?? 0;
        $jumlah_laki = $_POST['jumlah_laki'] ?? 0;
        $jumlah_perempuan = $_POST['jumlah_perempuan'] ?? 0;
        $jumlah_ibu_hamil = $_POST['jumlah_ibu_hamil'] ?? 0;
        $jumlah_lansia = $_POST['jumlah_lansia'] ?? 0;
        $jumlah_balita = $_POST['jumlah_balita'] ?? 0;
        $keterangan = $_POST['keterangan'] ?? '';

        if (updateStatistik($id_statistik, $tahun, $jumlah_penduduk, $jumlah_laki, $jumlah_perempuan, $jumlah_ibu_hamil, $jumlah_lansia, $jumlah_balita, $keterangan)) {
            header('Location: ../tambah_statistik.php?msg=updated');
            exit;
        } else {
            echo "Gagal update data.";
        }
    }
}

// Proses hapus data lewat link GET
if (isset($_GET['hapus'])) {
    $id_statistik = intval($_GET['hapus']);
    if (deleteStatistik($id_statistik)) {
        header('Location: ../tambah_statistik.php?msg=deleted');
        exit;
    } else {
        echo "Gagal hapus data.";
    }
}
