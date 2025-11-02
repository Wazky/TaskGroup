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
    
    <!-- Importar estilos -->
    <link rel="stylesheet" href="<?= CSS_PATH ?>/main.css">
</head>
<body class="dashboard-page">

    <!-- ===== HEADER ===== -->
    <header class="main-header">
        <div class="header-content container">
            <div class="header-left">
                <button class="menu-toggle" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <a href="<?= BASE_URL ?>/app/View/pages/dashboard.php" class="logo-container logo-small">
                    <img src="<?= IMAGES_PATH ?>/logo/taskgroup-logo-icon.png" alt="TaskGroup" class="logo-img">
                
                    <span class="logo-text">
                        <span class="logo-primary">TASK</span>
                        <span class="logo-accent">GROUP</span>
                    </span>
                </a>
            </div>

            <nav class="main-nav">
                <ul>

                    <li><a href="<?= BASE_URL ?>/app/View/pages/dashboard.php" class="nav-link">Dashboard</a></li>

                    <li><a href="<?= BASE_URL ?>/app/View/pages/projects/project_list.php" class="nav-link">Proyectos</a></li>
                
                </ul>
            </nav>

            <div class="user-menu">
                <div class="user-dropdown">
                    <button class="user-avatar">U</button>
                    <div class="dropdown-menu">
                        <a href="<?= BASE_URL ?>/profile" class="dropdown-item">Mi Perfil</a>
                        <a href="<?= BASE_URL ?>/settings" class="dropdown-item">Configuracion</a>
                        <hr class="dropdown-divider">
                        <a href="<?= BASE_URL ?>/logout" class="dropdown-item logout">Cerrar Sesion</a>
                    </div>
                </div>
            </div>

        </div>
    </header>

    <!-- ===== MAIN LAYOUT =====-->
    <div class="main-layout">
        <!-- ===== SIDEBAR ===== -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2 class="sidebar-title">Navegación</h2>
            </div>

            <nav class="sidebar-nav">
                <ul class="sidebard-menu">
                    <li class="sidebar-item">
                        <a href="<?= BASE_URL ?>/dashboard" class="sidebar-link active">
                            <span class="sidebar-icon">ICON</span>
                            <span class="sidebar-text">Dashboard</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="<?= BASE_URL ?>/app/View/pages/projects/project_list.php" class="sidebar-link">
                            <span class="sidebar-icon">ICON</span>
                            <span class="sidebar-text">Mis Proyectos</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="<?= BASE_URL ?>/app/View/pages/tasks/task_list.php" class="sidebar-link">
                            <span class="sidebar-icon">ICON</span>
                            <span class="sidebar-text">Tareas</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="<?= BASE_URL ?>/app/View/pages/projects/project_create.php" class="sidebar-link">
                            <span class="sidebar-icon">ICON</span>
                            <span class="sidebar-text">Nuevo Proyecto</span>
                        </a>
                    </li>

                </ul>
            </nav>
        </aside>

        <!-- ===== MAIN CONTENT =====-->
        <main class="main-content">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">DashBoard</h1>
                <div class="page-actions">
                    <p>Acciones</p>
                </div>
            </div>

            <!-- Page Content -->
            <div class="page-content">
                <!-- ===== CONTENIDO ESPECÍFICO DE CADA PÁGINA AQUÍ ===== -->
                
                <?php
                // EJEMPLO - Esto cambiará en cada vista:
                ?>
                <div class="welcome-message">
                    <h2>¡Bienvenido a TaskGroup!</h2>
                    <p>Esta es una página de ejemplo. Reemplaza este contenido.</p>
                    
                    <div class="example-stats">
                        <div class="stat-card">
                            <h3>Proyectos Activos</h3>
                            <div class="stat-number">0</div>
                        </div>
                        <div class="stat-card">
                            <h3>Tareas Pendientes</h3>
                            <div class="stat-number">0</div>
                        </div>
                    </div>
                </div>
                
            </div>
        </main>
    </div>

    <!-- ===== FOOTER ===== -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <span class="logo-text">
                        <span class="logo-primary">TASK</span>
                        <span class="logo-accent">GROUP</span>
                    </span>
                </div>
                <div class="footer-links">
                    <a href="#" class="footer-link">Ayuda</a>
                    <a href="#" class="footer-link">Términos</a>
                    <a href="#" class="footer-link">Privacidad</a>
                </div>
                <div class="footer-copy">
                    &copy; 2024 TaskGroup. Todos los derechos reservados.
                </div>
            </div>
        </div>
    </footer>

    <script src="<?= JS_PATH ?>/main.js"></script>

</body>
</html>
