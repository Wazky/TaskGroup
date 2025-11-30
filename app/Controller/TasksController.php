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

class TasksController extends BaseController {

    // TASK CONSTANTS

    private const TASKS_LAYOUT = "dashboard";
    private const TASKS_CONTROLLER_NAME = "tasks";
    private const TASKS_DETAIL_ACTION = "detail";
    private const TASKS_CREATE_ACTION = "create";
    private const TASKS_EDIT_ACTION = "edit";
    private const TASKS_DELETE_ACTION = "delete";

    // PROJECT CONSTANTS

    private const PROJECTS_CONTROLLER_NAME = "projects";
    private const PROJECTS_DETAIL_ACTION = "detail";   
    private const PROJECTS_LIST_ACTION = "list";

    // AUTHORIZATION CONSTANTS

    private const AUTH_CONTROLLER_NAME = "auth";
    private const AUTH_LOGIN_ACTION = "login";
    
    private $taskMapper;
    private $projectMapper;
    private $userMapper;
    
    public function __construct(){
        
        parent::__construct();

        $this->taskMapper = new TaskMapper();
        $this->projectMapper = new ProjectMapper();
        $this->userMapper = new UserMapper();

        // Set the layout for tasks pages
        $this->view->setLayout(self::TASKS_LAYOUT);
    }

