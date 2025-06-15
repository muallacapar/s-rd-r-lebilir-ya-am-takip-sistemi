<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';

// Sabit aktivite türleri (veritabanında yoksa kullanılacak)
$defaultActivityTypes = [
    'Geri Dönüşüm',
    'Su Tasarrufu',
    'Enerji Tasarrufu',
    'Kompost Yapımı',
    'Toplu Taşıma Kullanımı'
];

try {
    // Veritabanından aktivite türlerini çek
    $stmt = $pdo->query("SELECT DISTINCT activity_type FROM activities");
    $dbActivityTypes = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Veritabanında tür yoksa varsayılanları kullan
    $activityTypes = !empty($dbActivityTypes) ? array_unique(array_merge($dbActivityTypes, $defaultActivityTypes)) : $defaultActivityTypes;
    
} catch (PDOException $e) {
    // Hata durumunda varsayılan türleri kullan
    $activityTypes = $defaultActivityTypes;
    error_log("Aktivite türleri çekilirken hata: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $date = $_POST['date'];
    $description = $_POST['description'];

    // "Diğer" seçeneği seçilmişse ve yeni tür girilmişse
    if ($type === 'Diğer' && !empty($_POST['custom_type'])) {
        $type = trim($_POST['custom_type']);
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO activities (user_email, activity_type, activity_date, description) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['email'], $type, $date, $description]);
        header("Location: list_activities.php");
        exit();
    } catch (PDOException $e) {
        $error = "Ekleme hatası: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Aktivite Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        #customTypeContainer {
            display: none;
            margin-top: 10px;
        }
    </style>
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>
    
    <div class="container py-5">
        <div class="form-container">
            <h2 class="fw-bold text-success mb-4"><i class="bi bi-plus-circle me-2"></i>Yeni Aktivite Ekle</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div><?= htmlspecialchars($error) ?></div>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="type" class="form-label">Aktivite Türü:</label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="">Seçiniz</option>
                        <?php foreach ($activityTypes as $type): ?>
                            <option value="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($type) ?></option>
                        <?php endforeach; ?>
                        <option value="Diğer">Diğer (Kendiniz Yazın)</option>
                    </select>
                    <div id="customTypeContainer">
                        <label for="custom_type" class="form-label mt-2">Özel Tür:</label>
                        <input type="text" name="custom_type" id="custom_type" class="form-control" placeholder="Yeni aktivite türünü yazın">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="date" class="form-label">Tarih:</label>
                    <input type="date" name="date" id="date" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label">Açıklama:</label>
                    <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="list_activities.php" class="btn btn-outline-secondary me-md-2">
                        <i class="bi bi-x-circle me-1"></i>İptal
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save me-1"></i>Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Diğer seçeneği seçildiğinde özel tür alanını göster
        document.getElementById('type').addEventListener('change', function() {
            const customContainer = document.getElementById('customTypeContainer');
            if (this.value === 'Diğer') {
                customContainer.style.display = 'block';
                document.getElementById('custom_type').setAttribute('required', '');
            } else {
                customContainer.style.display = 'none';
                document.getElementById('custom_type').removeAttribute('required');
            }
        });
    </script>
</body>
</html>





