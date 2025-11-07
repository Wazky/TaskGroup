<?php
// file: /app/View/

if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../../../config/paths.php';
}

$view = ViewManager::getInstance();
$currentUser = $view->getVariable("currentUsername");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskGroup - Register</title>
    
    <!-- Importar iconno-->
    <link rel="icon" type="image/x-icon" href="<?= IMAGES_PATH ?>/icons/favicon.ico">
    
    <!-- Importar estilos -->
    <link rel="stylesheet" href="<?= CSS_PATH ?>/main.css">
</head>
<body class="auth-page">

    <div class="auth-container">
        <div class="auth-header">
            <div class="auth-logo">
                <?php include __DIR__ . '/../../shared/components/logo.php'; ?>
            </div>
            <h1 class="auth-title">Create Account</h1>
            <p class="auth-subtitle">Join TaskGroup and start collaborating</p>
        </div>

        <form id="registerForm" class="auth-form" action="<?= BASE_URL ?>/app/View/auth/register.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Choose your alias" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>

            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Create a password" required>
                <div class="password-strength">
                    <div class="strength-bar">
                        <div class="strength-fill" id="passwordStrength"></div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat your password" required>
                <div class="error">Passwords do not match</div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create Account</button>
            </div>
        </form>
        
        <div class="auth-footer">
            <p>
                Already have an account?
                <a href="<?= BASE_URL ?>/app/View/pages/auth/login.php">Sign in here</a>
            </p>
        </div>

        <div id="errormessage" class="error-message" style="display: none;">
            <!-- Error messages will be displayed here -->
        </div>
    </div>

    <script src="<?= JS_PATH ?>/auth.js"></script>
</body>
</html>