<?php
if (isset($_SESSION['is_logged_in'])) {
    header("Location: index.php?page=dashboard");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Maintenance CCTV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-body">

    <div class="login-card">
        <div class="card-body p-2 p-md-5">
            
            <div class="text-center mb-4">
                <i class="bi bi-camera-video-fill login-icon"></i>
                <h3 class="login-title">CCTV Maintenance</h3>
                <p class="text-muted">Silakan login untuk melanjutkan</p>
            </div>
            
            <?php if (isset($_SESSION['login_error'])): ?>
                <div class="alert alert-danger py-2">
                    <?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
                </div>
            <?php endif; ?>
            
            <form action="index.php?page=proses_login" method="POST">
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" class="form-control" name="username" placeholder="Username" required>
                </div>
                <div class="input-group mb-4">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary fw-bold">LOGIN</button>
                </div>
                <div class="text-center text-muted">
                    <p><small>username:admin - password:admin123</small></p>
                </div>
            </form>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>