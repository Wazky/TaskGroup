<?php
// file: /app/Controller/ProjectsController.php

require_once(__DIR__.'/../core/ViewManager.php');
require_once(__DIR__.'/../core/I18n.php');

require_once(__DIR__.'/../Model/Entity/User.php');
require_once(__DIR__.'/../Model/Mapper/UserMapper.php');

require_once(__DIR__.'/BaseController.php');

class ProjectsController extends BaseController {

    // PROJECTS CONSTANTS

    private const PROJECTS_LAYOUT = "dashboard"; 
    private const PROJECTS_CONTROLLER_NAME = "projects";
    private const PROJECTS_INDEX_ACTION = "index";
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

    public function __construct() {
        
        parent::__construct();
        $this->projectMapper = new ProjectMapper();

        // Set the layout for projects pages
        $this->view->setLayout(self::PROJECTS_LAYOUT);
    }

    public function index() {
        // Check authentication
        $this->requireAuthentication();
        
        // User is logged in - show projects
        $this->projectMapper->findByOwner($this->currentUser->getUsername());
        $this->projectMapper->findByMember($this->currentUser->getUsername());

        $this->view->render(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_INDEX_ACTION);
    }


    private function manageUnauthorizedAccess() {
        $this->view->setFlash(i18n("You must be logged in to access this page."));
        $this->view->redirect(self::AUTH_CONTROLLER_NAME, self::AUTH_LOGIN_ACTION);
    }

}

?>