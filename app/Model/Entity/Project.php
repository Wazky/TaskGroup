<?php
// file: /app/Model/Entity/Project.php

require_once(__DIR__.'/../../core/ValidationException.php');

/**
 * Project entity class
 * 
 * Represents a project in the system
 */
class Project {

    // ATTRIBUTES

    /**
     * The id of the project
     * 
     * @var int
     */
    private $id;

    /**
     * The name of the project
     * 
     * @var string
     */
    private $name;

    /**
     * The description of the project
     * 
     * @var string
     */
    private $description;

    /**
     * The owner username of the project
     * 
     * @var string
     */
    private $ownerUsername;

    /**
     * The creation date of the project
     * 
     * @var DateTime
     */
    private $createdAt;

    /**
     * The members of the project
     * 
     * @var array
     */
    private $members;

    /**
     * The member count of the project
     * 
     * @var int
     */
    private $memberCount;

    /**
     * The tasks of the project
     * 
     * @var array
     */
    private $tasks;

    /**
     * The task count of the project
     * 
     * @var int
     */
    private $taskCount;

    public function __construct($id = null,$name = null, $description = null, $ownerUsername = null, $createdAt = null) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->ownerUsername = $ownerUsername;
        $this->createdAt = $createdAt;
        $this->members = array();
        $this->tasks = array();
    }

    // GETTERS AND SETTERS

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getOwnerUsername() {
        return $this->ownerUsername;
    }

    public function setOwnerUsername($ownerUsername) {
        $this->ownerUsername = $ownerUsername;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getCreatedAtFormatted() {
        return $this->getCreatedAt()->format("d/m/y");
    }

    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    // MEMBER MANAGEMENT METHODS

    public function getMembers() {
        return $this->members;
    }

    public function setMembers($members) {
        $this->members = $members;
    }

    public function addMember($username) {
        if (!in_array($username, $this->members)) {
            $this->members[] = $username;
        }
    }

    public function removeMember($username) {
        $key = array_search($username, $this->members);

        if ($key !== false) {
            // Prevent removing the owner from members
            if ($username === $this->ownerUsername) {
                return false;
            }

            unset($this->members[$key]);
            $this->members = array_values($this->members);
            return true;
        }

        return false;
    }

    public function isMember($memberUsername) {
        return in_array($memberUsername, $this->members);
    }

    public function getMemberCount() {
        if ($this->memberCount > 0) {
            return $this->memberCount;
        }

        if (!empty($this->members)) {
            return count($this->members);
        }

        return 0;
    }

    public function setMemberCount($memberCount) {
        $this->memberCount = $memberCount;
    }

    // TASK MANAGEMENT METHODS

    public function getTasks() {
        return $this->tasks;
    }

    public function setTasks($tasks) {
        $this->tasks = $tasks;
    }

    public function addTask($task) {
        $this->tasks[] = $task;
    }

    public function removeTask($taskId) {
        foreach ($this->tasks as $key => $task) {
            if ($task->getId() == $taskId) {
                unset($this->tasks[$key]);
                $this->tasks = array_values($this->tasks);
                return true;
            }
        }
        return false;
    }

    public function getTaskCount() {
        if ($this->taskCount > 0) {
            return $this->taskCount;
        }

        if (!empty($this->tasks)) {
            return count($this->tasks);
        }

        return 0;
    }

    public function setTaskCount($taskCount) {
        $this->taskCount = $taskCount;
    }

    public function getTasksByStatus($status) {
        return array_filter($this->tasks, function($task) use ($status) {
            return $task->getStatus() === $status;
        });
    }

    public function getTasksByUser($username) {
        return array_filter($this->tasks, function($task) use ($username) {
            return $task->getAssignedUsername() === $username;
        });
    }

    public function getProgressPercentage() {
        $totalTasks = count($this->tasks);
        if ($totalTasks === 0) {
            return 0;
        }

        $completedTasks = count($this->getTasksByStatus('completed'));
        return ($completedTasks / $totalTasks) * 100;
    }

    public function checkIsValidForCreate() {
        $errors = array();

        if (empty($this->name) || strlen($this->name) < 3) {
            $errors["project_name"] = i18n("Project name must be at least 3 characters long.");
        }

        if (strlen(trim($this->ownerUsername)) == 0) {
            $errors["owner_username"] = i18n("Owner username cannot be empty.");
        }

        if (strlen($this->description) > 500) {
            $errors["project_description"] = i18n("Project description cannot exceed 500 characters.");
        }

        if (sizeof($errors) > 0) {
            throw new ValidationException($errors, i18n("Project data is not valid.")   );
        }

    }

    public function checkIsValidForUpdate() {
        $errors = array();

        if (empty($this->name) || strlen($this->name) < 3) {
            $errors["project_name"] = i18n("Project name must be at least 3 characters long.");
        }

        if (strlen($this->description) > 500) {
            $errors["project_description"] = i18n("Project description cannot exceed 500 characters.");
        }

        if (sizeof($errors) > 0) {
            throw new ValidationException($errors, i18n("Project data is not valid.")   );
        }

    }


}

?>

