<?php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../../../config/paths.php';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskGroup - Login</title>
    <!-- Importar iconno-->
    <link rel="icon" type="image/x-icon" href="<?= IMAGES_PATH ?>/icons/favicon.ico">
    
    <!-- Importar estilos -->
    <link rel="stylesheet" href="<?= CSS_PATH ?>/main.css">
</head>
<body class="auth-page">

    <div class="auth-container">
        
        <div class="auth-header">
            <div class="auth-logo">
                <?php include __DIR__ . '/../../shared/components/logo.php';?>
            </div>

            <h1 class="auth-title">Login</h1>
            <p class="auth-subtitle">Collaborative task manager</p>
        </div>
        <!-- Ajustar el action -->
        <form id="loginForm" class="auth-form" action="<?= BASE_URL?>/app/View/auth/login.php" method="POST">
            <div class="form-group">
                <label for="username">Username or Email:</label>
                <input type="text" id="username-email" name="username-email" placeholder="Enter your alias or email" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
        
        <div class="auth-footer">
            <p>
                Don't have an account?
                <a href="<?= BASE_URL ?>/app/View/pages/auth/register.php">Register here</a>
            </p>
        </div>

        <div id="errormessage" class="error-message" style="display: none;">
            <!-- Error messages will be displayed here -->
        </div>

    </div>

    <script src="/public/js/auth.js"></script>
</body>
</html>