<?php 
//file: /app/rest/ProjectsRest.php

//Required files
require_once(__DIR__."/../Model/Entity/Project.php");
require_once(__DIR__."/../Model/Entity/User.php");
require_once(__DIR__."/../Model/Entity/Task.php");
require_once(__DIR__."/../Model/Mapper/ProjectMapper.php");
require_once(__DIR__."/../Model/Mapper/TaskMapper.php");
require_once(__DIR__."/BaseRest.php");

class ProjectRest extends BaseRest {

    private $projectMapper;
    private $taskMapper;

    public function __construct() {
        parent::__construct();

        $this->projectMapper = new ProjectMapper();
        $this->taskMapper = new TaskMapper();
    }

    /**
     * GET '/api/projects' 
     * 
     * List all projects for current user
     */
    public function index() {
        try {
            // Check auth
            $currentUser = parent::authenticateUser();

            $ownedProjects = $this->projectMapper->findByOwnerWithCounts($currentUser->getUsername());

            $memberProjects = $this->projectMapper->findByMemberOnlyWithCounts($currentUser->getUsername());
            $response = [
                "ownedProjects" => $this->formatProjectListResponse($ownedProjects),
                "memberProjects" => $this->formatProjectListResponse($memberProjects)
            ];

            $this->ok($response, 'Projects retrieved successfully');

        } catch( Exception $e) {
            $this->serverError("Could not retrive projects ", $e);
        }
    }

    /**
     * POST '/api/projects'
     */
    public function store($projectData) {
        try {
            // Check auth
            $currentUser = parent::authenticateUser();

            // Check data presence            
            $this->checkDataPresence($projectData);

            // Create new project object
            $project = new Project();

            // Populate project object with data
            $project->setName($projectData->name ?? null);
            $project->setDescription($projectData->description ?? null);
            $project->setOwnerUsername($currentUser->getUsername());

            // Validate project data
            $project->checkIsValidForCreate();

            // Set members if any
            $members = $projectData->members ?? [];

            $invalidMembers = [];
            // Validate members
            foreach($members as $memberUsername) {
                // If exists username add it to project, else add to invalid list
                if (($user = $this->userMapper->getUser($memberUsername)) !== null) {
                    $project->addMember($user->getUsername());
                
                } else {
                    $invalidMembers[] = $memberUsername;
                }
            }

            // If any invalid members, return bad request listing them
            if (count($invalidMembers) > 0) $this->badRequest("The following members are invalid: ".implode(", ", $invalidMembers));

            // Save project in DB   
            $project = $this->projectMapper->save($project);    // Owner automatically added as member in mapper
            $project->addMember($currentUser->getUsername());   // Add owner as member on response

            // Format response data
            $response = $this->formatProjectResponse($project);

            // Return response
            $this->created($response, "Project created successfully.");            

        } catch (ValidationException $e) {
            $this->badRequest('Invalid project data', 400);

        } catch (Exception $e) {
            $this->serverError("Could not create project ", $e);
        }
    }

    /**
     * GET '/api/projects/{id}'
     */
    public function show($projectIdentifier) {
        try {
            // Check auth
            $currentUser = parent::authenticateUser();

            // Validate project identifier
            if ($projectIdentifier === null || !is_numeric($projectIdentifier)) $this->badRequest('Invalid project identifier.', 400);

            // Check if current user is member of the project (Independent of project existence)
            if (!$this->projectMapper->isUserMember($currentUser->getUsername(), $projectIdentifier)) $this->forbidden('You are not member of this project');

            // Find the Project object
            $requestedProject = $this->projectMapper->findById($projectIdentifier);

            // Check if project was found
            if ($requestedProject === null) $this->notFound('Project not found');

            // Get project members and set them to the project object
            $members = $this->projectMapper->getProjectMembers($projectIdentifier);
            $requestedProject->setMembers($members);

            // Get project tasks and set them to the project object
            $tasks = $this->taskMapper->findByProjectId($projectIdentifier);
            $requestedProject->setTasks($tasks);

            // Format response data
            $response = $this->formatProjectResponse($requestedProject);

            // Return response
            $this->ok($response, 'Project retrieved successfully');

        } catch (Exception $e) {
            $this->serverError("Could not retrieve project ", $e);
        }
    }

