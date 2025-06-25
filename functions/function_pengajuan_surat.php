<?php
include 'koneksi.php';

function tambahPengajuanSurat($nik, $nama, $jenis, $keterangan) {
    $conn = dbConnect();
    $stmt = $conn->prepare("INSERT INTO tb_pengajuan_surat (nik, nama, jenis_surat, keterangan) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nik, $nama, $jenis, $keterangan);
    return $stmt->execute();
}

function getPengajuanByNIK($nik) {
    $conn = dbConnect();
    $stmt = $conn->prepare("SELECT * FROM tb_pengajuan_surat WHERE nik = ? ORDER BY tanggal_pengajuan DESC");
    $stmt->bind_param("s", $nik);
    $stmt->execute();
    return $stmt->get_result();
}
?>
