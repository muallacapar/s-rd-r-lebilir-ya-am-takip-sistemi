<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: list_activities.php");
    exit();
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=surdurulebilir_yasam", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM activities WHERE id = ? AND user_email = ?");
    $stmt->execute([$_GET['id'], $_SESSION['email']]);
    $activity = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$activity) {
        die("Aktivite bulunamadı.");
    }

    $stmtTypes = $pdo->query("SELECT DISTINCT activity_type FROM activities");
    $activityTypes = $stmtTypes->fetchAll(PDO::FETCH_COLUMN);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $type = $_POST['type'];
        $date = $_POST['date'];
        $description = $_POST['description'];

        $stmt = $pdo->prepare("UPDATE activities SET activity_type = ?, activity_date = ?, description = ? WHERE id = ? AND user_email = ?");
        $stmt->execute([$type, $date, $description, $_GET['id'], $_SESSION['email']]);
        header("Location: list_activities.php");
        exit();
    }
} catch (PDOException $e) {
    die("Veritabanı hatası: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Aktivite Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Aktivite Düzenle</h2>

    <form method="POST">
        <div class="mb-3">
            <label for="type">Aktivite Türü:</label>
            <select name="type" id="type" class="form-select" required>
                <option value="">Seçiniz</option>
                <?php foreach ($activityTypes as $typeOption): ?>
                    <option value="<?= htmlspecialchars($typeOption) ?>" <?= $activity['type'] === $typeOption ? 'selected' : '' ?>>
                        <?= htmlspecialchars($typeOption) ?>
                    </option>
                <?php endforeach; ?>
                <option value="Diğer" <?= $activity['type'] === 'Diğer' ? 'selected' : '' ?>>Diğer</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="date">Tarih:</label>
            <input type="date" name="date" id="date" class="form-control" value="<?= $activity['date'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="description">Açıklama:</label>
            <textarea name="description" id="description" class="form-control" rows="3" required><?= htmlspecialchars($activity['description']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Güncelle</button>
        <a href="activity_list.php" class="btn btn-secondary">İptal</a>
    </form>
</body>
</html>

