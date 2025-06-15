<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=surdurulebilir_yasam", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM activities WHERE user_email = ? ORDER BY activity_date DESC");
    $stmt->execute([$_SESSION['email']]);
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Veritabanı hatası: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Aktivite Listesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .table thead {
            background-color: #198754;
            color: white;
        }
        .action-btns .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>
    
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-success"><i class="bi bi-list-check me-2"></i>Aktivite Listesi</h2>
            <div>
                <a href="add_activity.php" class="btn btn-success">
                    <i class="bi bi-plus-circle me-1"></i>Yeni Aktivite
                </a>
            </div>
        </div>

        <?php if (count($activities) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Aktivite Türü</th>
                            <th>Tarih</th>
                            <th>Açıklama</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <?php foreach ($activities as $activity): ?>
                            <tr>
                                <td><?= htmlspecialchars($activity['activity_type']) ?></td>
                                <td><?= htmlspecialchars($activity['activity_date']) ?></td>
                                <td><?= htmlspecialchars($activity['description']) ?></td>
                                <td class="action-btns">
                                    <a href="edit_activity.php?id=<?= $activity['id'] ?>" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i> Düzenle
                                    </a>
                                    <a href="delete_activity.php?id=<?= $activity['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bu aktiviteyi silmek istediğinizden emin misiniz?')">
                                        <i class="bi bi-trash"></i> Sil
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info d-flex align-items-center">
                <i class="bi bi-info-circle-fill me-2"></i>
                <div>Henüz hiç aktivite eklenmemiş. Yeni bir aktivite eklemek için "Yeni Aktivite" butonuna tıklayın.</div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


