<?php
// file: app/View/pages/projects/create.php

require_once __DIR__ . '/../../../../config/paths.php';

$view = ViewManager::getInstance();
$errors = $view->getVariable("errors");
$currentUser = $view->getVariable("current_user");

$task = $view->getVariable("task");
$members = $view->getVariable("members");
?>

<!-- Task Create Page Content -->
<div class="page-content">

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-6">
            <div class="card border-0 shadow-sm bg-tg-grey">
                <div class="card-body">
                    <div class="d-flex justify-content-center align-items-center mb-4">

                        <!-- Create Task Form Header -->
                        <h2 class="h3 card-title text-white mb-0 bg-tg-secondary fw-bold rounded py-2 px-4">
                            <i class="bi bi-file-earmark-text-fill text-light"></i>
                            <?= i18n("New Task Form") ?>
                        </h2>                        
                        
                    </div>

                    <!-- Create Task Form Content -->
                    <div class="d-flex justify-content-center">
                        <form class="w-75" action="index.php?controller=tasks&amp;action=create" method="POST">

                            <input type="hidden" name="project_id" value="<?= $task->getProjectId() ?>">

                            <!-- Task Name Section -->
                            <div class="mb-4">
                                <!-- Task Name Label -->
                                <label for="task_name" class="form-label fw-semibold text-light">
                                    <i class="bi bi-archive-fill me-1"></i>
                                    <?= strtoupper(i18n("Task Name")) ?> 
                                    <span class="text-danger">* </span>
                                </label>
                                <!-- Task Name Input -->
                                <input type="text" class="form-control form-control-lg <?= isset($errors["task_title"]) ? 'error' : '' ?>"
                                    id="task_title" name="task_title" value="<?= htmlspecialchars($task->getTitle()) ?>"
                                    placeholder="<?= i18n("Enter the task title") ?>" required
                                >
                                <!-- Task Name Error Message -->
                                <?php if(isset($errors["task_title"])): ?>
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        <?= htmlspecialchars($errors["task_title"]) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <hr class="text-white border-2"/>

                            <!-- Task Description Section -->
                            <div class="mb-4">
                                <!-- Task Description Label -->
                                <label for="task_description" class="form-label fw-semibold text-light">
                                    <i class="bi bi-chat-square-text-fill me-1"></i>
                                    <?= strtoupper(i18n("task description")) ?>
                                </label>
                                <!-- Task Description Textarea -->
                                <textarea class="form-control <?= isset($errors["task_description"]) ? 'error' : '' ?>"
                                    id="task_description" name="task_description" rows="4"
                                ><?= htmlspecialchars($task->getDescription()) ?></textarea>
                                <!-- Task Description Error Message -->
                                <?php if(isset($errors["task_description"])): ?>
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        <?= htmlspecialchars($errors["task_description"]) ?>
                                    </div>
                                <?php endif;?>
                            </div>

                            <hr class="text-white border-2"/>   

                            <!-- Task Status Section -->
                            <div class="mt-2 mb-2 row align-items-center">
                                <div class="col-auto">
                                    <!-- Task Status Label -->
                                    <label for="task_status" class="form-label fw-semibold text-light">
                                            <i class="bi bi-clipboard2-fill me-1"></i>
                                            <?= strtoupper(i18n("Task Status")) ?>
                                    </label>
                                </div>
                                <div class="col-auto">
                                    <!-- Task Status Select -->
                                    <select class="form-select <?= isset($errors["task_status"]) ? 'error' : '' ?> fw-bold" 
                                        id="task_status" name="task_status" required>
                                        <option value="to do" <?= ($task->getStatus() === 'to do') ? 'selected' : '' ?>>
                                            <?= i18n("To do") ?>
                                        </option>
                                        <option value="completed" <?= ($task->getStatus() === 'completed') ? 'selected' : '' ?>>
                                            <?= i18n("Completed") ?>
                                        </option>
                                    </select>
                                </div>

                                <!-- Task Status Error Message -->
                                <?php if(isset($errors["task_status"])): ?>
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        <?= htmlspecialchars($errors["task_status"]) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <hr class="text-white border-2"/>

                            <!-- Assigned User Section -->
                            <div class="mt-2 mb-2 row align-items-center">
                                <div class="col-auto">
                                    <!-- Assigned User Label -->
                                    <label for="assigned_username" class="form-label fw-semibold text-light">
                                            <i class="bi bi-person-fill me-1"></i>
                                            <?= strtoupper(i18n("Assigned User")) ?>
                                    </label>
                                </div>
                                <div class="col-auto">
                                    <!-- Assigned User Select -->
                                    <select class="form-select <?= isset($errors["assigned_username"]) ? 'error' : '' ?> fw-bold" 
                                        id="assigned_username" name="assigned_username" required>
                                        <?php foreach($members as $member): ?>
                                            <option value="<?= $member ?>" <?= ($member == $currentUser) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($member) ?>
                                            </option>                        
                                        <?php endforeach;?>
                                    </select>
                                </div>

                                <!-- Assigned User Error Message -->
                                <?php if(isset($errors["assigned_username"])): ?>
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        <?= htmlspecialchars($errors["assigned_username"]) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <hr class="text-white border-2"/>

                            <!-- Form Buttons Section -->
                            <div class="d-flex justify-content-center gap-2 pt-2 mb-3">
                                <!-- Submit Button -->
                                <button type="submit" name="submit" class="btn btn-lg bg-tg-secondary text-light fw-bold">
                                    <i class="bi bi-plus-circle-fill me-1"></i>
                                    <?= i18n("Create Task") ?>
                                </button>
                                <!-- Cancel Button -->
                                <a href="index.php?controller=projects&amp;action=detail&amp;id=<?= $task->getProjectId() ?>" class="btn btn-lg btn-secondary fw-bold">
                                    <i class="bi bi-x-circle me-1"></i>
                                    <?= i18n("Cancel") ?>
                                </a>    
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
</div>

<?php
// Set view variables
$view->setVariable("page-title", i18n("Create New Project"));
$view->setVariable("main-content-header", i18n("Create New Project"));
?>

<?php $view->moveToFragment("javascript"); ?>
    <script src="<?= JS_PATH ?>/add_username_items.js"></script>
<?php $view->moveToDefaultFragment(); ?>