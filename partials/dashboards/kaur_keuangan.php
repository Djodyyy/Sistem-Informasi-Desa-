<?php
require_once 'functions/function_anggaran.php';
require_once 'functions/function_pembangunan.php';



$tahunIni = date('Y');

// Ambil semua data anggaran & pembangunan
$anggaran = getAllAnggaran();
$pembangunan = getAllPembangunan();

$totalAnggaran = 0;
$totalPembangunan = 0;
$dataBulanan = array_fill(1, 12, 0); // index 1 = Jan, 12 = Des

$bulanMap = [
    'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4,
    'mei' => 5, 'juni' => 6, 'juli' => 7, 'agustus' => 8,
    'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12,
    'january' => 1, 'february' => 2, 'march' => 3,
    'may' => 5, 'june' => 6, 'july' => 7, 'august' => 8,
    'october' => 10, 'november' => 11, 'december' => 12
];

// =====================
// Proses Anggaran
// =====================
foreach ($anggaran as $item) {
    $bulanStr = strtolower($item['bulan']);
    preg_match_all('/([a-z]+)\s*(\d{4})/i', $bulanStr, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
        $namaBulan = strtolower($match[1]);
        $tahun = $match[2];

        if ($tahun == $tahunIni && isset($bulanMap[$namaBulan])) {
            $jumlah = (int) $item['anggaran'];
            $totalAnggaran += $jumlah;
            $indexBulan = $bulanMap[$namaBulan];
            $dataBulanan[$indexBulan] += $jumlah;
            break;
        }
    }
}

// =====================
// Proses Pembangunan
// =====================
foreach ($pembangunan as $item) {
    $bulanStr = strtolower($item['bulan']);
    preg_match_all('/([a-z]+)\s*(\d{4})/i', $bulanStr, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
        $namaBulan = strtolower($match[1]);
        $tahun = $match[2];

        if ($tahun == $tahunIni && isset($bulanMap[$namaBulan])) {
            $jumlah = (int) $item['anggaran'];
            $totalPembangunan += $jumlah;
            $indexBulan = $bulanMap[$namaBulan];
            $dataBulanan[$indexBulan] += $jumlah;
            break;
        }
    }
}

$totalKeseluruhan = $totalAnggaran + $totalPembangunan;

$labelBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$dataAnggaran = [];
for ($i = 1; $i <= 12; $i++) {
    $dataAnggaran[] = $dataBulanan[$i];
}

$labels = json_encode($labelBulan);
$data = json_encode($dataAnggaran);

?>

<div class="container mt-4">
    <h2>Dashboard Keuangan</h2>
    <p>Statistik dan informasi anggaran desa tahun <?= $tahunIni; ?>.</p>

    <div class="row">
        <div class="col-lg-4">
            <div class="card p-4 shadow-sm bg-info text-white">
                <h5>Total Anggaran + Pembangunan Tahun Ini</h5>
                <h3>Rp <?= number_format($totalKeseluruhan, 0, ',', '.'); ?></h3>
                <small>(Anggaran: Rp <?= number_format($totalAnggaran, 0, ',', '.'); ?> + Pembangunan: Rp <?= number_format($totalPembangunan, 0, ',', '.'); ?>)</small>
            </div>
        </div>
        <div class="col-lg-8">
            <div style="height: 300px;">
                <canvas id="chartAnggaran"></canvas>
            </div>
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
                label: 'Total Penggunaan Anggaran (Rp)',
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgb(75, 192, 192)',
                data: <?= $data; ?>
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>