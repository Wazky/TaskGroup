<?php
// file: /app/View/

if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../../config/paths.php';
}

$view = ViewManager::getInstance();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Information-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Title -->
    <title><?= "TaskGroup - ".$view->getVariable("page-title", i18n("Authentication Page")) ?></title>
    
    <!-- Importar icono (Change to get fragment of it) -->
    <link rel="icon" type="image/x-icon" href="<?= IMAGES_PATH ?>/icons/favicon.ico">
    
    <!-- Importar Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Importar estilos (Change to get fragment of it) -->
    <link rel="stylesheet" href="<?= CSS_PATH ?>/main.css">

    <!-- Importar scripts -->
    <script src="<?= JS_PATH ?>/auth.js"></script>
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
            <h1 class="auth-title"><?= $view->getVariable("auth-title") ?></h1>
            <p class="auth-subtitle"><?= i18n($view->getVariable("auth-subtitle")) ?></p>
        </div>

        <!-- Main Content -->
        <main>
            <?= $view->getFragment(ViewManager::DEFAULT_FRAGMENT) ?>
        </main>
        
        <div class="auth-footer">
            <?= $view->getFragment("footer"); ?>
        </div>

    </div>

    <script src="<?= JS_PATH ?>/auth.js"></script>
</body>
</html>