    /**
     * PUT '/api/projects/{id}' 
     * 
     * (Response does not include members or tasks)
     * Update project data (name, description)
     */
    public function update($projectIdentifier, $projectData) {
        try {
            // Check auth
            $currentUser = parent::authenticateUser();

            // Validate project identifier
            if ($projectIdentifier === null || !is_numeric($projectIdentifier)) $this->badRequest('Invalid project identifier.', 400);

            // Check data presence
            $this->checkDataPresence($projectData);

            // Find the Project 
            $requestedProject = $this->projectMapper->findById($projectIdentifier);
            
            // Check if project was found
            if ($requestedProject === null) $this->notFound('Project not found');

            // Check if current user is member of the project
            if (!$this->projectMapper->isUserMember($currentUser->getUsername(), $projectIdentifier)) $this->forbidden('You are not member of this project');

            // Create new project object with the data to update
            $requestedProject->setName($projectData->name ?? $requestedProject->getName());
            $requestedProject->setDescription($projectData->description ?? $requestedProject->getDescription());

            // Validate project data
            $requestedProject->checkIsValidForUpdate();
            // Update project in DB
            $this->projectMapper->save($requestedProject);
            
            // Format response data
            $response = $this->formatProjectResponse($requestedProject);
            // Return response
            $this->ok($response, "Project updated successfully.");

        } catch (Exception $e) {
            $this->serverError("Could not update project ", $e);
        }
    }

    /**
     * DELETE '/api/projects/{id}'
     */
    public function delete($projectIdentifier) {
        try {
            // Cehck auth
            $currentUser = parent::authenticateUser();

            // Validate project identifier
            if ($projectIdentifier === null || !is_numeric($projectIdentifier)) $this->badRequest('Invalid project identifier.', 400);

            // Find the Project 
            $requestedProject = $this->projectMapper->findById($projectIdentifier);

            // Check if project was found
            if ($requestedProject === null) $this->notFound('Project not found');

            // Check if current user is owner of the project
            if ($requestedProject->getOwnerUsername() !== $currentUser->getUsername()) $this->forbidden('You are not the owner of this project');

            // Delete project from DB
            $this->projectMapper->delete($projectIdentifier);

            // Return response
            $this->ok(null, "Project deleted successfully.");

        } catch (Exception $e) {
            $this->serverError("Could not delete project ", $e);
        }
    }

    /**
     * GET '/api/projects/{id}/members'
     */
    public function getMembers() {}

    /**
     * POST '/api/projects/{id}/members'
     */
    public function addMembers() {}

    /**
     * DELETE '/api/projects/{id}/members'
     */
    public function removeMembers() {}

    /**
     * GET '/api/projects/{id}/tasks' (Move to task rest ?)
     */
    public function getTasks() {}

    /**
     * ==============================
     * RESPONSE FORMATTING HELPERS
     * ==============================
     */

    /**
     * Format project list response
     */
    private function formatProjectListResponse($projectList) {
        $response = [];

        foreach($projectList as $project) {
            $response[] = $this->formatProjectResponse($project);
        }

        return $response;
    }

    /**
     * Format project response
     */
    private function formatProjectResponse($project) {
        return [
            "id" => $project->getId(),
            "name" => $project->getName(),
            "description" => $project->getDescription(),
            "ownedUsername" => $project->getOwnerUsername(),
            "createdAt" => $project->getCreatedAt(),
            "members" => $project->getMembers(),
            "memberCount" => $project->getMemberCount(),
            "tasks" => $this->formatTaskListResponse($project->getTasks()),
            "taskCount" => $project->getTaskCount(),

        ];
    }

    /**
     * Format task list response
     */
    private function formatTaskListResponse($taskList) {
        $response = [];

        foreach($taskList as $task) {
            $response[] = $this->formatTaskResponse($task);
        }

        return $response;
    }

    /**
     * Format task response
     */
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
    
    /**
     * Check data presence
     */
    private function checkDataPresence($data) {
        if ($data === null || !is_object($data)) {
            $this->badRequest('No data received or invalid format', 400);
        }
    }

}

$projectRest = new ProjectRest();
URIDispatcher::getInstance()->map("GET", "/projects", array($projectRest, "index"))                            
                            ->map("POST", "/projects", array($projectRest, "store"))
                            ->map("GET", "/projects/$1", array($projectRest, "show"))
                            ->map("PUT", "/projects/$1", array($projectRest, "update"))
                            ->map("DELETE", "/projects/$1", array($projectRest, "delete"))

                            ->map("", "", array($projectRest, "getMembers"))
                            ->map("", "", array($projectRest, "addMembers"))
                            ->map("", "", array($projectRest, "removeMembers"))
                            ->map("", "", array($projectRest, "getTasks")) // Move to task rest ?
?>