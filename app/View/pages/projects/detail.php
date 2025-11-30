<?php
// file: app/View/pages/projects/detail.php

require_once __DIR__ . '/../../../../config/paths.php';

$view = ViewManager::getInstance();
//$errors = $view->getVariable("errors");
$currentUser = $view->getVariable("current_user");

$projectInfo = $view->getVariable("project");
$members = $projectInfo->getMembers();
$tasks = $projectInfo->getTasks();
$completedTasks = $projectInfo->getTasksByStatus("completed");
$todoTasks = $projectInfo->getTasksByStatus("to do");

?>

<!-- Project Detail Page Content  -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-lg bg-tg-grey">
            <div class="card-body p-4">
                <div class="row g-4">
                    
                    <!-- Project Info Section -->
                    <div class="col-md-4">
                        <!--  Project Info Header -->
                        <div class="d-flex justify-content-between align-items-center mb-2 bg-tg-primary-dark rounded">
                            <!-- Project Info Section Title -->
                            <h2 class="text-white fw-bold ms-2 mb-0">
                                <i class="bi bi-info-circle-fill me-1"></i>
                                <?= i18n("Project Info") ?>
                            </h2>
                            <!-- Project Info Section Toggle Button -->
                            <button class="btn btn-lg text-white section-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#projectInfoSection">
                                <i class="bi bi-chevron-up toggle-icon"></i>
                            </button>
                        </div>
                        <!--  Project Info Section Content -->
                        <div class="collapse show" id="projectInfoSection">
                            <div class="card bg-tg-primary shadow-sm h-100">
                                <div class="card-body">
                                    <!-- Project Name -->
                                    <div class="project-info-item mb-4">
                                        <div class="text-light small mb-1">
                                            <i class="bi bi-archive-fill"></i>
                                            <span><?= strtoupper(i18n("Project Name")) ?></span>
                                        </div>
                                        <div class="h5 fw-bold text-white ms-2"><?= $projectInfo->getName() ?></div>
                                    </div>
                                    <!-- Project Description -->
                                    <hr class="text-white">
                                    <div class="project-info-item mb-4">
                                        <div class="text-light small mb-1">
                                            <i class="bi bi-chat-square-text-fill"></i>
                                            <span><?= strtoupper(i18n("Description")) ?></span>
                                        </div>
                                        <div class="h6 fw-bold  fst-italic text-white ms-2"><?= $projectInfo->getDescription() ?></div>
                                    </div>
                                    <!-- Project Owner -->
                                    <hr class="text-white">
                                    <div class="project-info-item mb-4">
                                        <div class="text-light small mb-1">
                                            <i class="bi bi-diamond-fill"></i>
                                            <span><?= strtoupper(i18n("Owner")) ?></span>
                                        </div>
                                        <div class="h6 fw-bold text-white ms-2"><?= $projectInfo->getOwnerUsername() ?></div>
                                    </div>
                                    <!-- Project Creation Date -->
                                    <hr class="text-white">
                                    <div class="project-info-item">
                                        <div class="text-light small mb-1">
                                            <i class="bi bi-calendar-fill me-1"></i>
                                            <span><?= strtoupper(i18n("Created Date")) ?></span>
                                        </div>
                                        <div class="h6 fw-bold text-white ms-2"><?= $projectInfo->getCreatedAtFormatted() ?></div>
                                    </div>
                                </div>                        
                            </div>
                        </div>
                    </div>  

                    <!-- Tasks Overview -->
                    <div class="col-md-4">
                        <!-- Tasks Overview Header -->
                        <div class="d-flex justify-content-between align-items-center mb-2 bg-tg-tertiary-dark rounded">
                            <!-- Task Section Title -->
                            <h2 class="text-white fw-bold ms-2 mb-0">
                                <i class="bi bi-pie-chart-fill  me-1"></i>
                                <?= i18n("Overview") ?>        
                            </h2>                   
                            <!-- Task Section Toggle Button -->     
                            <button class="btn btn-lg text-white section-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#projectOverviewSection">
                                <i class="bi bi-chevron-up toggle-icon"></i>
                            </button>                                                       
                        </div>
                        <!-- Tasks Overview Content -->
                        <div class="collapse show" id="projectOverviewSection">
                            <div class="card bg-tg-tertiary shadow-sm h-100">
                                <!-- Tasks Chart -->
                                <div class="task-chart-container mb-2 mt-3">
                                    <canvas class="task-chart"
                                        data-total-tasks="<?= count($tasks) ?>" 
                                        data-completed-tasks="<?= count($completedTasks) ?>"
                                        data-todo-tasks="<?= count($todoTasks) ?>">
                                    </canvas>
                                </div>
                                <!-- Tasks Stats -->
                                <div class="card-body">
                                    <div class="task-stats text-center mb-4">
                                        <div class="row g-3 justify-content-center">
                                            <!-- Completed Tasks Count -->
                                            <div class="col-3">
                                                <div class="bg-tg-primary border border-light text-white rounded p-2">
                                                    <div class="h5 mb-1"><?= count($completedTasks) ?></div>
                                                    <small class="fw-bold"><?= i18n("Completed") ?></small>
                                                </div>
                                            </div>                                            
                                            <!-- Total Tasks Count -->
                                            <div class="col-4">
                                                <div class="bg-tg-accent border border-light text-white rounded p-2">
                                                    <div class="h3 mb-1"><?= count($tasks) ?></div>
                                                    <small class="fw-bold"><?= i18n("Total Tasks") ?></small>
                                                </div>
                                            </div>
                                            <!-- To Do Tasks Count -->                                        
                                            <div class="col-3">
                                                <div class="bg-tg-secondary border border-light text-white rounded p-2">
                                                    <div class="h5 mb-1"><?= count($todoTasks) ?></div>
                                                    <small class="fw-bold"><?= i18n("To do") ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                            
                                </div>
                            </div>
                        </div>
                    </div>                                        
                    
                    <!-- Members Section -->
                    <div class="col-md-4">
                        <!-- Members Section Header -->
                        <div class="d-flex justify-content-between align-items-center mb-2 bg-tg-secondary-dark rounded">
                            <!-- Members Section Title -->
                            <h2 class="text-white fw-bold ms-2 mb-0">
                                <i class="bi bi-person-circle me-1"></i>    
                                <?= i18n("Members") ?>
                            </h2>
                            <!-- Members Section Toggle Button -->
                            <button class="btn btn-lg text-white section-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#projectMembersSection">
                                <i class="bi bi-chevron-up toggle-icon"></i>
                            </button>
                        </div>
                        <!-- Members Section Content -->
                        <div class="collapse show" id="projectMembersSection">
                            <div class="card bg-tg-secondary shadow-sm h-100">                    
                                <div class="card-body">
                                    <!-- Total Participants -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="text-start small text-light">
                                            <i class="bi bi-people-fill me-1"></i>
                                            <span><?= strtoupper(i18n("Participants: ")) ?></span>
                                            <span class="h4 fw-bold"><?= count($members) ?></span>
                                        </div>
                                        <!-- Add Member Button -->
                                        <button class="btn btn-lg btn-light fw-bold w-30"
                                            onclick="openConfirmModal({
                                                title: '<?= i18n("Add Member") ?>',
                                                message: '<?= i18n("Enter the username of the member to add:") ?>',
                                                action: 'index.php?controller=projects&amp;action=addMember',
                                                id: '<?= $projectInfo->getId() ?>',
                                                inputs: [{
                                                    type: 'text',
                                                    name: 'username',
                                                    label: '<?= i18n("Username") ?>',
                                                    placeholder: '<?= i18n("Enter username") ?>',
                                                }],

                                                confirmButtonText: '<?= i18n("Add Member") ?>',
                                                confirmButtonClass: 'bg-tg-primary text-light'
                                            })"
                                        >
                                            <i class="bi bi-person-fill-add me-2"></i>                                                                                
                                        </button>
                                    </div>
                                    <!-- Member List -->
                                    <hr class="text-white">
                                    <div class="text-light small mb-1">
                                        <i class="bi bi-collection-fill me-1"></i>
                                        <span class="text-white small mb-4"><?= strtoupper(i18n("Member List")) ?></span>
                                    </div>
                                    <?php foreach($members as $member): ?>  
                                        <!-- Member Card -->
                                        <div class="card mt-2 bg-light" data-entity="user" data-id="<?= $member ?>">                                            
                                            <div class="card-body d-flex  justify-content-between align-items-center">
                                                <div class="member-avatar text-dark me-2">
                                                    <p class="fw-bold text-dark">
                                                        <i class="bi bi-person-circle"></i>  
                                                        <?= $member ?>
                                                    </p>                                                 
                                                </div>
                                                <?php if ($projectInfo->getOwnerUsername() === $currentUser): ?>
                                                    <button class="btn btn-danger fw-bold"
                                                        onclick="openConfirmModal({
                                                            title: '<?= i18n("Remove Member") ?>',
                                                            message: '<?= sprintf(i18n("Are you sure you want to remove %s from the project?"), $member) ?>',
                                                            action: 'index.php?controller=projects&amp;action=removeMember',
                                                            id: '<?= $projectInfo->getId() ?>',
                                                            inputs: [{
                                                                type: 'hidden',
                                                                name: 'username',
                                                                value: '<?= $member ?>',                                                            
                                                            }],
                                                            confirmButtonText: '<?= i18n("Remove") ?>',
                                                            confirmButtonClass: 'btn-danger'
                                                        })"
                                                    >
                                                        <i class="bi bi-eraser-fill"></i>
                                                    </button>

                                                <?php endif;?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>    
                                    <!-- Add case no members -->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <!-- Tasks List Header -->
                        <div class="d-flex justify-content-between align-items-center mb-2 bg-tg-second-grey rounded">
                            <h2 class="text-white fw-bold ms-2">
                                <i class="bi bi-clipboard2-fill me-1"></i>
                                <?= i18n("Task List") ?>
                            </h2>
                            <!-- Tasks List Section Toggle Button -->
                            <button class="btn btn-lg text-white section-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#taskListSection">
                                <i class="bi bi-chevron-up toggle-icon"></i>
                            </button>
                        </div>

                        
                        <!-- Tasks List Content -->
                        <div class="collapse show" id="taskListSection">
                            <div class="card bg-secondary shadow-sm">
                                <div class="card-body">
                    
                                    <div class="d-flex justify-content-start mb-2">
                                        <!-- Create Task Button -->                                                         
                                        <a class="btn btn-lg btn-light fw-bold" 
                                            href="<?= "index.php?controller=tasks&amp;action=create&amp;project_id=".$projectInfo->getId() ?>"
                                        >
                                            <i class="bi bi-pencil-square me-1"></i>
                                            <?= i18n("Create Task") ?>
                                        </a>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 your-tasks-container">
                                            <!-- Your Tasks Table -->
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h3 class="h4 text-white fw-bold">
                                                    <i class="bi bi-paperclip me-1"></i>
                                                    <?= i18n("Your Tasks")  ?>
                                                </h3>
                                                <div class="btn-group" role="group" aria-label="Filter Your Tasks">
                                                    <button type="button" class="btn btn-lg filter-btn bg-tg-secondary text-white fw-bold" data-filter="to do"><?= strtoupper(i18n("To do")) ?></button>
                                                    <button type="button" class="btn btn-lg filter-btn active bg-tg-accent text-white fw-bold" data-filter="all"><?= strtoupper(i18n("All")) ?></button>
                                                    <button type="button" class="btn btn-lg filter-btn bg-tg-primary text-white fw-bold" data-filter="completed"><?= strtoupper(i18n("Completed")) ?></button>
                                                </div>
                                            </div>
                                            <hr class="text-white mb-2">
                                            <!-- Your Tasks List -->
                                            <?php foreach($projectInfo->getTasksByUser($currentUser) as $task): ?>
                                                <?php $status = $task->getStatus(); ?>
                                                <div class="row-md-3 mb-2 task-item" data-entity="task" data-id="<?= $task->getId() ?>" data-status="<?= $status ?>">
                                                    <div class="card mb-2 <?= ("completed" === $status) ? 'bg-tg-primary' : 'bg-tg-secondary' ?> text-white">
                                                        <div class="card-body d-flex justify-content-between fw-bold p-2">
                                                            <div class="col-4 my-auto ms-2">                                                                                                                                                                                    
                                                                <?= $task->getTitle() ?>                                                            
                                                            </div>
                                                            <div class="col-4 my-auto text-center">
                                                                    <div>
                                                                    <i class="bi bi-clipboard2-<?= ("completed" === $status) ? "check" : "x" ?>-fill me-1"></i>                                                           
                                                                    <?= strtoupper(i18n($status)) ?>
                                                                    </div>
                                                            </div>
                                                            <div class="col-3 my-auto text-center">
                                                                <div>
                                                                    <?php if("to do" === $status ): ?>
                                                                        <button class="btn btn-light fw-bold"
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
                                                                    <?php endif; ?>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            <?php endforeach; ?>
                                        </div>                                        

                                        <!-- All Tasks Section -->
                                        <div class="col-md-6 all-tasks-container">
                                            <!-- Tasks Table -->
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h3 class="h4 text-white fw-bold">
                                                    <i class="bi bi-folder-fill me-1"></i>
                                                    <?= i18n("All Tasks")  ?>
                                                </h3>
                                                <div class="btn-group" role="group" aria-label="Filter All Tasks">
                                                    <button type="button" class="btn btn-lg filter-btn bg-tg-secondary text-white fw-bold" data-filter="to do"><?= strtoupper(i18n("To do")) ?></button>
                                                    <button type="button" class="btn btn-lg filter-btn active bg-tg-accent text-white fw-bold" data-filter="all"><?= strtoupper(i18n("All")) ?></button>
                                                    <button type="button" class="btn btn-lg filter-btn bg-tg-primary text-white fw-bold" data-filter="completed"><?= strtoupper(i18n("Completed")) ?></button>
                                                </div>
                                            </div>
                                            <hr class="text-white mb-2">
                                            <!-- All Tasks List -->
                                            <?php foreach($tasks as $task): ?>
                                                <?php $status = $task->getStatus(); ?>
                                                <div class="row-md-3 mb-2 task-item" data-entity="task" data-id="<?= $task->getId() ?>" data-status="<?= $status ?>">
                                                    <div class="card mb-2 <?= ("completed" === $status) ? 'bg-tg-primary' : 'bg-tg-secondary' ?> text-white">
                                                        <div class="card-body d-flex justify-content-between fw-bold p-2">
                                                            <div class="col-4 my-auto ms-2">                                                                                                                                                                                                                                                
                                                                <?= $task->getTitle() ?>                                                            
                                                            </div>
                                                            <div class="col-4 my-auto text-center">
                                                                    <div>
                                                                        <i class="bi bi-clipboard2-<?= ("completed" === $status) ? "check" : "x" ?>-fill"></i>    
                                                                        <?= strtoupper(i18n($status)) ?>
                                                                    </div>
                                                            </div>
                                                            <div class="col-3 my-auto text-center">
                                                                <div>                                                                    
                                                                    <a class="btn btn-light fw-bold">
                                                                        <i class="bi bi-briefcase-fill me-1"></i>
                                                                        <?= i18n("Assigned to:") ?>
                                                                        <?= $task->getAssignedUsername() ?>
                                                                    </a>                                                                
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            <?php endforeach; ?>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- Fragments & Variables Setup -->
<?php
// Set view variables
$view->setVariable("page-title", $projectInfo->getName());
$view->setVariable("main-content-header", $projectInfo->getName());

