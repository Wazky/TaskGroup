<?php
// file: app/View/layouts/dashboard.php

if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../../config/paths.php';
}

$view = ViewManager::getInstance();
$errors = $view->getVariable("errors");
$currentUser = $view->getVariable("current_user");

$flashMessage = $view->popFlash();
$flashType = $view->getVariable("flash-type");
$flashIcon = $view->getVariable("flash-icon");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= "TaskGroup - " . $view->getVariable("page-title", i18n("Default Title"))  ?></title>

    <!-- Importar iconno-->
    <link rel="icon" type="image/x-icon" href="<?= IMAGES_PATH ?>/icons/favicon.ico">
    
    <!-- Importar Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


    <!-- Importar estilos -->
    <link rel="stylesheet" href="<?= CSS_PATH ?>/main.css">

    <!-- Importar scripts -->
    <script type="module" src="<?= JS_PATH ?>/utils.js"></script>
    <script src="<?= JS_PATH ?>/confirmActionModal.js"></script>
    <script src="<?= JS_PATH ?>/closableAlert.js"></script>

    <?= $view->getFragment("javascript"); ?>

</head>
<body class="dashboard-page bg-tg-dark">

    <!-- ===== HEADER ===== -->
    <header class="main-header bg-tg-dark shadow-sm sticky-top">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between py-2">
                
                <!-- Logo y Menú -->
                <div class="d-flex align-items-center">
                    <button class="btn btn-link text-dark d-lg-none me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapse">
                        <i class="bi bi-list"></i>
                    </button>
                    <a href="index.php?controller=projects&amp;action=index" class="text-decoration-none d-flex align-items-center">
                        <img src="<?= IMAGES_PATH ?>/logo/taskgroup-logo-icon.png" alt="TaskGroup" class="me-2" style="height: 32px;">
                        <span class="fw-bold fs-5">
                            <span style="color: #6C2DBE;">TASK</span>
                            <span style="color: #E42F5A;">GROUP</span>
                        </span>
                    </a>
                </div>
                
                <div class="d-flex align-items-center">
                    <!-- Language Selector -->
                    <?php include(__DIR__ . '/../shared/components/language_dropdown.php') ?>
                    <!-- User Menu -->
                    <div class="dropdown">
                        <button class="btn btn-link text-white dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2 user-avatar-c bg-tg-primary" style="width: 36px; height: 36px;">
                                <?= strtoupper($currentUser[0]) ?>
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end bg-dark">
                            <li><a class="dropdown-item text-light" href="<?= "index.php?controller=users&amp;action=detail&amp;id=" . urlencode($currentUser) ?>">
                                <i class="bi bi-person me-2"></i><?= $currentUser ?>
                            </a></li>
                            <li><a class="dropdown-item text-light" href="<?= BASE_URL ?>/settings">
                                <i class="bi bi-gear me-2"></i>Configuración
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= "index.php?controller=auth&amp;action=logout" ?>">
                                <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- ===== MAIN LAYOUT =====-->
    <div class="container-fluid">
        <div class="row">
            
            <!-- ===== SIDEBAR ===== -->
            <div class="col-lg-2 col-md-3 d-md-block collapse bg-tg-dark" id="sidebarCollapse">
                <aside class="sidebar bg-dark vh-100 sticky-top pt-3 bg-tg-dark" style="top: 56px;">
                    <nav class="nav flex-column">
                        <a href="<?= "index.php?controller=projects&amp;action=index" ?>" class="nav-link active py-3">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                        </a>
                        <a href="<?= "index.php?controller=projects&amp;action=list" ?>" class="nav-link py-3">
                            <i class="bi bi-folder me-2"></i>Mis Proyectos
                        </a>
                        <a href="<?= "index.php?controller=tasks&amp;action=index" ?>" class="nav-link py-3">
                            <i class="bi bi-check-circle me-2"></i>Tareas
                        </a>
                        
                        <hr class="my-2">
                        
                        <a href="<?= "index.php?controller=projects&amp;action=create" ?>" class="nav-link py-3">
                            <i class="bi bi-plus-circle me-2"></i>Nuevo Proyecto
                        </a>
                    </nav>
                </aside>
            </div>

            <!-- ===== MAIN CONTENT =====-->
            <div class="col-lg-10 col-md-9 bg-tg-primary-10">
                <main class="main-content py-4 bg-tg-primary-10">
                    
                    <!-- Page Header -->
                    <div class="d-flex justify-content-center align-items-center mb-4 ">
                        <h1 class="h1 fw-bold text-center text-white me-3"><?= $view->getVariable("main-content-header") ?></h1>
                        <div class="page-actions">
                            <!-- Aquí van botones de acciones específicas -->
                            <?= $view->getFragment("actions-header-main") ?>
                        </div>
                    </div>
                    

                    <!-- Flash Messages -->
                    <?php if ($flashMessage != null): ?>
                        <div class="alert <?= ($flashType != null) ? $flashType : 'alert-info' ?> alert-dismissible fade show" role="alert"
                            id="flash-message" data-auto-dismiss="4000">
                            <i class="bi <?= ($flashIcon != null) ? $flashIcon : 'bi-info-circle' ?> me-1"></i>
                            <?= $flashMessage ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            </button>
                        </div>                    
                    <?php endif; ?>
                    
                    <!-- Page Content -->
                    <div class="page-content">
                        <!-- ===== CONTENIDO ESPECÍFICO DE CADA PÁGINA AQUÍ ===== -->
                        
                        <div class="row">
                            <div class="col-12">
                                <?=  $view->getFragment(ViewManager::DEFAULT_FRAGMENT) ?>
                            </div>
                        </div>
                        
                    </div>
                </main>
            </div>
        </div>
    </div>

    <!-- ===== FOOTER ===== -->
    <footer class="main-footer bg-dark text-white mt-5">
        <div class="container py-4">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <span class="fw-bold fs-5">
                        <span style="color: #6C2DBE;">TASK</span>
                        <span style="color: #E42F5A;">GROUP</span>
                    </span>
                </div>
                <div class="col-md-4 text-center">
                    <div class="footer-links">
                        <a href="#" class="text-white text-decoration-none me-3">Ayuda</a>
                        <a href="#" class="text-white text-decoration-none me-3">Términos</a>
                        <a href="#" class="text-white text-decoration-none">Privacidad</a>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <small>&copy; 2024 TaskGroup. Todos los derechos reservados.</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Action Confirmation Modal -->
    <div class="modal fade" id="confirmActionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmActionModalTitle"><?= i18n("Confirm") ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="confirmActionForm" method="POST" class="d-inline">
                    <div class="modal-body">
                        <p id="confirmActionMessage" ><?= i18n("Are you sure you want to proceed with this action?") ?></p>
                        
                        <!-- Dynamic inputs -->
                        <div id="confirmActionInputs"></div>

                        <!-- Hidden input for ID -->
                        <input type="hidden" name="id" id="confirmActionId">

                    </div>

                    <div class="modal-footer">
                        <button type="buton" class="btn btn-secondary" data-bs-dismiss="modal">
                            <?= i18n("Cancel") ?>
                        </button>
                        
                        <button type="submit" id="confirmActionButton" class="btn btn-danger">
                            <?= i18n("Confirm") ?>
                        </button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Nuestro JS personalizado -->    

</body>
</html>
