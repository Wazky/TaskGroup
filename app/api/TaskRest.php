<?php 
// file: app/api/TaskRest.php

require_once(__DIR__."/../Model/Entity/Task.php");
require_once(__DIR__."/../Model/Mapper/TaskMapper.php");

require_once(__DIR__."/../Model/Entity/Project.php");
require_once(__DIR__."/../Model/Mapper/ProjectMapper.php");

require_once(__DIR__."/BaseRest.php");

class TaskRest extends BaseRest {

    private $taskMapper;
    private $projectMapper; 

    public function __construct() {
        parent::__construct();

        $this->taskMapper = new TaskMapper();
        $this->projectMapper = new ProjectMapper();
    }

    /**
     * GET '/api/projects/{projectId}/tasks'
     */
    public function index($projectIdentifier) {
        try {
            // Check auth
            $currentUser = parent::authenticateUser();

            // Validate project identifier
            if ($projectIdentifier === null || !is_numeric($projectIdentifier)) $this->badRequest('Invalid project identifier.');

            // Check that current user is member of the project
            if (!$this->projectMapper->isUserMember($currentUser->getUsername(), $projectIdentifier)) $this->forbidden('You are not member of this project.');

            // Find the Project object
            $requestedProject = $this->projectMapper->findById($projectIdentifier);

            // Check if project was found
            if ($requestedProject === null) $this->notFound('Project not found.');

            // Get tasks for the project
            $tasks = $this->taskMapper->findByProjectId($projectIdentifier);

            // Format response
            $response = $this->formatTaskListResponse($tasks);

            // Return response
            $this->ok($response);  

        } catch (Exception $e) {
            $this->serverError("Could not retrieve tasks", $e);
        }
    }

    /**
     * POST '/api/projects/{projectId}/tasks'
     */
    public function store($projectIdentifier, $taskData) {
        try {
            // Check auth
            $currentUser = parent::authenticateUser();

            // Validate project identifier
            if ($projectIdentifier === null || !is_numeric($projectIdentifier)) $this->badRequest('Invalid project identifier.');   

            // Check data presence
            $this->checkDataPresence($taskData);

            // Check that current user is member of the project
            if (!$this->projectMapper->isUserMember($currentUser->getUsername(), $projectIdentifier)) $this->forbidden('You are not member of this project.');

            // Find the Project object
            $requestedProject = $this->projectMapper->findById($projectIdentifier);
            
            // Check if project was found
            if ($requestedProject === null) $this->notFound('Project not found.');

            // Create new Task object
            $newTask = new Task();

            // Populate task object with param data
            $newTask->setProjectId($projectIdentifier);
            $newTask->setTitle($taskData->title ?? null);
            $newTask->setDescription($taskData->description ?? null);
            $newTask->setStatus($taskData->status ?? Task::STATUS_TODO);
            $newTask->setAssignedUsername($taskData->assignedUsername ?? null);

            // Validate task data
            $newTask->checkIsValid();

            // Save task in the DB
            $this->taskMapper->save($newTask);

            // Format response
            $response = $this->formatTaskResponse($newTask);

            // Return response
            $this->created($response);

        } catch(Exception $e) {
            $this->serverError("Could not create task", $e);
        }
    }

    /**
     * GET '/api/projects/{projectId}/tasks/{taskId}'
     */
    public function show($projectIdentifier, $taskIdentifier) {
        try {
            // Check auth
            $currentUser = parent::authenticateUser();

            // Validate project identifier
            if ($projectIdentifier === null || !is_numeric($projectIdentifier)) $this->badRequest('Invalid project identifier.');

            // Validate task identifier
            if ($taskIdentifier === null || !is_numeric($taskIdentifier)) $this->badRequest('Invalid task identifier.');

            // Check that current user is member of the project
            if (!$this->projectMapper->isUserMember($currentUser->getUsername(), $projectIdentifier)) $this->forbidden('You are not member of this project.');

            // Find the Task object
            $requestedTask = $this->taskMapper->findById($taskIdentifier);

            // Check if task was found
            if ($requestedTask === null || $requestedTask->getProjectId() != $projectIdentifier) $this->notFound('Task not found.');

            // Format response
            $response = $this->formatTaskResponse($requestedTask);

            // Return response
            $this->ok($response);

        } catch (Exception $e) {
            $this->serverError("Could not retrieve task", $e);
        }
    }

