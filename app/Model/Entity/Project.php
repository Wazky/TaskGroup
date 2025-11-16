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
     * The tasks of the project
     * 
     * @var array
     */
    private $tasks;

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
        return count($this->members);
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

    public function getTasksByStatus($status) {
        return array_filter($this->tasks, function($task) use ($status) {
            return $task->getStatus() === $status;
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

}

?>

