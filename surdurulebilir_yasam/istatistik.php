<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT DATE(activity_date) as activity_date, COUNT(*) as count 
    FROM activities 
    WHERE user_email = ? AND activity_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(activity_date)
");
$stmt->execute([$_SESSION['email']]);
$results = $stmt->fetchAll();

// Tarihlere göre sıralayıp eksik günleri 0 ile dolduralım
$data = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $data[$date] = 0;
}
foreach ($results as $row) {
    $data[$row['activity_date']] = $row['count'];
}

$labels = json_encode(array_keys($data));
$counts = json_encode(array_values($data));
?>

<!DOCTYPE html>
<html>
<head>
    <title>İstatistikler</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .chart-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>
    
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-success"><i class="bi bi-bar-chart me-2"></i>Son 7 Günde Yaptığın Aktiviteler</h2>
            <a href="dashboard.php" class="btn btn-outline-success">
                <i class="bi bi-arrow-left me-1"></i>Geri Dön
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="chart-container">
                    <canvas id="weeklyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('weeklyChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= $labels ?>,
                datasets: [{
                    label: 'Günlük Aktivite Sayısı',
                    data: <?= $counts ?>,
                    backgroundColor: 'rgba(25, 135, 84, 0.7)',
                    borderColor: 'rgba(25, 135, 84, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { 
                        beginAtZero: true, 
                        stepSize: 1,
                        title: {
                            display: true,
                            text: 'Aktivite Sayısı'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tarih'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' aktivite';
                            }
                        }
                    }
                }
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>