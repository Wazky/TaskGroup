<?php
// file: /app/View/pages/auth/login.php

require_once(__DIR__.'/../../../core/ViewManager.php');

$view = ViewManager::getInstance();
$error = $view->getVariable("error");
$user = $view->getVariable("user");

?>

<form id="loginForm" class="auth-form" action="<?= __DIR__ ?>/../../../../public/index.php?controller=auth&amp;action=login" method="POST">
    <div class="form-group">
        <label for="username">Username or Email:</label>
        <input type="text" id="auth-identifier" name="username-email" placeholder="Enter your alias or email" required>
    </div>

    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Login</button>
    </div>
</form>
