<?php
// file: /app/View/shared/components/footer.php

$view = ViewManager::getInstance();
$action

?>

<p>
    <?= $view->getVariable("auth-footer-text", "default footer text") ?>
    <a href="<?= "./index.php?controller=".$view->getVariable("footer-controller", "")."&amp;action=".$view->getVariable("footer-action", "") ?>">
        <?= $view->getVariable("auth-footer-link-text") ?>
    </a>
</p>