    public function create() {
        // Check authentication
        $this->requireAuthentication();

        // Check if projectId is provided
        if (!isset($_REQUEST["project_id"])) {
            $this->prepareAlert(i18n("Project ID is required to create a task."), "alert-info", "bi-info-circle");
            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        $projectId = $_REQUEST["project_id"];
        // Verify that the project exists
        if ($this->projectMapper->findById($projectId) === null) {
            $this->prepareAlert(i18n("Project not found."), "alert-info", "bi-info-circle");
            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Authorization: only project members can create tasks
        if (! $this->projectMapper->isUserMember($this->currentUser->getUsername(), $projectId)) {
            $this->prepareAlert(i18n("You are not authorized to create tasks in this project."), "alert-danger", "bi-exclamation-triangle-fill");
            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        $task = new Task();
        $task->setProjectId($projectId);

        // Get project members for assignment
        $members = $this->projectMapper->getProjectMembers($projectId);

        // Handle form submission
        if (isset($_POST["submit"])) {            
            // Populate task entity with form data
            $task->setTitle($_POST["task_title"]);
            $task->setDescription($_POST["task_description"]);
            $task->setStatus($_POST["task_status"]);
            $task->setAssignedUsername($_POST["assigned_username"]);

            try {
                // Validate task data
                $task->checkIsValid();
                
                // Assigned user is not a member of the project
                if (!$this->projectMapper->isUserMember($task->getAssignedUsername(), $task->getProjectId())) {
                    $errors = array();
                    $errors["assigned_username"] = i18n("Assigned user must be a member of the project.");
                    throw new ValidationException($errors, i18n("Task data is not valid."));
                }  
                
                // Save task to database
                $this->taskMapper->save($task);

                // POST_REDIRECT_GET
                // Set success flash message
                $this->prepareAlert(i18n("Task created successfully."), "alert-success", "bi-check-circle");                
                // Redirect to project detail page
                $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_DETAIL_ACTION, "id=".$task->getProjectId());
            
            } catch (ValidationException $ex) {
                $this->view->setVariable("errors", $ex->getErrors());
                $this->prepareAlert(i18n("Invalid data provided."), "alert-danger", "bi-exclamation-triangle-fill");                
            }
        }

        // Set task and members variable for the view
        $this->view->setVariable("task", $task);
        $this->view->setVariable("members", $members);
        // Render the create task view
        $this->view->render(self::TASKS_CONTROLLER_NAME, self::TASKS_CREATE_ACTION);

    }

    public function detail() {
        // Check authentication
        $this->requireAuthentication();

        // Check if task ID is provided
        if (!isset($_GET["id"])) {
            $this->prepareAlert(i18n("Task ID is required."), "alert-info", "bi-info-circle");
            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Obtain task_id from query parameters
        $taskId = $_GET["id"];

        // Obtain task from database
        $task = $this->taskMapper->findById($taskId);

        // Check if task exists
        if ($task === null) {
            $this->prepareAlert(i18n("Task not found."), "alert-info", "bi-info-circle");
            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Authorization: only project members can view task details
        if (!$this->projectMapper->isUserMember($this->currentUser->getUsername(), $task->getProjectId())) {
            $this->prepareAlert(i18n("You are not authorized to view this task."), "alert-danger", "bi-exclamation-triangle-fill");
            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        if ($this->projectMapper->isUserOwner($this->currentUser->getUsername(), $task->getProjectId())) {
            $this->view->setVariable("isProjectOwner", true);
        }

        // Set task variable for the view
        $this->view->setVariable("task", $task);
        // Render the task detail view
        $this->view->render(self::TASKS_CONTROLLER_NAME, self::TASKS_DETAIL_ACTION);
    }

    public function edit() {
        // Check authentication
        $this->requireAuthentication();

        // Check if task ID is provided
        if (!isset($_REQUEST["id"])) {
            $this->prepareAlert(i18n("Task ID is required."), "alert-info", "bi-info-circle");
            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Obtain task from database
        $taskId = $_REQUEST["id"];
        $task = $this->taskMapper->findById($taskId);

        // Check if task exists
        if ($task === null) {
            $this->prepareAlert(i18n("Task not found."), "alert-info", "bi-info-circle");
            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Authorization: only assigned user or project owner can edit task
        if ($task->getAssignedUsername() !== $this->currentUser->getUsername()
            && !$this->projectMapper->isUserOwner($this->currentUser->getUsername(), $task->getProjectId())) {
            $this->prepareAlert(i18n("You are not authorized to edit this task."), "alert-danger", "bi-exclamation-triangle-fill");
            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Get project members for assignment
        $members = $this->projectMapper->getProjectMembers($task->getProjectId());

        if (isset($_POST["submit"])) {

            // Populate task entity with form data
            $task->setTitle($_POST["task_title"]);
            $task->setDescription($_POST["task_description"]);
            $task->setStatus($_POST["task_status"]);
            $task->setAssignedUsername($_POST["assigned_username"]);

            try {
                // Validate task data
                $task->checkIsValid();

                // Check if assigned user is not a member of the project
                if ($this->projectMapper->isUserMember($task->getAssignedUsername(), $task->getProjectId()) === false) {
                    $errors = array();
                    $errors["assigned_username"] = i18n("Assigned user must be a member of the project.");
                    throw new ValidationException($errors, i18n("Task data is not valid."));
                }

                // Save task to database
                $this->taskMapper->save($task);

                // POST_REDIRECT_GET
                // Set success flash message
                $this->prepareAlert(i18n("Task updated successfully."), "alert-success", "bi-check-circle-fill");
                // Redirect to project detail page
                $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_DETAIL_ACTION, "id=".$task->getProjectId());
            
            } catch (ValidationException $ex) {        
                // Set validation errors for the view
                $this->view->setVariable("errors", $ex->getErrors());
                $this->prepareAlert(i18n("Invalid data provided."), "alert-danger", "bi-exclamation-triangle-fill");
            }

        }

        // Set task and project members variable for the view
        $this->view->setVariable("task", $task);
        $this->view->setVariable("members", $members);
        // Render the edit task view
        $this->view->render(self::TASKS_CONTROLLER_NAME, self::TASKS_EDIT_ACTION);


    }

    public function delete() {
        // Check authentication
        $this->requireAuthentication();

        // Check if task ID is provided
        if (!isset($_POST["id"])) {
            $this->prepareAlert(i18n("Task ID is required."), "alert-info", "bi-info-circle");
            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Obtain task from database
        $taskId = $_POST["id"];
        $task = $this->taskMapper->findById($taskId);

        // Check if task exists
        if ($task === null) {
            $this->prepareAlert(i18n("Task not found."), "alert-info", "bi-info-circle");
            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Authorization: only assigned user or project owner can delete task
        if ($task->getAssignedUsername() !== $this->currentUser->getUsername()
            && !$this->projectMapper->isUserOwner($this->currentUser->getUsername(), $task->getProjectId())) {
            $this->prepareAlert(i18n("You are not authorized to delete this task."), "alert-danger", "bi-exclamation-triangle-fill");
            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Delete task from database
        $this->taskMapper->delete($taskId);

        // POST_REDIRECT_GET
        // Set success flash message
        $this->prepareAlert(i18n("Task deleted successfully."), "alert-success", "bi-check-circle-fill");
        // Redirect to project detail page
        $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_DETAIL_ACTION, "id=".$task->getProjectId());

    }

    public function markAsCompleted() {
        // Check authentication
        $this->requireAuthentication();
        
        // Check if task ID is provided
        if (!isset($_POST["id"])) {
            $this->prepareAlert(i18n("Task ID is required."), "alert-info", "bi-info-circle");
            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        // Obtain task from database
        $taskId = $_POST["id"];
        $task = $this->taskMapper->findById($taskId);

        // Check if task exists
        if ($task === null) {
            $this->prepareAlert(i18n("Task not found."), "alert-info", "bi-info-circle");
            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }
        
        // Authorization: only assigned user or project owner can update status
        if ($task->getAssignedUsername() !== $this->currentUser->getUsername()
            && !$this->projectMapper->isUserOwner($this->currentUser->getUsername(), $task->getProjectId())) {
            $this->prepareAlert(i18n("You are not authorized to update the status of this task."), "alert-danger", "bi-exclamation-triangle-fill");
            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        }

        if ($task->getStatus() === Task::STATUS_DONE) {
            // Task is already completed
            $this->prepareAlert(i18n("Task is already marked as completed."), "alert-warning", "bi-exclamation-triangle-fill");
            $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_LIST_ACTION);
        } else {
            // Update task status to completed
            $task->setStatus(Task::STATUS_DONE);
            $this->taskMapper->save($task);

            $this->prepareAlert(i18n("Task marked as completed successfully."), "alert-success", "bi-check-circle-fill");
        }

        // Redirect to task detail page
        $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_DETAIL_ACTION, "id=".$task->getProjectId());
    }

    private function prepareAlert($message, $type = "alert-info", $icon = "bi-info-circle") {
        $this->view->setFlash(i18n($message));
        $this->view->setVariable("flash-type", $type, true);
        $this->view->setVariable("flash-icon", $icon, true);
    }

}

?>