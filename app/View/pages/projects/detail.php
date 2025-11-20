<?php
// file: app/View/pages/projects/detail.php

require_once __DIR__ . '/../../../../config/paths.php';

$view = ViewManager::getInstance();
$errors = $view->getVariable("errors");
$currentUser = $view->getVariable("current_user");

$projectInfo = $view->getVariable("project");

?>



<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-lg bg-tg-grey">
            <div class="card-body p-4">
                <div class="row g-4">
                    <!-- Project Info -->
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-center mb-2 bg-tg-primary-dark rounded">
                            <h2 class="text-white fw-bold ms-2 mb-0">
                                <i class="bi bi-info-circle-fill me-1"></i>
                                Project Info
                            </h2>
                            <button class="btn btn-lg text-white section-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#projectInfoSection">
                                <i class="bi bi-chevron-up toggle-icon"></i>
                            </button>
                        </div>
                        <div class="collapse show" id="projectInfoSection">
                            <div class="card bg-tg-primary shadow-sm h-100">
                                <div class="card-body">
                                    <div class="project-info-item mb-4">
                                        <div class="d-flex align-items-center text-white small mb-1">
                                            <i class="bi bi-archive-fill me-1"></i>
                                            <span>PROJECT NAME</span>
                                        </div>
                                        <div class="h5 fw-bold text-white"><?= $projectInfo->getName() ?></div>
                                    </div>
                                    <hr class="text-white">
                                    <div class="project-info-item mb-4">
                                        <div class="d-flex align-items-center text-white small mb-1">
                                            <i class="bi bi-chat-square-text-fill me-1"></i>
                                            <span>DESCRIPTION</span>
                                        </div>
                                        <div class="text-white"><?= $projectInfo->getDescription() ?></div>
                                    </div>
                                    <hr class="text-white">
                                    <div class="project-info-item mb-4">
                                        <div class="d-flex align-items-center text-white small mb-1">
                                            <i class="bi bi-diamond-fill me-1"></i>
                                            <span>OWNER</span>
                                        </div>
                                        <div class="h6 fw-bold text-white"><?= $projectInfo->getOwnerUsername() ?></div>
                                    </div>
                                    <hr class="text-white">
                                    <div class="project-info-item">
                                        <div class="d-flex align-items-center text-white small mb-1">
                                            <i class="bi bi-calendar-fill me-1"></i>
                                            <span>CREATED DATE</span>
                                        </div>
                                        <div class="h6 text-white"><?= $projectInfo->getCreatedAtFormatted() ?></div>
                                    </div>
                                </div>                        
                            </div>
                        </div>
                    </div>

                    <!-- Members -->
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-center mb-2 bg-tg-secondary-dark rounded">
                            <h2 class="text-white fw-bold ms-2 mb-0">
                                <i class="bi bi-person me-1"></i>    
                                Team Members
                            </h2>
                            <button class="btn btn-lg text-white section-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#projectMembersSection">
                                <i class="bi bi-chevron-up toggle-icon"></i>
                            </button>
                        </div>
                        <div class="card bg-tg-secondary border-0 shadow-sm h-100">
                            <div class="card-body">
                                <?php foreach($projectInfo->getMembers() as $member): ?>
                                    <div class="member-item d-flex align-items-center mb-3 p-2 bg-light  rounded" data-entity="user" data-id="<?= $member ?>">
                                        <div class="member-avatar bg-tg-secondary text-dark rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="bi bi-person-circle"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?= $member ?></div>                                            
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                
                                <button class="btn btn-outline-secondary btn-light btn-sm w-100 mt-2">
                                    <i class="fas fa-plus me-2"></i>Add Member
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tasks Info -->
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-tasks text-tg-secondary me-2 fs-5"></i>
                            <h2 class="text-tg-secondary fw-bold mb-0">Tasks Overview</h2>
                        </div>
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="task-stats text-center mb-4">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="bg-primary text-white rounded p-3">
                                                <div class="h4 mb-1">12</div>
                                                <small>Total Tasks</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="bg-success text-white rounded p-3">
                                                <div class="h4 mb-1">8</div>
                                                <small>Completed</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="bg-warning text-white rounded p-3">
                                                <div class="h4 mb-1">3</div>
                                                <small>In Progress</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="bg-danger text-white rounded p-3">
                                                <div class="h4 mb-1">1</div>
                                                <small>Pending</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="progress mb-3" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: 67%"></div>
                                    <div class="progress-bar bg-warning" style="width: 25%"></div>
                                    <div class="progress-bar bg-danger" style="width: 8%"></div>
                                </div>
                                <div class="text-center small text-muted">
                                    67% Complete • 25% In Progress • 8% Pending
                                </div>
                                
                                <button class="btn btn-tg-secondary btn-sm w-100 mt-3">
                                    <i class="fas fa-eye me-2"></i>View All Tasks
                                </button>
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
$view->setVariable("page-title", i18n("Project Details"));
$view->setVariable("main-content-header", $projectInfo->getName());

?>

<?php $view->moveToFragment("javascript"); ?>
    <!-- Include any JavaScript specific to the project detail page here -->
    <script src="<?= JS_PATH ?>/clickable_entity_card.js"></script>
<?php $view->moveToDefaultFragment(); ?>