<?php
// file: /app/View/shared/components/language_dropdown.php

if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../../../config/paths.php';
}

?>

<div class="dropdown me-3">
    <button class="btn btn-link text-white dropdown-toggle" type="button" data-bs-toggle="dropdown">
        <i class="bi bi-translate"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end bg-dark">
        <li>
            <a class="dropdown-item text-light" href="<?= BASE_URL ?>/index.php?controller=language&action=change&lang=es">
                <img src="<?= IMAGES_PATH ?>/flag-es.png" alt="Español" width="20" height="14" class="me-2">
                <?= i18n("Spanish") ?>
            </a>
        </li>
        <li>
            <a class="dropdown-item text-light" href="<?= BASE_URL ?>/index.php?controller=language&action=change&lang=en">
                <img src="<?= IMAGES_PATH ?>/flag-en.png" alt="English" width="20" height="14" class="me-2">
                <?= i18n("English") ?>
            </a>
        </li>
    </ul>
</div>