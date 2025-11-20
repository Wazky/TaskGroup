<?php
// file: /app/View/pages/auth/register.php

require_once(__DIR__.'/../../../core/ViewManager.php');

$view = ViewManager::getInstance();
$errors = $view->getVariable("errors");
$currentUser = $view->getVariable("current_user");

?>

<!-- Error Div -->
<div id="error-div" class="error-div justify-content-center align-items-center" onclick="closeErrorDiv()">
    <p class="text-danger error-message"><?= isset($errors["general"]) ? $errors["general"] : ""?></p>
</div>  

<!-- Register Form -->  
<form id="register-form" class="auth-form" action="./index.php?controller=auth&amp;action=register" method="POST">
    <div class="form-group">
        <label for="username"><?= i18n("Username") ?></label>
        <input type="text" id="username" name="username" placeholder="<?= i18n("Choose your alias") ?>" 
            class="border border-secondary <?= (isset($errors["username"]) && !empty($errors["username"])) ? "border-danger" : "" ?>"
            value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required>
        <p class="text-danger error-message" style="display: none;">
            <?= isset($errors["username"]) ? $errors["username"] : ""?>            
        </p>
    </div>

    <div class="form-group">
        <label for="email"><?= i18n("Email") ?></label>
        <input type="email" id="email" name="email" placeholder="<?= i18n("Enter your email") ?>" 
            class="border border-secondary <?= (isset($errors["email"]) && !empty($errors["email"])) ? "border-danger" : "" ?>" 
            value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
        <p class="text-danger error-message" style="display: none;">
            <?= isset($errors["email"]) ? $errors["email"] : ""?>
        </p>
    </div>

    <div class="form-group">
        <label for="password"><?= i18n("Password") ?></label>
        <input type="password" id="password" name="password" placeholder="<?= i18n("Create a password") ?>" 
            class="border border-secondary <?= (isset($errors["password"]) && !empty($errors["password"])) ? "border-danger" : "" ?>" required>
        <p class="text-danger error-message" style="display: none;">
            <?= isset($errors["password"]) ? $errors["password"] : ""?>
        </p>
    </div>

    <div class="form-group">
        <label for="confirm_password"><?= i18n("Confirm Password") ?></label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="<?= i18n("Repeat your password") ?>" 
            class="border border-secondary <?= isset($errors["confirm_password"]) ? "border-danger" : "" ?>" required>
        <div class="error"><?= i18n("Passwords do not match") ?></div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?= i18n("Create Account") ?></button>
    </div>
</form>


<!-- Fragments & Variables Setup -->
<?php
//Create javascript fragment
$view->moveToFragment("javascript");
include(__DIR__.'/../../../../public/js/auth.js');
$view->moveToDefaultFragment();

// Create logo fragment
$view->moveToFragment("logo");
include(__DIR__."/../../shared/components/auth_logo.php");
$view->moveToDefaultFragment();

// Set view variables
$view->setVariable("page-title", i18n("Create Account"));
$view->setVariable("auth-title", i18n("Create Account"));
$view->setVariable("auth-subtitle", i18n("Join TaskGroup and start collaborating"));
$view->setVariable("auth-footer-text", i18n("Already have an account?"));
$view->setVariable("footer-controller", 'auth');
$view->setVariable("footer-action", 'login');
$view->setVariable("auth-footer-link-text", i18n("Log in here"));

// Create footer fragment
$view->moveToFragment("footer");
include(__DIR__."/../../shared/components/auth_footer.php");
$view->moveToDefaultFragment();
?>