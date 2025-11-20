<?php
// file: app/View/pages/projects/index.php

require_once __DIR__ . '/../../../../config/paths.php';

$view = ViewManager::getInstance();
$errors = $view->getVariable("errors");
$currentUser = $view->getVariable("current_user");

?>

<div class="card border-0 shadow-sm bg-tg-grey">
    <div class="card-body">
        <h2 class="h4 card-title text-light">Info Proyectos</h2>
        <p class="card-text text-white">asdds.</p>

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


<!-- Fragments & Variables Setup -->
<?php

// Set view variables
$view->setVariable("page-title", i18n("Dashboard"));
$view->setVariable("main-content-header", i18n("Dashboard"));


?>