    /**
     * PUT '/api/projects/{projectId}/tasks/{taskId}'
     */
    public function update($projectIdentifier, $taskIdentifier, $taskData) {
        try {
            // Check auth
            $currentUser = parent::authenticateUser();

            // Validate project identifier
            if ($projectIdentifier === null || !is_numeric($projectIdentifier)) $this->badRequest('Invalid project identifier.');

            // Validate task identifier
            if ($taskIdentifier === null || !is_numeric($taskIdentifier)) $this->badRequest('Invalid task identifier.');

            // Check data presence
            $this->checkDataPresence($taskData);

            // Check that current user is member of the project
            if (!$this->projectMapper->isUserMember($currentUser->getUsername(), $projectIdentifier)) $this->forbidden('You are not member of this project.');

            // Find the Task object
            $requestedTask = $this->taskMapper->findById($taskIdentifier);

            // Check if task was found
            if ($requestedTask === null || $requestedTask->getProjectId() != $projectIdentifier) $this->notFound('Task not found.');

            // Update Task object with param data
            $requestedTask->setTitle($taskData->title ?? $requestedTask->getTitle());
            $requestedTask->setDescription($taskData->description ?? $requestedTask->getDescription());
            $requestedTask->setStatus($taskData->status ?? $requestedTask->getStatus());
            $requestedTask->setAssignedUsername($taskData->assignedUsername ?? $requestedTask->getAssignedUsername());

            // Validate task data
            $requestedTask->checkIsValid();

            // Update task in the DB
            $this->taskMapper->update($requestedTask);

            // Format response
            $response = $this->formatTaskResponse($requestedTask);

            // Return response
            $this->ok($response);

        } catch (Exception $e) {
            $this->serverError("Could not update task", $e);
        }
    }

    /**
     * DELETE '/api/projects/{projectId}/tasks/{taskId}'
     */
    public function delete($projectIdentifier, $taskIdentifier) {
        try {
            // Check auth
            $currentUser = parent::authenticateUser();

            // Validate project identifier
            if ($projectIdentifier === null || !is_numeric($projectIdentifier)) $this->badRequest('Invalid project identifier.');

            // Validate task identifier
            if ($taskIdentifier === null || !is_numeric($taskIdentifier)) $this->badRequest('Invalid task identifier.');

            // Find the Task object
            $requestedTask = $this->taskMapper->findById($taskIdentifier);

            // Check if task was found
            if ($requestedTask === null || $requestedTask->getProjectId() != $projectIdentifier) $this->notFound('Task not found.');

            // Check that current user is the owner of the project
            $isOwner = $this->projectMapper->isUserOwner($currentUser->getUsername(), $projectIdentifier);
            $isAssigned = $requestedTask->getAssignedUsername() === $currentUser->getUsername();
            if (!$isOwner && !$isAssigned ) $this->forbidden('You are not the owner of this project or assigned to this task.');
            
            // Delete task from the DB
            $this->taskMapper->delete($requestedTask->getId());
            
            // Return response
            $this->ok(null, "Task deleted successfully.");

        } catch (Exception $e) {
            $this->serverError("Could not delete task", $e);
        }
    }

    private function formatTaskResponse(Task $task) {
        return [
            "id" => $task->getId(),
            "title" => $task->getTitle(),
            "description" => $task->getDescription(),
            "status" => $task->getStatus(),
            "assignedUsername" => $task->getAssignedUsername(),
            "projectId" => $task->getProjectId(),
            "createdAt" => $task->getCreatedAt(),
            "updatedAt" => $task->getUpdatedAt(),
        ];
    }

    private function formatTaskListResponse($tasks) {
        $response = [];

        foreach ($tasks as $task) {
            $response[] = $this->formatTaskResponse($task);
        }

        return $response;
    }

    /**
     * Check data presence
     */
    private function checkDataPresence($data) {
        if ($data === null || !is_object($data)) {
            $this->badRequest('No data received or invalid format', 400);
        }
    }

}

$taskRest = new TaskRest();
URIDispatcher::getInstance()->map("GET", "/projects/$1/tasks", array($taskRest, "index"))
                            ->map("POST", "/projects/$1/tasks", array($taskRest, "store"))
                            ->map("GET", "/projects/$1/tasks/$2", array($taskRest, "show"))
                            ->map("PUT", "/projects/$1/tasks/$2", array($taskRest, "update"))
                            ->map("DELETE", "/projects/$1/tasks/$2", array($taskRest, "delete"))
?>