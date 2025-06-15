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

    $stmt = $pdo->prepare("DELETE FROM activities WHERE id = ? AND user_email = ?");
    $stmt->execute([$_GET['id'], $_SESSION['email']]);
} catch (PDOException $e) {
    die("Veritabanı hatası: " . $e->getMessage());
}

header("Location: list_activities.php");
exit();
?>
