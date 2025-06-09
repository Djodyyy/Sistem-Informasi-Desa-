<?php

// panggil koneksi dan function user
require_once 'functions/koneksi.php';
require_once 'functions/function_user.php';

// Dummy data jumlah total permohonan
$totalPermohonan = 126;

// Dummy data statistik 12 bulan terakhir
$bulan = ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
$dataPermohonan = [5, 12, 8, 14, 10, 7, 9, 15, 11, 13, 18, 14];

// Encode untuk Chart.js
$labels = json_encode($bulan);
$data = json_encode($dataPermohonan);
?>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<!-- Konten Halaman -->
<div class="container-fluid mt-4" style="min-height: 90vh;">
    <h2 class="mb-3">Dashboard Pelayanan</h2>
    <p class="text-secondary">Statistik dan informasi pelayanan surat permohonan warga.</p>

    <div class="row g-4">
        <!-- Kartu Total Permohonan -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100 card-hover text-white" style="background: #28a745;">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title d-flex align-items-center gap-2">
                            <i class="bi bi-envelope-paper-fill fs-4"></i>
                            Total Permohonan
                        </h5>
                        <p class="card-text fs-2 fw-bold"><?= htmlspecialchars($totalPermohonan) ?></p>
                    </div>
                    <div>
                        <div class="progress mb-2" style="height: 6px; background-color: rgba(255,255,255,0.3);">
                            <div class="progress-bar bg-light" role="progressbar" style="width: <?= min(100, $totalPermohonan * 0.5) ?>%;" aria-valuenow="<?= $totalPermohonan ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <a href="data_permohonan.php" class="btn btn-light btn-sm" data-bs-toggle="tooltip" title="Kelola Permohonan">Kelola Permohonan</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Statistik Permohonan -->
        <div class="col-md-8">
            <div class="card shadow-sm h-100 p-3">
                <h4>Statistik Permohonan Surat (12 Bulan Terakhir)</h4>
                <canvas id="permohonanChart" height="130"></canvas>
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
    box-shadow: 0 0.5rem 1rem rgba(40, 167, 69, 0.7);
    transform: translateY(-5px);
}
</style>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Tooltip dan Grafik -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Tooltip Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Chart.js Bar Chart
    var ctx = document.getElementById('permohonanChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= $labels ?>,
            datasets: [{
                label: 'Jumlah Permohonan',
                data: <?= $data ?>,
                backgroundColor: 'rgba(40, 167, 69, 0.7)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 2 }
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
