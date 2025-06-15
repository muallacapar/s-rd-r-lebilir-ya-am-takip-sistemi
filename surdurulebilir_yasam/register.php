<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=surdurulebilir_yasam", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("INSERT INTO users (email, password, name) VALUES (?, ?, ?)");
        $stmt->execute([
            $_POST['email'],
            password_hash($_POST['password'], PASSWORD_DEFAULT),
            $_POST['name'] ?? null
        ]);

        header("Location: login.php?registered=1");
        exit();
    } catch (PDOException $e) {
        $error = "Kayıt hatası: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .register-container {
            max-width: 450px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn-register {
            background-color: #198754;
            color: white;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="register-container">
            <div class="text-center mb-4">
                <i class="bi bi-person-plus-fill text-success" style="font-size: 2.5rem;"></i>
                <h2 class="mt-3">Yeni Hesap Oluştur</h2>
                <p class="text-muted">Sürdürülebilir yaşam takibine başlayın</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div><?= $error ?></div>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Adınız (Opsiyonel):</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="name" id="name" class="form-control">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">E-posta:</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label">Şifre:</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-register">
                        <i class="bi bi-person-plus me-1"></i>Kayıt Ol
                    </button>
                </div>
                
                <div class="text-center">
                    <p class="text-muted">Zaten hesabınız var mı? <a href="login.php">Giriş Yapın</a></p>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>