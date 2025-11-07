<?php
// file: /app/View/

if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../../config/paths.php';
}

$view = ViewManager::getInstance();
$currentUser = $view->getVariable("currentUsername");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Information-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Title -->
    <title><?= $view->getVariable("page-title", "TaskGroup -".i18n("Authentication Page")) ?></title>
    
    <!-- Importar iconno (Change to get fragment of it) -->
    <link rel="icon" type="image/x-icon" href="<?= IMAGES_PATH ?>/icons/favicon.ico">
    
    <!-- Importar estilos (Change to get fragment of it) -->
    <link rel="stylesheet" href="<?= CSS_PATH ?>/main.css">
</head>
<body class="auth-page">

    <div class="auth-container">
        <!-- Header -->
        <div class="auth-header">
            <!-- Logo -->
            <div class="auth-logo">
                <?= $view->getFragment("logo") ?>
            </div>
            <!-- Title and Subtitle -->
            <h1 class="auth-title"><?= i18n($view->getVariable("title")) ?></h1>
            <p class="auth-subtitle"><?= i18n($view->getVariable("subtitle")) ?></p>
        </div>

        <!-- Main Content -->
        <main>
            <?= $view->getFragment(ViewManager::DEFAULT_FRAGMENT) ?>
        </main>
        
        <div class="auth-footer">
            <?= $view->getFragment("footer"); ?>
        </div>

        <div id="errormessage" class="error-message" style="display: none;">
            <!-- Error messages will be displayed here -->
        </div>
    </div>

    <script src="<?= JS_PATH ?>/auth.js"></script>
</body>
</html>