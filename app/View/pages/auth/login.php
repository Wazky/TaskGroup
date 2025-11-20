<?php
// file: /app/View/pages/auth/login.php

require_once(__DIR__.'/../../../core/ViewManager.php');

$view = ViewManager::getInstance();
$errors = $view->getVariable("errors");
$currentUser = $view->getVariable("current-user");

?>

<!-- Error Div -->
<div id="error-div" class="error-div justify-content-center align-items-center" onclick="closeErrorDiv()">
    <p class="text-danger error-message"><?= isset($errors["general"]) ? $errors["general"] : ""?></p>
</div>

<!-- Login Form -->
<form id="login-form" class="auth-form" action="<?= "./index.php?controller=auth&amp;action=login" ?>" method="POST">
    <div class="form-group">
        <label for="username"><?= i18n("Username or Email:") ?></label>
        <input type="text" id="auth-identifier" name="auth-identifier" placeholder="<?= i18n("Enter your alias or email") ?>" 
        value="<?= isset($_POST['auth-identifier']) ? htmlspecialchars($_POST['auth-identifier']) : '' ?>" required>
    </div>

    <div class="form-group">
        <label for="password"><?= i18n("Password:") ?></label>
        <input type="password" id="password" name="password" placeholder="<?= i18n("Enter your password") ?>" required>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?= i18n("Login") ?></button>
    </div>
</form>


<!-- Fragments Setup -->
<?php

// Set view variables
$view->setVariable("page-title", i18n("Login"));
$view->setVariable("auth-title", i18n("Login"));
$view->setVariable("auth-subtitle", i18n("Collaborative task manager"));
$view->setVariable("auth-footer-text", i18n("Don't have an account?"));
$view->setVariable("footer-controller", 'auth');
$view->setVariable("footer-action", 'register');
$view->setVariable("auth-footer-link-text", i18n("Sign up here"));

//Create javascript fragment
$view->moveToFragment("javascript");
include(__DIR__.'/../../../../public/js/auth.js');
$view->moveToDefaultFragment();

// Create logo fragment
$view->moveToFragment("logo");
include(__DIR__."/../../shared/components/auth_logo.php");
$view->moveToDefaultFragment();

// Create footer fragment
$view->moveToFragment("footer");
include(__DIR__."/../../shared/components/auth_footer.php");
$view->moveToDefaultFragment();
?>