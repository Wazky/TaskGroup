<?php
// file: /app/Controller/ProjectsController.php

require_once(__DIR__.'/../core/ViewManager.php');
require_once(__DIR__.'/../core/I18n.php');

require_once(__DIR__.'/../Model/Entity/User.php');
require_once(__DIR__.'/../Model/Mapper/UserMapper.php');

require_once(__DIR__.'/../Model/Entity/Project.php');
require_once(__DIR__.'/../Model/Mapper/ProjectMapper.php');

require_once(__DIR__.'/../Model/Entity/Task.php');
require_once(__DIR__.'/../Model/Mapper/TaskMapper.php');

require_once(__DIR__.'/BaseController.php');

class ProjectsController extends BaseController {

    // PROJECTS CONSTANTS

    private const PROJECTS_LAYOUT = "dashboard"; 
    private const PROJECTS_CONTROLLER_NAME = "projects";
    private const PROJECTS_INDEX_ACTION = "index";
    private const PROJECTS_LIST_ACTION = "list";
    private const PROJECTS_DETAIL_ACTION = "detail";    
    private const PROJECTS_CREATE_ACTION = "create";
    private const PROJECTS_EDIT_ACTION = "edit";    
    private const PROJECTS_DELETE_ACTION = "delete";

    // AUTHENTICATION CONSTANTS

    private const AUTH_CONTROLLER_NAME = "auth";
    private const AUTH_LOGIN_ACTION = "login";

    /**
     * Reference to the ProjectMapper to interact with the database
     * 
     * @var ProjectMapper
     */
    private $projectMapper;

    /**
     * Reference to the TaskMapper to interact with the database
     * 
     * @var TaskMapper
     */
    private $taskMapper;

    /**
     * Reference to the UserMapper to interact with the database
     * 
     * @var UserMapper
     */
    private $userMapper;

    public function __construct() {
        
        parent::__construct();
        $this->projectMapper = new ProjectMapper();
        $this->taskMapper = new TaskMapper();
        $this->userMapper = new UserMapper();

        // Set the layout for projects pages
        $this->view->setLayout(self::PROJECTS_LAYOUT);
    }

    public function index() {
        // Check authentication
        $this->requireAuthentication();
        

        $this->projectMapper->findByOwner($this->currentUser->getUsername());
        $this->projectMapper->findByMemberOnly($this->currentUser->getUsername());

        $this->view->render(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_INDEX_ACTION);
    
    }

    public function create() {
        // Check authentication
        $this->requireAuthentication();

        $project = new Project();

        // Handle form submission
        if (isset($_POST["submit"])) {

            // Populate project entity with form data
            $project->setName($_POST["project_name"]);
            $project->setDescription($_POST["project_description"]);
            $project->setOwnerUsername($this->currentUser->getUsername());
            // Save members in case of validation error to show them again
            $tempMembers = $_POST["members"] ?? array();
            
            try {
                $invalidMembers = array();
                // Add valid members to the project entity
                foreach ($tempMembers as $memberUsername) {
                    // Check existence/validity and add them
                    if (($user = $this->userMapper->getUser($memberUsername)) !== null) {
                        $project->addMember($user->getUsername());
                    
                    // If user invalid add to invalid members array
                    } else {
                        $invalidMembers[] .= $memberUsername;
                    }              
                }
                
                // If there are invalid members, throw validation exception
                if (count($invalidMembers) > 0) {
                    // Restore members to tempMembers for re-display in the form
                    $project->setMembers($tempMembers);
                    $errors["members"] = sprintf(i18n("The following members do not exist: %s"), implode(", ", $invalidMembers));
                    throw new ValidationException($errors);
                }

                // Validate Project object
                $project->checkIsValidForCreate();

                // Save the project to the database
                $project = $this->projectMapper->save($project);

                // POST-REDIRECT-GET
                // Set success flash message
                $this->prepareAlert(sprintf(i18n("Project \"%s\" successfully created."), $project->getName()), "alert-success", "bi-check-circle-fill");
                // Redirect to created project's detail page
                $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_DETAIL_ACTION,"id=".$project->getId());

            } catch (ValidationException $ex) {            
                $this->view->setVariable("errors", $ex->getErrors());
                $this->prepareAlert(i18n("Invalid data provided."), "alert-danger", "bi-exclamation-triangle-fill");                
            }
        }