?>

<?php $view->moveToFragment("javascript"); ?>
    <!-- Include any JavaScript specific to the project detail page here -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>    
    <script src="<?= JS_PATH ?>/clickable_entity_card.js"></script>
    <script src="<?= JS_PATH ?>/collapse_sections.js"></script>
    <script src="<?= JS_PATH ?>/chart.js"></script>
    <script src="<?= JS_PATH ?>/task_filter.js"></script>
    <script src="<?= JS_PATH ?>/confirmActionModal.js"></script>
<?php $view->moveToDefaultFragment(); ?>

<?php $view->moveToFragment("actions-header-main") ?>
    <!-- Action Buttons for Project Detail Page -->
    <a class="btn btn-lg btn-light fw-bold" href="<?= "index.php?controller=projects&amp;action=edit&amp;id=" . $projectInfo->getId() ?>">
        <i class="bi bi-pencil-fill me-2"></i>
        <?= i18n("Edit") ?>
    </a>
    <?php if ($currentUser === $projectInfo->getOwnerUsername()): ?>
    <button 
        class="btn btn-lg btn-danger fw-bold"
        onclick="openConfirmModal({
            title: '<?= i18n("Delete Project") ?>',
            message: '<?= i18n("Are you sure you want to delete this project? All associated task will be deleted as well.") ?>',
            action: 'index.php?controller=projects&amp;action=delete',
            id: '<?= $projectInfo->getId() ?>',
            confirmButtonText: '<?= i18n("Delete") ?>',
            confirmButtonClass: 'btn-danger'
        })"
    >
        <i class="bi bi-trash-fill me-2"></i>
        <?= i18n("Delete") ?>
    </button>

    <?php endif; ?>
<?php $view->moveToDefaultFragment(); ?>