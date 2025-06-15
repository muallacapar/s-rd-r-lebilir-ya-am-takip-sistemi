<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <title>Anasayfa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    .welcome-card {
      background: linear-gradient(135deg, #d4edda, #c3e6cb);
      border-radius: 15px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .feature-card {
      transition: transform 0.3s;
      border-radius: 10px;
    }
    .feature-card:hover {
      transform: translateY(-5px);
    }
  </style>
</head>
<body class="bg-light">
  <?php include 'navbar.php'; ?>

  <div class="container py-5">
    <div class="welcome-card p-5 mb-5 text-center">
      <h1 class="display-4 fw-bold text-success">Hoşgeldiniz, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Kullanıcı') ?></h1>
      <p class="lead">Sürdürülebilir yaşam takibine hoşgeldiniz.</p>
    </div>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="feature-card card h-100 border-success">
          <div class="card-body text-center">
            <i class="bi bi-plus-circle text-success" style="font-size: 2rem;"></i>
            <h3 class="h5 mt-3">Aktivite Ekle</h3>
            <p class="text-muted">Yeni sürdürülebilir aktivitelerinizi kaydedin</p>
            <a href="add_activity.php" class="btn btn-outline-success">Ekle</a>
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="feature-card card h-100 border-success">
          <div class="card-body text-center">
            <i class="bi bi-list-check text-success" style="font-size: 2rem;"></i>
            <h3 class="h5 mt-3">Aktivitelerim</h3>
            <p class="text-muted">Kayıtlı aktivitelerinizi görüntüleyin</p>
            <a href="list_activities.php" class="btn btn-outline-success">Listele</a>
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="feature-card card h-100 border-success">
          <div class="card-body text-center">
            <i class="bi bi-pie-chart text-success" style="font-size: 2rem;"></i>
            <h3 class="h5 mt-3">İstatistikler</h3>
            <p class="text-muted">Aktivitelerinizin grafiksel görünümü</p>
            <a href="chart.php" class="btn btn-outline-success">Görüntüle</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
