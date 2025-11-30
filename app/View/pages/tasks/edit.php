<?php
// file: app/View/pages/tasks/detail.php

require_once(__DIR__ . '/../../../../config/paths.php');

$view = ViewManager::getInstance();
$currentUser = $view->getVariable("current_user");

$task = $view->getVariable("task");
$members = $view->getVariable("members");

?>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-slg bg-tg-grey w-75 mx-auto">
            <div class="card-body p-4 w-75 mx-auto">

                <div class="d-flex justify-content-center align-items-center mb-2 
                    <?= ($task->getStatus() === "completed") ? 'bg-tg-primary-dark' : 'bg-tg-secondary-dark'  ?> rounded"
                    id="header-status">
                    <!-- Task Detail Header -->
                    <h2 class="h3 card-title text-light mb-0 fw-bold rounded py-2 px-4">
                        <i class="bi bi-clipboard2-fill me-1"></i>
                        <?= i18n("Task Edit") ?>
                    </h2>
                </div>

                <!-- Task Detail Content -->
                <form action="index.php?controller=tasks&amp;action=edit&amp;id=<?= $task->getId() ?>" method="POST">

                    <!-- Hidden Task ID -->
                    <input type="hidden" name="task_id" value="<?= $task->getId() ?>">

                    <div class="d-flex justify-content-center <?= ($task->getStatus() === "completed") ? 'bg-tg-primary' : 'bg-tg-secondary' ?> rounded shadow-sm p-4"
                        id="form-status">
                        <div class="w-75">
                            <!-- Task Title -->
                            <div class="mb-4">
                                <label class="form-label h4 text-white fw-bold mb-3">
                                    <i class="bi bi-journal-text me-1"></i>
                                    <?= i18n("Title") ?>
                                </label>
                                <input type="text" name="task_title" class="form-control bg-light w-100"
                                    value="<?= htmlspecialchars($task->getTitle()) ?>" required>
                            </div>

                            <hr class="text-white border-2"/>
                            
                            <!-- Task Description -->
                            <div class="mb-4">
                                <label class="form-label h4 text-white fw-bold mb-3">
                                    <i class="bi bi-card-text me-1"></i>
                                    <?= i18n("Description") ?>
                                </label>
                                <textarea name="task_description" class="form-control bg-light w-100" required><?= htmlspecialchars($task->getDescription()) ?></textarea>
                            </div>

                            <hr class="text-white border-2"/>

                            <!-- Task Status -->
                            <div class="col mb-2">
                                <label class="form-label h4 text-white fw-bold mb-4">
                                    <i class="bi bi-clipboard2-pulse-fill me-1"></i>
                                    <?= i18n("Status") ?>
                                </label>
                                <select class="form-select fw-bold" name="task_status"  id="task_status" onchange="updateStatusColors()" required>
                                    <option value="to do" <?= ($task->getStatus() === "to do") ? 'selected' : '' ?>>
                                        <?= i18n("To Do") ?>
                                    </option>
                                    <option value="completed" <?= ($task->getStatus() === "completed") ? 'selected' : '' ?>>
                                        <?= i18n("Completed") ?>
                                    </option>
                                </select>
                            </div>

                            <hr class="text-white border-2"/>

                            <!-- Assigned User -->
                            <div class="mb-4">
                                <label class="form-label h4 text-white fw-bold mb-3">
                                    <i class="bi bi-person-fill me-1"></i>
                                    <?= i18n("Assigned User") ?>
                                </label>
                                <select class="form-select fw-bold" name="assigned_username" id="assigned_username" required>
                                    <?php foreach ($members as $member): ?>
                                        <option value="<?= htmlspecialchars($member) ?>" <?= ($task->getAssignedUsername() === $member) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($member) ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>

                            <hr class="text-white border-2"/>

                            <!-- Created At and Modified At -->
                            <div class="d-flex justify-content-between row mb-4">
                                <!-- Created At -->
                                <div class="col">                        
                                    <h3 class="h4 text-white fw-bold mb-2">
                                        <i class="bi bi-calendar-fill me-1"></i>
                                        <?= i18n("Created At ") ?>
                                    </h3>
                                    <p class="text-light fs-5"><?= htmlspecialchars($task->getCreatedAtFormatted()) ?></p>
                                </div>
                                <!-- Modified At -->
                                <div class="col">
                                <h3 class="h4 text-white fw-bold mb-2">
                                    <i class="bi bi-calendar-event-fill me-1"></i>
                                    <?= i18n("Modified At ") ?>
                                </h3>
                                <p class="text-light fs-5"><?= htmlspecialchars($task->getUpdatedAtFormatted()) ?></p>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="text-center mb-2">
                                <button type="submit" name="submit" class="btn btn-lg <?= ($task->getStatus() === "completed") ? 'bg-tg-secondary' : 'bg-tg-primary' ?> text-light fw-bold"
                                    id="save-button">
                                        <i class="bi bi-save me-1"></i>
                                        <?= i18n("Save Changes") ?>
                                </button>
                                <button type="button" class="btn btn-lg btn-secondary fw-bold ms-2"
                                    onclick="window.location.href='index.php?controller=projects&amp;action=detail&amp;id=<?= $task->getProjectId() ?>'">
                                    <i class="bi bi-x-circle me-1"></i>
                                    <?= i18n("Cancel") ?>
                                </button>                            
                            </div>
                        
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<!-- Fragments and Variables Setup-->
<?php 
// Set view variables
$view->setVariable("page-title", $task->getTitle());
$view->setVariable("main-content-header", $task->getTitle()." - ".strtoupper(i18n("Task Edit")));
?>

<?php $view->moveToFragment("javascript"); ?>
    <!-- Include any JavaScript specific to the task detail page here -->
    <script src="<?= JS_PATH ?>/confirmActionModal.js"></script>
    <script src="<?= JS_PATH ?>/adjustBgToStatus.js"></script>
<?php $view->moveToDefaultFragment(); ?>

