<?php
    require_once __DIR__ . '/../../../config/paths.php';

    //Verifcacion de sesion
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskGroup - Dashboard</title>

    <!-- Importar iconno-->
    <link rel="icon" type="image/x-icon" href="<?= IMAGES_PATH ?>/icons/favicon.ico">
    
    <!-- Importar Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


    <!-- Importar estilos -->
    <link rel="stylesheet" href="<?= CSS_PATH ?>/main.css">

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
                    <a href="<?= BASE_URL ?>/app/View/pages/dashboard.php" class="text-decoration-none d-flex align-items-center">
                        <img src="<?= IMAGES_PATH ?>/logo/taskgroup-logo-icon.png" alt="TaskGroup" class="me-2" style="height: 32px;">
                        <span class="fw-bold fs-5">
                            <span style="color: #6C2DBE;">TASK</span>
                            <span style="color: #E42F5A;">GROUP</span>
                        </span>
                    </a>
                </div>

                <!-- Navegación Principal -->
                <nav class="d-none d-md-flex align-items-center">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>/app/View/pages/dashboard.php" class="nav-link active">
                                <i class="bi bi-speedometer2 me-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>/app/View/pages/projects/project_list.php" class="nav-link">
                                <i class="bi bi-folder me-1"></i>Proyectos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>/tasks" class="nav-link">
                                <i class="bi bi-check-circle me-1"></i>Tareas
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- User Menu -->
                <div class="dropdown">
                    <button class="btn btn-link text-white dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2 user-avatar-c bg-tg-primary" style="width: 36px; height: 36px;">
                            U
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end bg-dark">
                        <li><a class="dropdown-item text-light" href="<?= BASE_URL ?>/profile">
                            <i class="bi bi-person me-2"></i>Mi Perfil
                        </a></li>
                        <li><a class="dropdown-item text-light" href="<?= BASE_URL ?>/settings">
                            <i class="bi bi-gear me-2"></i>Configuración
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/logout">
                            <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                        </a></li>
                    </ul>
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
                        <a href="<?= BASE_URL ?>/app/View/pages/dashboard.php" class="nav-link active py-3">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                        </a>
                        <a href="<?= BASE_URL ?>/app/View/pages/projects/project_list.php" class="nav-link py-3">
                            <i class="bi bi-folder me-2"></i>Mis Proyectos
                        </a>
                        <a href="<?= BASE_URL ?>/tasks" class="nav-link py-3">
                            <i class="bi bi-check-circle me-2"></i>Tareas
                        </a>
                        
                        <hr class="my-2">
                        
                        <a href="<?= BASE_URL ?>/app/View/pages/projects/project_create.php" class="nav-link py-3">
                            <i class="bi bi-plus-circle me-2"></i>Nuevo Proyecto
                        </a>
                    </nav>
                </aside>
            </div>

            <!-- ===== MAIN CONTENT =====-->
            <div class="col-lg-10 col-md-9 bg-tg-primary-10">
                <main class="main-content py-4 bg-tg-primary-10">
                    
                    <!-- Page Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h2 fw-bold text-white">Dashboard</h1>
                        <div class="page-actions">
                            <!-- Aquí van botones de acciones específicas -->
                        </div>
                    </div>

                    <!-- Page Content -->
                    <div class="page-content">
                        <!-- ===== CONTENIDO ESPECÍFICO DE CADA PÁGINA AQUÍ ===== -->
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="card border-0 shadow-sm bg-tg-grey">
                                    <div class="card-body">
                                        <h2 class="h4 card-title text-light">¡Bienvenido a TaskGroup!</h2>
                                        <p class="card-text text-white">Esta es una página de ejemplo. Reemplaza este contenido.</p>
                                        
                                        <div class="row mt-4">
                                            <div class="col-md-3 mb-3">
                                                <div class="card bg-tg-primary text-white">
                                                    <div class="card-body text-center">
                                                        <h3 class="h6">Proyectos Activos</h3>
                                                        <div class="h2 fw-bold">0</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <div class="card bg-tg-secondary text-white">
                                                    <div class="card-body text-center">
                                                        <h3 class="h6">Tareas Pendientes</h3>
                                                        <div class="h2 fw-bold">0</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Nuestro JS personalizado -->
    <script src="<?= JS_PATH ?>/main.js"></script>

</body>
</html>
