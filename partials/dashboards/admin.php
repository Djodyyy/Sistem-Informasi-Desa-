<?php
// aktifkan error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// panggil koneksi dan function user
require_once 'functions/koneksi.php';
require_once 'functions/function_user.php';

// ambil semua data user
$users = getData();
$totalUsers = is_array($users) ? count($users) : 0;

// Ambil data user aktif per bulan selama 12 bulan terakhir dari DB
$conn = dbConnect();
$userStats = [];

// Siapkan array bulan dalam format singkat dan angka bulan (1-12)
$months = [];
for ($i=11; $i>=0; $i--) {
    $monthYear = date('Y-m', strtotime("-$i months")); // ex: 2024-06
    $label = date('M', strtotime("-$i months"));       // ex: Jun
    $months[$monthYear] = $label;
}

// Inisialisasi nilai 0 dulu untuk semua bulan
foreach ($months as $mY => $label) {
    $userStats[$label] = 0;
}

// Query untuk hitung user baru per bulan
$sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, COUNT(*) AS total 
        FROM tb_user 
        WHERE created_at >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 11 MONTH), '%Y-%m-01')
        GROUP BY ym";

$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ym = $row['ym'];
        $count = (int)$row['total'];
        if (isset($months[$ym])) {
            $label = $months[$ym];
            $userStats[$label] = $count;
        }
    }
}

$conn->close();

$labels = json_encode(array_keys($userStats));
$data = json_encode(array_values($userStats));
?>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<!-- Mulai konten halaman -->
<div class="container-fluid mt-4" style="min-height: 90vh;">
    <h2 class="mb-3">Selamat Datang, Admin!</h2>
    <p class="text-secondary">Berikut ini adalah ringkasan dan kontrol cepat sistem:</p>

    <div class="row g-4">
        <!-- Kartu Total User -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100 card-hover text-white" style="background: #4a90e2;">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title d-flex align-items-center gap-2">
                            <i class="bi bi-person-circle fs-4"></i>
                            Total User
                        </h5>
                        <p class="card-text fs-2 fw-bold"><?= htmlspecialchars($totalUsers) ?></p>
                    </div>
                    <div>
                        <div class="progress mb-2" style="height: 6px; background-color: rgba(255,255,255,0.3);">
                            <div class="progress-bar bg-light" role="progressbar" style="width: <?= min(100, $totalUsers * 5) ?>%;" aria-valuenow="<?= $totalUsers ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <a href="data_user.php" class="btn btn-light btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Kelola data user">Kelola User</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Statistik User di samping kanan -->
        <div class="col-md-8">
            <div class="card shadow-sm h-100 p-3">
                <h4>Statistik User Baru per Bulan (12 bulan terakhir)</h4>
                <canvas id="userChart" height="130"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- CSS efek hover -->
<style>
.card-hover {
    transition: all 0.3s ease;
    cursor: pointer;
}
.card-hover:hover {
    box-shadow: 0 0.5rem 1rem rgba(74, 144, 226, 0.7);
    transform: translateY(-5px);
}
</style>

<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Aktifkan tooltip Bootstrap dan buat grafik -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Tooltip bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Chart.js Bar Chart
    var ctx = document.getElementById('userChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= $labels ?>,
            datasets: [{
                label: 'User Baru',
                data: <?= $data ?>,
                backgroundColor: 'rgba(74, 144, 226, 0.7)',
                borderColor: 'rgba(74, 144, 226, 1)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            },
            responsive: true,
            plugins: {
                legend: { display: true },
                tooltip: { enabled: true }
            }
        }
    });
});
</script>
