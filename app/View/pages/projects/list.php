<?php
// file: /app/View/pages/projects/list.php

require_once __DIR__ . '/../../../../config/paths.php';

$view = ViewManager::getInstance();
$errors = $view->getVariable("errors");
$currentUser = $view->getVariable("current_user");

$ownedProjects = $view->getVariable("ownedProjects");
$memberProjects = $view->getVariable("memberProjects"); 
?>

<!--Project List Page Content -->
<div class="card border-0 shadow-sm bg-tg-grey mb-2">
    <!-- Owned Projects Section -->
    <div class="card-body">
        <!-- Owned Projects Header -->
        <div class="d-flex justify-content-between align-items-center mb-2 ms-2 ">
            <!-- Section Title -->
            <h2 class="h4 card-title  text-white "> <?= strtoupper(i18n("Your Projects")) ?></h2>
            <!-- Section Actions -->
            <div class="page-actions">
                <a href="index.php?controller=projects&action=create" class="btn btn-light btn-lg">
                    <i class="bi bi-plus-square-fill me-1"></i><?= i18n("New Project") ?>
                </a>
                <button class="btn btn-lg text-white section-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#ownedProjectList">
                    <i class="bi bi-chevron-up toggle-icon"></i>
                </button>
            </div>
        </div>
        <!-- Owned Projects List -->
        <div class="collapse show" id="ownedProjectList">
            <hr class="text-white border-2"/>
            <div class="col mt-4">
                <?php foreach($ownedProjects as $index => $project): ?> 
                    <div class="row-md-3 mb-2" data-entity="project" data-id="<?= $project->getId() ?>">
                        <div class="card <?= ($index % 2 == 0) ? 'bg-tg-secondary' : 'bg-tg-primary' ?> text-white">
                            <div class="card-body d-flex justify-content-between">
                                <div class="col-5 my-auto">
                                    <h3 class="h3 fw-bold"><?= $project->getName() ?></h3>                                
                                </div>
                                <div class="col-4 my-auto align-left">
                                    <h3 class="h6 ms-3 fst-italic"><?= (strlen($project->getDescription()) > 60) ? substr($project->getDescription(), 0, 60) . "..." : $project->getDescription() ?></h3>    
                                    
                                </div>                      
                                <div class ="col-1 text-center my-auto">
                                    <h3 class="h4 fw-bold"><?= $project->getMemberCount() ?></h3>
                                    <small><?= i18n("Members") ?></small>
                                </div>
                                <div class ="col-1 text-center my-auto">
                                    <h3 class="h4 fw-bold"><?= $project->getTaskCount() ?></h3>
                                    <small><?= i18n("Tasks") ?></small>
                                </div>                                         
                                <div class="col-1 my-auto">
                                    <div class="d-flex flex-column gap-2">                                                                                                                                  
                                                <a href="<?= "index.php?controller=projects&amp;action=edit&amp;id=" . $project->getId() ?>" class="btn btn-light "><i class="bi bi-pencil-fill"></i></a>
                                                <button class="btn btn-light"
                                                    onclick="openConfirmModal({
                                                        title: '<?= i18n("Delete project") ?>',
                                                        message: '<?= i18n("Are you sure you want to delete this project? All associated task will be deleted as well.") ?>',
                                                        action: 'index.php?controller=projects&amp;action=delete',
                                                        id: <?= $project->getId() ?>,
                                                        confirmButtonText: '<?= i18n("Delete") ?>',
                                                        confirmButtonClass: 'btn-danger'    
                                                    })"
                                                >
                                                    <i class="bi bi-trash-fill"></i>                                                
                                                </button>
                                    </div>                                
                                </div>                                                  
                            </div>
                            <div class="card-footer text-end">
                                <?= i18n("Created on") . ": " . $project->getCreatedAtFormatted() ?>
                            </div>
                        </div>                                        
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>                   
</div>

<div class="card border-0 shadow-sm bg-tg-grey mb-2 mt-2">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 card-title  text-white "> <?= strtoupper(i18n("Member of")) ?></h2>        
            <button class="btn btn-lg  text-white section-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#memberOfProjectList">
                <i class="bi bi-chevron-up toggle-icon"></i>
            </button>
                </div>
        <div class="collapse show" id="memberOfProjectList">
            <hr class="text-white border-2"/>
            <div class="row mt-4">
                <?php foreach($memberProjects as $index => $project): ?>
                    <div class="row-md-3 mb-2" data-entity="project" data-id="<?= $project->getId() ?>">
                        <div class="card <?= ($index % 2 == 0) ? 'bg-tg-secondary' : 'bg-tg-primary' ?> text-white">
                            <div class="card-body d-flex justify-content-between">
                                <div class="col-5 my-auto">
                                    <h3 class="h3 fw-bold"><?= $project->getName() ?></h3>                                
                                </div>
                                <div class="col-4 my-auto align-left">
                                    <h3 class="h6 ms-3 fst-italic"><?= (strlen($project->getDescription()) > 60) ? substr($project->getDescription(), 0, 60) . "..." : $project->getDescription() ?></h3>    
                                    
                                </div>                      
                                <div class ="col-1 text-center my-auto">
                                    <h3 class="h4 fw-bold"><?= $project->getMemberCount() ?></h3>
                                    <small> <?= i18n("Members") ?></small>
                                </div>
                                <div class ="col-1 text-center my-auto">
                                    <h3 class="h4 fw-bold"><?= $project->getTaskCount() ?></h3>
                                    <small><?= i18n("Tasks") ?></small>
                                </div>                                                                                           
                            </div>
                        </div>               
                        </a> 
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>    
</div>

<!-- Fragments & Variables Setup -->
<?php

// Set view variables
$view->setVariable("page-title", i18n("Projects"));
$view->setVariable("main-content-header", strtoupper(i18n("Projects")));
?>

<?php $view->moveToFragment("javascript"); ?>
    <script src="<?= JS_PATH ?>/clickable_entity_card.js"></script>
    <script src="<?= JS_PATH ?>/collapse_sections.js"></script>
<?php $view->moveToDefaultFragment(); ?>
