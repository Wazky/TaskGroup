<?php
// file: /app/View/shared/components/footer.php

$view = ViewManager::getInstance();

?>

<div class="d-flex justify-content-between align-items-center">
    <p>
        <?= $view->getVariable("auth-footer-text", "default footer text") ?>
        <a href="<?= "./index.php?controller=".$view->getVariable("footer-controller", "")."&amp;action=".$view->getVariable("footer-action", "") ?>">
            <?= $view->getVariable("auth-footer-link-text") ?>
        </a>
    </p>

    <?php include(__DIR__ . '/language_dropdown.php') ?>
</div>