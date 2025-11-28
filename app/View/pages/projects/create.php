<?php
// file: app/View/pages/projects/create.php

require_once __DIR__ . '/../../../../config/paths.php';

$view = ViewManager::getInstance();
$errors = $view->getVariable("errors");
$currentUser = $view->getVariable("current_user");

$project = $view->getVariable("project");

?>

<!-- Project Create Page Content -->
<div class="page-content">

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-6">
            <div class="card border-0 shadow-sm bg-tg-grey">
                <div class="card-body">
                    <div class="d-flex justify-content-center align-items-center mb-4">

                        <!-- Create Project Form Header -->
                        <h2 class="h3 card-title text-white mb-0 bg-tg-secondary fw-bold rounded py-2 px-4">
                            <i class="bi bi-file-earmark-text-fill text-light"></i>
                            <?= i18n("New Project Form") ?>
                        </h2>                        
                        
                    </div>

                    <!-- Create Project Form Content -->
                    <div class="d-flex justify-content-center">
                        <form class="w-75" action="index.php?controller=projects&amp;action=create" method="POST">

                            <!-- Project Name Section -->
                            <div class="mb-4">
                                <!-- Project Name Label -->
                                <label for="project_name" class="form-label fw-semibold text-light">
                                    <i class="bi bi-archive-fill me-1"></i>
                                    <?= strtoupper(i18n("Project Name")) ?> 
                                    <span class="text-danger">* </span>
                                </label>
                                <!-- Project Name Input -->
                                <input type="text" class="form-control form-control-lg <?= isset($errors["project_name"]) ? 'error' : '' ?>"
                                    id="project_name" name="project_name" value="<?= htmlspecialchars($project->getName()) ?>"
                                    placeholder="<?= i18n("Enter the project name")     ?>" required
                                >
                                <!-- Project Name Error Message -->
                                <?php if(isset($errors["project_name"])): ?>
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        <?= htmlspecialchars($errors["project_name"]) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <hr class="text-white border-2"/>

                            <!-- Project Description Section -->
                            <div class="mb-4">
                                    <!-- Project Description Label -->
                                    <label for="project_description" class="form-label fw-semibold text-light">
                                        <i class="bi bi-chat-square-text-fill me-1"></i>
                                        <?= strtoupper(i18n("project description")) ?>
                                    </label>
                                    <!-- Project Description Textarea -->
                                    <textarea class="form-control <?= isset($errors["project_description"]) ? 'error' : '' ?>"
                                        id="project_description" name="project_description" rows="4"
                                    >
                                        <?= htmlspecialchars($project->getDescription()) ?>
                                    </textarea>
                                    <!-- Project Description Error Message -->
                                    <?php if(isset($errors["project_description"])): ?>
                                        <div class="invalid-feedback d-block">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                            <?= htmlspecialchars($errors["project_description"]) ?>
                                        </div>
                                    <?php endif;?>
                            </div>

                            <hr class="text-white border-2"/>   

                            <!-- Project Members Section -->
                            <div class="mb-2">
                                <!-- Project Members Label -->
                                <label for="project_members" class="form-label fw-semibold text-light">
                                    <i class="bi bi-person-plus-fill me-1"></i>
                                    <?= strtoupper(i18n("Add Members")) ?>
                                </label>
                                <!-- Project Members Input Group -->
                                <div class="w-100">
                                    <div class="d-flex align-items-center gap-3 flex-nowrap" id="input-group-container">
                                        <div class="input-group mb-3 w-50" id="input-group">
                                            <input type="text" class="form-control" id="memberInput" placeholder="Enter username..."
                                                aria-label="Enter username...">
                                            
                                            <button class="btn btn-primary rounded-end text-light" type="button" id="buttonAddMember">
                                                <i class="bi bi-person-plus-fill me-1"></i>    
                                                Add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Project Members List Section -->
                                <div class="ms-1">               
                                    <!-- Project Members List Header -->                 
                                    <h3 class="h6 text-light mb-2">                                        
                                        <?= i18n("Members:") ?>
                                    </h3>
                                    <!-- Project Members List -->
                                    <div id="memberContainer" class="d-flex flex-wrap gap-2 mb-1">
                                        <!-- Member Items Will Be Added Here Dynamically -->
                                    </div>  
                                    <!-- Hidden Inputs for Members -->
                                    <div id="membersHiddenInputs">
                                        <!-- Hidden input elements for members will be added here dynamically -->

                                        <!-- Preserve previously added members in case of validation errors -->
                                        <?php if(isset($errors["members"])): ?>
                                            <?php foreach($project->getMembers() as $member): ?>
                                                <input type="hidden" name="members[]" value="<?= htmlspecialchars($member) ?>">
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>                                    
                                </div>

                                <!-- Project Members Error Message -->
                                <?php if(isset($errors["members"])): ?>
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        <?= htmlspecialchars($errors["members"]) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <hr class="text-white border-2"/>

                            <!-- Form Buttons Section -->
                            <div class="d-flex justify-content-center gap-2 pt-2 mb-3">
                                <!-- Submit Button -->
                                <button type="submit" name="submit" class="btn btn-lg bg-tg-secondary text-light fw-bold">
                                    <i class="bi bi-plus-circle-fill me-1"></i>
                                    <?= i18n("Create Project") ?>
                                </button>
                                <!-- Cancel Button -->
                                <a href="index.php?controller=projects&amp;action=list" class="btn btn-lg btn-secondary fw-bold">
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