        // Set project variable for the view
        $this->view->setVariable("project", $project);
        // Render the create view
        $this->view->render(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_CREATE_ACTION);

    }

    public function list() {
        // Check authentication
        $this->requireAuthentication();

        // Get projects owned by the username
        $ownedProjects = $this->projectMapper->findByOwnerWithCounts($this->currentUser->getUsername());
        $this->view->setVariable("ownedProjects", $ownedProjects);
        
        // Get projects where the user is just a member
        $memberProjects = $this->projectMapper->findByMemberOnlyWithCounts($this->currentUser->getUsername());
        $this->view->setVariable("memberProjects", $memberProjects);

        // Render the list view
        $this->view->render(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
    }

    public function detail() {
        // Check authentication
        $this->requireAuthentication();

        // Check if project id is provided
        if (!isset($_GET["id"])) {
            // No project id provided
            $this->prepareAlert(i18n("Project ID is required to access a project detail."), "alert-info", "bi-info-circle");
            
            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Obtain project id from query parameters
        $projectId = $_GET["id"];

        // Check if the current user is member of the project (Independent of project existence)
        if (!$this->projectMapper->isUserMember($this->currentUser->getUsername(), $projectId)) {
            // No permission to access the project
            $this->prepareAlert(i18n("You do not have permission to access this project."), "alert-danger", "bi-exclamation-triangle-fill");

            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);        
        }

        // Get basic project info
        $project = $this->projectMapper->findById($projectId);

        // Verify that the project exists
        if ($project == null) {
            // No project found with the given ID
            $this->prepareAlert(i18n("No project found with the given ID."), "alert-info", "bi-info-circle");            
            
            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);            
        }

        // Get info of project members
        $members = $this->projectMapper->getProjectMembers($projectId);
        $project->setMembers($members);
        
        // Get info of project tasks
        $tasks = $this->taskMapper->findByProjectId($projectId);
        $project->setTasks($tasks);

        // Set project variable for the view
        $this->view->setVariable("project", $project);

        // Render the detail view
        $this->view->render(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_DETAIL_ACTION);
    }

    public function edit() {
        // Check authentication
        $this->requireAuthentication();

        // Check if project id is provided
        if (!isset($_REQUEST["id"])) {
            // No project id provided
            $this->prepareAlert(i18n("Project ID is required."), "alert-info", "bi-info-circle");

            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Obtain project from database
        $projectId = $_REQUEST["id"];
        $project = $this->projectMapper->findById($projectId);

        // Verify that the project exists
        if ($project == null) {
            // No project found with the given ID
            $this->prepareAlert(i18n("No project found with the given ID."), "alert-info", "bi-info-circle");
            
            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Verify that the current user is member of the project
        if (!$this->projectMapper->isUserMember($this->currentUser->getUsername(), $projectId)) {
            // No permission to edit the project
            $this->prepareAlert(i18n("You do not have permission to edit this project."), "alert-danger", "bi-exclamation-triangle-fill");
            
            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Handle form submission
        if (isset($_POST["submit"])) {
            
            // Populate project entity with form data
            $project->setName($_POST["project_name"]);
            $project->setDescription($_POST["project_description"]);

            try {
                // Validate Project object
                $project->checkIsValidForUpdate();

                // Save the project to the database
                $this->projectMapper->save($project);
                
                // POST-REDIRECT-GET
                // Set success flash message
                $this->prepareAlert(sprintf(i18n("Project \"%s\" successfully updated."), $project->getName()), "alert-success", "bi-check-circle-fill");                
                // Redirect to updated project's detail page
                $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_DETAIL_ACTION,"id=".$project->getId());

            } catch (ValidationException $ex) {
                // Get the errors from the exception
                $errors = $ex->getErrors();
                // Set errors to the view
                $this->view->setVariable("errors", $errors);
                $this->prepareAlert(i18n("Invalid data provided."), "alert-danger", "bi-exclamation-triangle-fill");                                
            }
        }

        // Set project variable for the view
        $this->view->setVariable("project", $project);
        // Render the edit view
        $this->view->render(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_EDIT_ACTION);

    }

    public function delete() {
        // Check authentication
        $this->requireAuthentication();

        // Check if project id is provided
        if (!isset($_POST["id"])) {
            // No project id provided
            $this->prepareAlert(i18n("Project ID is required to delete a project."), "alert-info", "bi-info-circle");

            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Obtain project from database
        $projectId = $_POST["id"];
        $project = $this->projectMapper->findById($projectId);

        // Verify that the project exists
        if ($project == null) {
            // No project found with the given ID
            $this->prepareAlert(i18n("No project found with the given ID."), "alert-info", "bi-info-circle");

            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        if ($project->getOwnerUsername() !== $this->currentUser->getUsername()) {
            // No permission to delete the project
            $this->prepareAlert(i18n("You do not have permission to delete this project."), "alert-danger", "bi-exclamation-triangle-fill");

            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Delete the project
        $this->projectMapper->delete($projectId);

        // POST-REDIRECT-GET 
        $this->prepareAlert(sprintf(i18n("Project \"%s\" successfully deleted."), $project->getName()), "alert-success", "bi-check-circle-fill");

        $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);

    }

    public function addMember() {
        // Check authentication
        $this->requireAuthentication();

        // Check if project id or username are provided
        if (!isset($_POST["id"]) || !isset($_POST["username"])) {            
            $this->prepareAlert(i18n("Project ID and Username are required."), "alert-danger", "bi-exclamation-triangle-fill");

            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Obtain project from database
        $projectId = $_POST["id"];
        $project = $this->projectMapper->findById($projectId);

        // Verify that the project exists
        if ($project == null) {
            $this->prepareAlert(i18n("No project found with the given ID."), "alert-info", "bi-info-circle");

            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        $usernameToAdd = $_POST["username"];
        
        // Verify that username is provided
        if ($usernameToAdd == null) {
            $this->prepareAlert(i18n("Username is required."), "alert-danger", "bi-exclamation-triangle-fill");

            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Verify that the current user is a member of the project
        if (!$this->projectMapper->isUserMember($this->currentUser->getUsername(), $projectId)) {
            $this->prepareAlert(i18n("You do not have permission to add members to this project."), "alert-danger", "bi-exclamation-triangle-fill");
            
            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }
        
        // Verify that the user to add exists
        $userToAdd = $this->userMapper->getUser($usernameToAdd);
        if ($userToAdd == null) {
            $this->prepareAlert(i18n("No user found with the given username/email."), "alert-info", "bi-info-circle");

            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Verify that the current user is not already a member of the project
        if ($this->projectMapper->isUserMember($userToAdd->getUsername(), $projectId)) {
            $this->prepareAlert(i18n("User is already a member of the project."), "alert-info", "bi-info-circle");

            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Add the member to the project
        $this->projectMapper->addMember($userToAdd->getUsername(), $projectId);

        // POST-REDIRECT-GET
        $this->prepareAlert(sprintf(i18n("User \"%s\" successfully added to project \"%s\"."), $userToAdd->getUsername(), $project->getName()), "alert-success", "bi-check-circle-fill");

        $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_DETAIL_ACTION, "id=" . $projectId);
    }

    public function removeMember() {
        // Check authentication
        $this->requireAuthentication();

        if (!isset($_POST["id"]) || !isset($_POST["username"])) {
            $this->prepareAlert(i18n("Project ID and Username are required."), "alert-danger", "bi-exclamation-triangle-fill");
            
            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Obtain project from database
        $projectId = $_POST["id"];
        $project = $this->projectMapper->findById($projectId);

        // Verify that the project exists
        if ($project == null) {
            $this->prepareAlert(i18n("No project found with the given ID."), "alert-info", "bi-info-circle");

            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        $usernameToRemove = $_POST["username"];
        // Verify that username is provided 
        if ($usernameToRemove == null) {
            $this->prepareAlert(i18n("Username is required."), "alert-danger", "bi-exclamation-triangle-fill");

            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Verify that the current user is the owner of the project
        if ($project->getOwnerUsername()  !== $this->currentUser->getUsername()) {
            $this->prepareAlert(i18n("You do not have permission to remove members from this project."), "alert-danger", "bi-exclamation-triangle-fill");

            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }


        // Verify that the user to remove exists
        $userToRemove = $this->userMapper->getUser($usernameToRemove);
        if ($userToRemove == null) {
            $this->prepareAlert(i18n("No user found with the given username/email."), "alert-info", "bi-info-circle");

            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Verify that the user to remove is a member of the project
        if (!$this->projectMapper->isUserMember($usernameToRemove, $projectId)) {
            $this->prepareAlert(i18n("User is not a member of the project."), "alert-info", "bi-info-circle");

            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Prevent removing the owner from the project
        if ($usernameToRemove === $project->getOwnerUsername()) {
            $this->prepareAlert(i18n("Cannot remove the owner from the project."), "alert-danger", "bi-exclamation-triangle-fill");

            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Remove the member from the project
        $this->projectMapper->removeMember($usernameToRemove, $projectId);

        // POST-REDIRECT-GET
        $this->prepareAlert(sprintf(i18n("User \"%s\" successfully removed from project \"%s\"."), $userToRemove->getUsername(), $project->getName()), "alert-success", "bi-check-circle");

        $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_DETAIL_ACTION, "id=" . $projectId);
    }

    private function prepareAlert($message, $type = "alert-info", $icon = "bi-info-circle") {
        $this->view->setFlash(i18n($message));
        $this->view->setVariable("flash-type", $type, true);
        $this->view->setVariable("flash-icon", $icon, true);
    }

}

?>