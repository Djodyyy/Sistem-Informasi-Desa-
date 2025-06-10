<?php
// Dummy data jumlah total anggaran tahun ini
$totalAnggaran = 785000000; // misal dalam rupiah

// Dummy data grafik 12 bulan terakhir
$bulan = ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
$dataAnggaran = [40000000, 50000000, 30000000, 60000000, 45000000, 40000000, 55000000, 60000000, 52000000, 58000000, 61000000, 62000000];

// Encode untuk Chart.js
$labels = json_encode($bulan);
$data = json_encode($dataAnggaran);
?>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<!-- Konten Halaman -->
<div class="container-fluid mt-4" style="min-height: 90vh;">
    <h2 class="mb-3">Dashboard Keuangan</h2>
    <p class="text-secondary">Statistik dan informasi anggaran desa tahun berjalan.</p>

    <div class="row g-4">
        <!-- Kartu Total Anggaran -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100 card-hover text-white" style="background: #17a2b8;">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title d-flex align-items-center gap-2">
                            <i class="bi bi-cash-stack fs-4"></i>
                            Total Anggaran Tahun Ini
                        </h5>
                        <p class="card-text fs-2 fw-bold">Rp <?= number_format($totalAnggaran, 0, ',', '.') ?></p>
                    </div>
                    <div>
                        <div class="progress mb-2" style="height: 6px; background-color: rgba(255,255,255,0.3);">
                            <div class="progress-bar bg-light" role="progressbar" style="width: <?= min(100, $totalAnggaran / 10000000) ?>%;" aria-valuenow="<?= $totalAnggaran ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <a href="tambah_anggaran.php" class="btn btn-light btn-sm" data-bs-toggle="tooltip" title="Kelola Data Anggaran">Kelola Anggaran</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Statistik Anggaran -->
        <div class="col-md-8">
            <div class="card shadow-sm h-100 p-3">
                <h4>Penggunaan Anggaran Bulanan (12 Bulan Terakhir)</h4>
                <canvas id="anggaranChart" height="130"></canvas>
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
    box-shadow: 0 0.5rem 1rem rgba(23, 162, 184, 0.7);
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
    var ctx = document.getElementById('anggaranChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= $labels ?>,
            datasets: [{
                label: 'Penggunaan Anggaran (Rp)',
                data: <?= $data ?>,
                backgroundColor: 'rgba(23, 162, 184, 0.7)',
                borderColor: 'rgba(23, 162, 184, 1)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }
            },
            responsive: true,
            plugins: {
                legend: { display: true },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let val = context.parsed.y;
                            return 'Rp ' + val.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>
