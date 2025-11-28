<?php
// file: app/Model/Mapper/TaskMapper.php

require_once(__DIR__.'/../core/ViewManager.php');
require_once(__DIR__.'/../core/I18n.php');

require_once(__DIR__.'/../Model/Entity/User.php');
require_once(__DIR__.'/../Model/Mapper/UserMapper.php');

require_once(__DIR__.'/../Model/Entity/Project.php');
require_once(__DIR__.'/../Model/Mapper/ProjectMapper.php');

require_once(__DIR__.'/../Model/Entity/Task.php');
require_once(__DIR__.'/../Model/Mapper/TaskMapper.php');

require_once(__DIR__.'/BaseController.php');

class TaskController extends BaseController {

    // TASK CONSTANTS

    private const TASKS_LAYOUT = "dashboard";
    private const TASKS_CONTNROLLER_NAME = "tasks";
    private const TASKS_DETAIL_VIEW = "detail";
    private const TASKS_CREATE_ACTION = "create";
    private const TASKS_EDIT_ACTION = "edit";
    private const TASKS_DELETE_ACTION = "delete";

    // AUTHORIZATION CONSTANTS

    private const AUTH_CONTROLLER_NAME = "auth";
    private const AUTH_LOGIN_ACTION = "login";
    
    private $taskMapper;

    private $userMapper;

    public function __construct(){
        
        parent::__construct();

        $this->taskMapper = new TaskMapper();
        $this->userMapper = new UserMapper();

        // Set the layout for tasks pages
        $this->view->setLayout(self::TASKS_LAYOUT);
    }

    public function create() {
        // Check authentication
        $this->requireAuthentication();

    }

    public function detail() {
        // Check authentication
        $this->requireAuthentication();

    }

    public function edit() {
        // Check authentication
        $this->requireAuthentication();

    }

    public function delete() {
        // Check authentication
        $this->requireAuthentication();
    }



}

?>