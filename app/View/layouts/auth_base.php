<?php
// file: /app/View/layouts/auth_base.php

if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../../config/paths.php';
}

$view = ViewManager::getInstance();
$flashMessage = $view->getVariable("flash_message");
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>    
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
            <h1 class="auth-title"><?= $view->getVariable("auth-title", i18n("Authentication")) ?></h1>
            <p class="auth-subtitle"><?= $view->getVariable("auth-subtitle", i18n("Welcome to TaskGroup")) ?></p>

        </div>

    <dialog class="">
        <p>AAAAAA</p>
    </dialog>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?= is_null($flashMessage) ? "" : $flashMessage ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
            </div>
        </div>
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