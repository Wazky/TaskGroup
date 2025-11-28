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
                $this->view->setFlash(sprintf(i18n("Project \"%s\" successfully created."), $project->getName()));
                // Redirect to created project's detail page
                $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_DETAIL_ACTION,"id=".$project->getId());

            } catch (ValidationException $ex) {
                // Get the errors from the exception
                $errors = $ex->getErrors();
                // Set errors to the view
                $this->view->setVariable("errors", $errors);                
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
            $this->view->setFlash(i18n("Project ID is required."));
            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Obtain project id from query parameters
        $projectId = $_GET["id"];

        // Check if the current user is member of the project (Independent of project existence)
        if (!$this->projectMapper->isUserMember($this->currentUser->getUsername(), $projectId)) {
            $errors = array();
            $errors["general"] = i18n("You do not have permission to access this project.");            
            $this->view->setVariable("errors", $errors);
            // La variable no aplica al usar redirect (mirar de solucionar)

            $this->view->setFlash(i18n("MSG BY FLASH"));
            $this->view->setVariable("flash-type", "alert alert-danger", true);

            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
            return;
        }

        // Get basic project info
        $project = $this->projectMapper->findById($projectId);

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
            throw new Exception(i18n("Project ID is required."));
        }

        // Obtain project from database
        $projectId = $_REQUEST["id"];
        $project = $this->projectMapper->findById($projectId);

        // Verify that the project exists
        if ($project == null) {
            throw new Exception(i18n("No project found with the given ID."));
        }

        // Verify that the current user is member of the project
        if (!$this->projectMapper->isUserMember($this->currentUser->getUsername(), $projectId)) {
            throw new Exception(i18n("You do not have permission to edit this project."));
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
                $this->view->setFlash(sprintf(i18n("Project \"%s\" successfully updated."), $project->getName()));
                // Redirect to updated project's detail page
                $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_DETAIL_ACTION,"id=".$project->getId());

            } catch (ValidationException $ex) {
                // Get the errors from the exception
                $errors = $ex->getErrors();
                // Set errors to the view
                $this->view->setVariable("errors", $errors);                                
            }
        }

        $this->view->setVariable("project", $project);

        $this->view->render(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_EDIT_ACTION);

    }

    public function delete() {
        // Check authentication
        $this->requireAuthentication();

        // Check if project id is provided
        if (!isset($_POST["id"])) {
            throw new Exception(i18n("Project ID is required."));
        }

        // Obtain project from database
        $projectId = $_POST["id"];
        $project = $this->projectMapper->findById($projectId);

        // Verify that the project exists
        if ($project == null) {
            throw new Exception(i18n("No project found with the given ID."));
        }

        if ($project->getOwnerUsername() !== $this->currentUser->getUsername()) {
            throw new Exception(i18n("You do not have permission to delete this project."));
        }

        // Delete the project
        $this->projectMapper->delete($projectId);

        // POST-REDIRECT-GET 
        $this->view->setFlash(sprintf(i18n("Project \"%s\" successfully deleted."), $project->getName()));

        $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);

    }

    public function addMember() {
        // Check authentication
        $this->requireAuthentication();

        if (!isset($_POST["id"]) || !isset($_POST["username"])) {
            throw new Exception(i18n("Project ID and Username are required."));

        }

        // Obtain project from database
        $projectId = $_POST["id"];
        $project = $this->projectMapper->findById($projectId);

        // Verify that the project exists
        if ($project == null) {
            throw new Exception(i18n("No project found with the given ID."));
        }

        $usernameToAdd = $_POST["username"];
        // Verify that username is provided
        if ($usernameToAdd == null) {
            throw new Exception(i18n("Username is required."));
        }
        // Verify that the current user is a member of the project
        if (!$this->projectMapper->isUserMember($this->currentUser->getUsername(), $projectId)) {
            throw new Exception(i18n("You do not have permission to add members to this project."));
        }
        
        // Verify that the user to add exists
        $userToAdd = $this->userMapper->getUser($usernameToAdd);
        if ($userToAdd == null) {
            throw new Exception(i18n("No user found with the given username/email."));
        }

        // Verify that the current user is not already a member of the project
        if ($this->projectMapper->isUserMember($userToAdd->getUsername(), $projectId)) {
            throw new Exception(i18n("User is already a member of the project."));
        }

        // Add the member to the project
        $this->projectMapper->addMember($userToAdd->getUsername(), $projectId);

        // POST-REDIRECT-GET
        $this->view->setFlash(sprintf(i18n("User \"%s\" successfully added to project \"%s\"."), $userToAdd->getUsername(), $project->getName()));

        $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_DETAIL_ACTION, "id=" . $projectId);
    }

    public function removeMember() {
        // Check authentication
        $this->requireAuthentication();

        if (!isset($_POST["id"]) || !isset($_POST["username"])) {
            throw new Exception(i18n("Project ID and Username are required."));
        }

        // Obtain project from database
        $projectId = $_POST["id"];
        $project = $this->projectMapper->findById($projectId);

        // Verify that the project exists
        if ($project == null) {
            throw new Exception(i18n("No project found with the given ID."));
        }

        $usernameToRemove = $_POST["username"];
        // Verify that username is provided 
        if ($usernameToRemove == null) {
            throw new Exception(i18n("Username is required."));
        }

        // Verify that the current user is the owner of the project
        if ($project->getOwnerUsername()  !== $this->currentUser->getUsername()) {
            throw new Exception(i18n("You do not have permission to remove members from this project."));
        }


        // Verify that the user to remove exists
        $userToRemove = $this->userMapper->getUser($usernameToRemove);
        if ($userToRemove == null) {
            throw new Exception(i18n("No user found with the given username/email."));
        }

        // Verify that the user to remove is a member of the project
        if (!$this->projectMapper->isUserMember($usernameToRemove, $projectId)) {
            throw new Exception(i18n("User is not a member of the project."));
        }

        // Prevent removing the owner from the project
        if ($usernameToRemove === $project->getOwnerUsername()) {
            throw new Exception(i18n("Cannot remove the owner from the project."));
        }

        // Remove the member from the project
        $this->projectMapper->removeMember($usernameToRemove, $projectId);

        // POST-REDIRECT-GET
        $this->view->setFlash(sprintf(i18n("User \"%s\" successfully removed from project \"%s\"."), $userToRemove->getUsername(), $project->getName()));

        $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_DETAIL_ACTION, "id=" . $projectId);
    }

}

?>