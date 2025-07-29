<?php
// Simulasi login (untuk dummy/UAT)
session_start();
$_SESSION['login'] = true;
$_SESSION['role_id'] = 1;

// Total user dummy
$totalUsers = 25;

// Data dummy statistik user per bulan
$labels = json_encode(['Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul']);
$data = json_encode([1, 2, 1, 4, 3, 2, 1, 5, 2, 3, 0, 1]);
?>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<!-- Konten -->
<div class="container-fluid mt-4" style="min-height: 90vh;">
    <h2 class="mb-3">Selamat Datang, Admin!</h2>
    <p class="text-secondary">Ini adalah tampilan dummy untuk dashboard admin.</p>

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
                        <a href="#" class="btn btn-light btn-sm" title="Kelola data user (non-aktif)">Kelola User</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Statistik Dummy -->
        <div class="col-md-8">
            <div class="card shadow-sm h-100 p-3">
                <h4>Statistik User Baru per Bulan</h4>
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

<!-- Aktifkan Chart -->
<script>
document.addEventListener('DOMContentLoaded', function () {
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
