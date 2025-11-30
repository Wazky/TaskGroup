<?php
// file: app/View/pages/users/detail.php

require_once(__DIR__ . '/../../../../config/paths.php');

$view = ViewManager::getInstance();
$currentUser = $view->getVariable("current_user");

$user = $view->getVariable("user");

?>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-slg bg-tg-grey w-75 mx-auto">
            <div class="card-body p-4 w-75 mx-auto">

                <div class="d-flex justify-content-center align-items-center mb-2 bg-tg-primary rounded">
                    <!-- User Detail Header -->
                    <h2 class="h3 card-title text-light mb-0 fw-bold rounded py-2 px-4">
                        <i class="bi bi-person-fill me-1"></i>
                        <?= i18n("User Detail") ?>
                    </h2>
                </div>

                <!-- User Detail Content -->
                <div class="d-flex justify-content-center bg-tg-primary rounded shadow-sm p-4">
                    <div class="w-75">
                        <!-- User Name -->
                        <div class="mb-4">
                            <h3 class="h4 text-white fw-bold mb-2">
                                <i class="bi bi-person-fill me-1"></i>
                                <?= i18n("Username") ?>
                            </h3>
                            <p class="text-light fs-5"><?= htmlspecialchars($user->getUsername()) ?></p>
                        </div>

                        <hr class="text-white border-2"/>
                        
                        <!-- User Email -->
                        <div class="mb-4">
                            <h3 class="h4 text-white fw-bold mb-2">
                                <i class="bi bi-envelope-fill me-1"></i>
                                <?= i18n("Email") ?>
                            </h3>
                            <p class="text-light fst-italic fs-5"><?= htmlspecialchars($user->getEmail()) ?></p>
                        </div>

                        <hr class="text-white border-2"/>

                        <!-- Password shouldn't be displayed -->
                        <!-- User Password (only if current user is the same as the user being viewed) -->
                        <?php if($currentUser === $user->getUsername()): ?>
                            <div class="mb-4">
                                <h3 class="h4 text-white fw-bold mb-2">
                                    <i class="bi bi-lock-fill me-1"></i>
                                    <?= i18n("Password") ?>
                                </h3>
                                <p class="text-light fs-5"><?= htmlspecialchars($user->getPassword()) ?></p>
                            </div>

                            <hr class="text-white border-2"/>                        
                        <?php endif; ?>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Fragments and Variables Setup-->
<?php 
// Set view variables
$view->setVariable("page-title", $user->getUsername());
$view->setVariable("main-content-header", $user->getUsername()." - ".strtoupper(i18n("User Detail")));
?>

<?php $view->moveToFragment("javascript"); ?>
    <!-- Include any JavaScript specific to the task detail page here -->
    <script src="confirmActionModal.js"></script>
<?php $view->moveToDefaultFragment(); ?>

<?php $view->moveToFragment("actions-header-main"); ?>
    <!-- Additional action buttons can be added here in the future -->  
<?php $view->moveToDefaultFragment(); ?>