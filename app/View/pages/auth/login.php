<?php
// file: /app/View/pages/auth/login.php

if (!defined('BASE_URL')) {
    require_once(__DIR__ . '/../../../../config/paths.php');
}

require_once(BASE_URL.'/app/core/ViewManager.php');

$view = ViewManager::getInstance();
$error = $view->getVariable("error");
$user = $view->getVariable("user");
$view->setVariable("page-title", i18n("Login"));
$view->setVariable("auth-title", "Login");
$view->setVariable("auth-subtitle", i18n("Collaborative task manager"));

?>


<form id="loginForm" class="auth-form" action="<?= BASE_URL?>/app/View/auth/login.php" method="POST">
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
