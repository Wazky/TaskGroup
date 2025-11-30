<?php 
// file: app/View/pages/projects/edit.php

require_once __DIR__ . '/../../../../config/paths.php';

$view = ViewManager::getInstance();

$projectInfo = $view->getVariable("project");
$members = $projectInfo->getMembers();
?>

<!-- Project EditPage Content  -->
<div class="card border-0 shadow-lg bg-tg-grey">
    <div class="card-body p-4">
        <div class="d-flex justify-content-center align-items-center mb-4">
            <!-- Edit Project Form -->
            <form class="w-50" action="index.php?controller=projects&amp;action=edit&amp;id=<?= $projectInfo->getId() ?>" method="POST">

                <!-- Hidden Project ID -->
                <input type="hidden" name="project_id" value="<?= $projectInfo->getId() ?>">

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
                            <!-- Project Name Editable -->
                            <div class="project-info-item mb-4">
                                <div class="text-light small mb-1">
                                    <i class="bi bi-pencil-fill"></i>
                                    <span><?= strtoupper(i18n("Project Name")) ?></span>
                                </div>
                                <input type="text" name="project_name" class="form-control bg-light border w-50"
                                    value="<?= htmlspecialchars($projectInfo->getName()) ?>" required>                                  
                                
                                <!-- Project Name Error Message -->
                                <?php if(isset($errors["project_name"])): ?>
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        <?= htmlspecialchars($errors["project_name"]) ?>
                                    </div>                                    
                                <?php endif; ?>
                            </div>
                            
                            <hr class="text-white">
                            
                            <!-- Project Description -->
                            <div class="project-info-item mb-4">
                                <div class="text-light small mb-1">
                                    <i class="bi bi-pencil-fill"></i>
                                    <span><?= strtoupper(i18n("Description")) ?></span>
                                </div>
                                <textarea name="project_description" class="form-control bg-light w-50"><?= htmlspecialchars($projectInfo->getDescription()) ?></textarea>
                                
                                <!-- Project Description Error Message -->
                                <?php if(isset($errors["project_description"])): ?>
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        <?= htmlspecialchars($errors["project_description"]) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <hr class="text-white">

                            <!-- Project Owner -->
                            <div class="project-info-item mb-4">
                                <div class="text-light small mb-1">
                                    <i class="bi bi-lock-fill"></i>
                                    <span><?= strtoupper(i18n("Owner")) ?></span>
                                </div>
                                <input type="text" name="project_owner" class="form-control bg-tg-primary text-light fw-bold border-0 w-50"
                                    value="<?= htmlspecialchars($projectInfo->getOwnerUsername()) ?>" disabled required>
                            </div>
                            
                            <hr class="text-white">
                            
                            <!-- Project Creation Date -->
                            <div class="project-info-item">
                                <div class="text-light small mb-1">
                                    <i class="bi bi-lock-fill"></i>
                                    <span><?= strtoupper(i18n("Created Date")) ?></span>
                                </div>
                                <input type="text" name="project_created_date" class="form-control bg-tg-primary text-light fw-bold border-0 w-25"
                                    value="<?= htmlspecialchars($projectInfo->getCreatedAtFormatted()) ?>" disabled required>
                            </div>

                            <hr class="text-white">
                        </div>                        
                        
                        <!-- Form Action Buttons -->
                        <div class="card-footer text-center mb-2 border-0">
                            <button type="submit" name="submit" class="btn btn-lg bg-tg-secondary text-light fw-bold">
                                <i class="bi bi-save me-1"></i>
                                <?= i18n("Save Changes") ?>
                            </button>
                            <button type="button" class="btn btn-lg btn-secondary fw-bold ms-2"
                                onclick="window.location.href='index.php?controller=projects&amp;action=detail&amp;id=<?= $projectInfo->getId() ?>'">
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

<!-- Fragments & Variables Setup -->
<?php
$view->setVariable("page-title", i18n("Edit Project") . " - " . $projectInfo->getName());
$view->setVariable("main-content-header", $projectInfo->getName() . " - " . i18n("Edit Project"));

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
