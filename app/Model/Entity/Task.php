<?php
// file: /app/Model/Entity/Task.php

require_once(__DIR__.'/../../core/ValidationException.php');

/**
 * Task entity class
 * 
 * Represents a task in the system
 */
class Task {

    // ATTRIBUTES

    /**
     * The id of the task
     * 
     * @var int
     */
    private $id;

    private $title;

    private $description;

    private $status;

    private $assignedUsername;

    private $projectId;

    private $createdAt;

    private $updatedAt;

    // Status constants
    const STATUS_TODO = 'to do';    
    const STATUS_DONE = 'completed';

    public function __construct($id = null, $title = null, $description = null, $status = null,
            $assignedUsername = null, $projectId = null, $createdAt = null, $updatedAt = null) {

        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->assignedUsername = $assignedUsername;
        $this->projectId = $projectId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    // GETTERS AND SETTERS

    public function getId() { return $this->id; }

    public function setId($id) { $this->id = $id; }

    public function getTitle() { return $this->title; }

    public function setTitle($title) { $this->title = $title; }

    public function getDescription() { return $this->description; }
    
    public function setDescription($description) { $this->description = $description; }

    public function getStatus() { return $this->status; }

    public function setStatus($status) { 
        if ($status !== self::STATUS_TODO && $status !== self::STATUS_DONE) {
            $errors = array();
            throw new ValidationException($errors, "Invalid status value");
        }
        $this->status = $status;
    }

    public function getAssignedUsername() { return $this->assignedUsername; }

    public function setAssignedUsername($assignedUsername) { $this->assignedUsername = $assignedUsername; }    

    public function getProjectId() { return $this->projectId; }

    public function setProjectId($projectId) { $this->projectId = $projectId; }

    public function getCreatedAt() { return $this->createdAt; }

    public function getCreatedAtFormatted() {return $this->getCreatedAt()->format("d/m/y"); }

    public function setCreatedAt($createdAt) { $this->createdAt = $createdAt; }

    public function getUpdatedAt() { return $this->updatedAt; }

    public function getUpdatedAtFormatted() {return $this->getUpdatedAt()->format("d/m/y"); }

    public function setUpdatedAt($updatedAt) { $this->updatedAt = $updatedAt; }

    // BUSINESS LOGIC METHODS

    public function isTodo() {
        return $this->status === self::STATUS_TODO;
    }

    public function isCompleted() {
        return $this->status === self::STATUS_DONE;
    }

    public function markAsTodo() {
        $this->status = self::STATUS_TODO;
    }
    
    public function markAsCompleted() {
        $this->status = self::STATUS_DONE;
    }

    public function isAssigned() {
        return !is_null($this->assignedUsername);
    }



}

?>