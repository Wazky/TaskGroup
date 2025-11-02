<?php
//Comprobar constantes
if (!defined('IMAGES_PATH')) {
    require_once __DIR__ . '/../../../../config/paths.php';
}
?>

<div class="logo-container">
    <img
        src="<?= IMAGES_PATH ?>/logo/taskgroup-logo-icon.png"
        alt="TaskGroup Logo"
        class="logo-img"
        style="max-width: 100%; height: auto;"
    >
    <span class="logo-text">
        <span class="text-tg-primary">TASK</span>
        <span class="text-tg-secondary">GROUP</span>
    </span>
</div>

