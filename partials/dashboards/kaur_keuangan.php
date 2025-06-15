<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'functions/function_anggaran.php';

// Ambil semua data anggaran
$anggaran = getAllAnggaran();
$tahunIni = date('Y');

// Inisialisasi total & data bulanan
$totalAnggaran = 0;
$dataBulanan = array_fill(1, 12, 0);

foreach ($anggaran as $item) {
    if ($item['tahun'] == $tahunIni) {
        $totalAnggaran += intval($item['anggaran']);

        // Deteksi bulan dari deskripsi
        $desc = strtolower($item['deskripsi']);
        if (strpos($desc, 'januari') !== false) $dataBulanan[1] += intval($item['anggaran']);
        if (strpos($desc, 'februari') !== false) $dataBulanan[2] += intval($item['anggaran']);
        if (strpos($desc, 'maret') !== false) $dataBulanan[3] += intval($item['anggaran']);
        if (strpos($desc, 'april') !== false) $dataBulanan[4] += intval($item['anggaran']);
        if (strpos($desc, 'mei') !== false) $dataBulanan[5] += intval($item['anggaran']);
        if (strpos($desc, 'juni') !== false) $dataBulanan[6] += intval($item['anggaran']);
        if (strpos($desc, 'juli') !== false) $dataBulanan[7] += intval($item['anggaran']);
        if (strpos($desc, 'agustus') !== false) $dataBulanan[8] += intval($item['anggaran']);
        if (strpos($desc, 'september') !== false) $dataBulanan[9] += intval($item['anggaran']);
        if (strpos($desc, 'oktober') !== false) $dataBulanan[10] += intval($item['anggaran']);
        if (strpos($desc, 'november') !== false) $dataBulanan[11] += intval($item['anggaran']);
        if (strpos($desc, 'desember') !== false) $dataBulanan[12] += intval($item['anggaran']);
    }
}

// Untuk chart
$bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$dataAnggaran = [];
for ($i = 1; $i <= 12; $i++) {
    $dataAnggaran[] = $dataBulanan[$i];
}

$labels = json_encode($bulan);
$data = json_encode($dataAnggaran);
?>

<!-- HTML bagian -->
<div class="container">
    <h2 class="mt-4">Dashboard Keuangan</h2>
    <p>Statistik dan informasi anggaran desa tahun <?= $tahunIni; ?>.</p>

    <div class="row">
        <div class="col-lg-4">
            <div class="card p-4 shadow-sm bg-info text-white">
                <h5>Total Anggaran Tahun Ini</h5>
                <h3>Rp <?= number_format($totalAnggaran, 0, ',', '.'); ?></h3>
            </div>
        </div>
        <div class="col-lg-8">
            <canvas id="chartAnggaran"></canvas>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chartAnggaran').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= $labels; ?>,
            datasets: [{
                label: 'Penggunaan Anggaran (Rp)',
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgb(75, 192, 192)',
                data: <?= $data; ?>
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
