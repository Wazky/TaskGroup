<?php
// file: app/View/pages/tasks/detail.php

require_once(__DIR__ . '/../../../../config/paths.php');

$view = ViewManager::getInstance();
$currentUser = $view->getVariable("current_user");

$task = $view->getVariable("task");
$isProjectOwner = $view->getVariable("isProjectOwner");

?>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-slg bg-tg-grey w-75 mx-auto">
            <div class="card-body p-4 w-75 mx-auto">

                <div class="d-flex justify-content-center align-items-center mb-2 <?= ($task->getStatus() === "completed") ? 'bg-tg-primary-dark' : 'bg-tg-secondary-dark'  ?> rounded">
                    <!-- Task Detail Header -->
                    <h2 class="h3 card-title text-light mb-0 fw-bold rounded py-2 px-4">
                        <i class="bi bi-clipboard2-fill me-1"></i>
                        <?= i18n("Task Detail") ?>
                    </h2>
                </div>

                <!-- Task Detail Content -->
                <div class="d-flex justify-content-center <?= ($task->getStatus() === "completed") ? 'bg-tg-primary' : 'bg-tg-secondary' ?> rounded shadow-sm p-4">
                    <div class="w-75">
                        <!-- Task Title -->
                        <div class="mb-4">
                            <h3 class="h4 text-white fw-bold mb-2">
                                <i class="bi bi-journal-text me-1"></i>
                                <?= i18n("Title") ?>
                            </h3>
                            <p class="text-light fs-5"><?= htmlspecialchars($task->getTitle()) ?></p>
                        </div>

                        <hr class="text-white border-2"/>
                        
                        <!-- Task Description -->
                        <div class="mb-4">
                            <h3 class="h4 text-white fw-bold mb-2">
                                <i class="bi bi-card-text me-1"></i>
                                <?= i18n("Description") ?>
                            </h3>
                            <p class="text-light fst-italic fs-5"><?= htmlspecialchars($task->getDescription()) ?></p>
                        </div>

                        <hr class="text-white border-2"/>

                        <!-- Task Status -->
                        <div class="col mb-2">
                            <h3 class="h4 text-white fw-bold mb-2">
                                <i class="bi bi-clipboard2-pulse-fill me-1"></i>
                                <?= i18n("Status") ?>
                            </h3>
                            <p class="text-light fs-5"><?= strtoupper(i18n($task->getStatus())) ?></p>
                        </div>

                        <hr class="text-white border-2"/>

                        <!-- Assigned User -->
                        <div class="mb-4">
                            <h3 class="h4 text-white fw-bold mb-2">
                                <i class="bi bi-person-fill me-1"></i>
                                <?= i18n("Assigned User") ?>
                            </h3>
                            <p class="text-light fs-5"><?= htmlspecialchars($task->getAssignedUsername()) ?></p>
                        </div>

                        <hr class="text-white border-2"/>

                        <div class="d-flex justify-content-between row mb-4">
                            <div class="col">                        
                                <h3 class="h4 text-white fw-bold mb-2">
                                    <i class="bi bi-calendar-fill me-1"></i>
                                    <?= i18n("Created At ") ?>
                                </h3>
                                <p class="text-light fs-5"><?= htmlspecialchars($task->getCreatedAtFormatted()) ?></p>
                            </div>
                            <div class="col">
                            <h3 class="h4 text-white fw-bold mb-2">
                                <i class="bi bi-calendar-event-fill me-1"></i>
                                <?= i18n("Modified At ") ?>
                            </h3>
                            <p class="text-light fs-5"><?= htmlspecialchars($task->getUpdatedAtFormatted()) ?></p>
                            </div>
                        </div>

                        <?php if("to do" === $task->getStatus() ): ?>
                            <div class="mb-2 text-center">
                                <button class="btn btn-lg btn-light fw-bold"
                                    onclick="openConfirmModal({
                                        title: '<?= i18n("Confirm Mark as Done") ?>',
                                        message: '<?= sprintf(i18n("Are you sure you want to mark the task %s as done?"), $task->getTitle()) ?>',
                                        action: '<?= "index.php?controller=tasks&amp;action=markAsCompleted" ?>',
                                        id: '<?= $task->getId() ?>',
                                        confirmButtonText: '<?= i18n("Mark as Done") ?>',
                                        confirmButtonClass: 'bg-tg-primary text-light'
                                    })"
                                >
                                    <i class="bi bi-check2-circle me-1"></i>
                                    <?= i18n("Mark as Done") ?>
                                </button>
                            </div>
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
$view->setVariable("page-title", $task->getTitle());
$view->setVariable("main-content-header", $task->getTitle()." - ".strtoupper(i18n("Task Detail")));
?>

<?php $view->moveToFragment("javascript"); ?>
    <!-- Include any JavaScript specific to the task detail page here -->
    <script src="confirmActionModal.js"></script>
<?php $view->moveToDefaultFragment(); ?>

<?php $view->moveToFragment("actions-header-main"); ?>
    <!-- Action Buttons for Task Detail Page -->
    <div class="d-flex gap-2">        
        <?php if(($currentUser === $task->getAssignedUsername()) || $isProjectOwner): ?>
            <!-- Edit Task Button -->
            <a href="index.php?controller=tasks&amp;action=edit&amp;id=<?= $task->getId() ?>" class="btn btn-lg btn-light fw-bold">
                <i class="bi bi-pencil-fill me-1"></i>
                <?= i18n("Edit Task") ?>
            </a>
            
            <!-- Delete Task Button -->
            <button class="btn btn-lg btn-danger fw-bold"
                onclick="openConfirmModal({
                    title: '<?= i18n("Confirm Delete Task") ?>',
                    message: '<?= sprintf(i18n("Are you sure you want to delete the task %s? This action cannot be undone."), $task->getTitle()) ?>',
                    action: '<?= "index.php?controller=tasks&amp;action=delete" ?>',
                    id: '<?= $task->getId() ?>',
                    confirmButtonText: '<?= i18n("Delete Task") ?>',
                    confirmButtonClass: 'btn-danger'
                })"
            >
                <i class="bi bi-trash-fill me-1"></i>
                <?= i18n("Delete Task") ?>
            </button>
        <?php endif; ?>
    </div>

<?php $view->moveToDefaultFragment(); ?>