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

    public function __construct() {
        
        parent::__construct();
        $this->projectMapper = new ProjectMapper();
        $this->taskMapper = new TaskMapper();

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

        // Check if the current user is member of the project
        if (!$this->projectMapper->isUserMember($this->currentUser->getUsername(), $projectId)) {
            $errors = array();
            $errors["general"] = i18n("You do not have permission to access this project.");
            $this->view->setVariable("errors", $errors);
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

}

?>