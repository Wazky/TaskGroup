<?php
// file: /app/View/shared/components/dashboard_main_nav.php

if (!defined('BASE_URL')) {
    require_once (__DIR__ . '/../../../../config/paths.php');
}

?>

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