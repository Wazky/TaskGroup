<?php
// file: /app/Model/Mapper/TaskMapper.php

require_once(__DIR__ . '/../../core/PDOConnection.php');

/**
 * Class TaskMapper
 * 
 * Maps Task entities to the database and vice versa
 */
class TaskMapper {

    /**
     * The database connection
     * 
     * @var PDO
     */
    private $db;

    /**
     * Constructor
     */
    public function __construct(){
        $this->db = PDOConnection::getInstance();
    }

    /**
     * Saves a Task entity to the database
     * (inserts if new, updates if existing)
     * 
     * @param Task $task The Task entity to save
     */
    public function save($task) {
        ($task->getId() === null) ? $this->insert($task) : $this->update($task);
    }

    /**
     * Inserts a new Task entity into the database
     * 
     * @param Task $task The Task entity to insert
     * @return Task The inserted Task entity with updated ID
     */
    public function insert($task) {
        $stmt = $this->db->prepare("INSERT INTO tasks (task_title, task_description, task_status, assigned_user, project_id)
            VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(array($task->getTitle(), $task->getDescription(), $task->getStatus(),
            $task->getAssignedUsername(), $task->getProjectId()));

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $task->setId($result['task_id']);

        return $task;
    }

    /**
     * Updates an existing Task entity in the database
     * 
     * @param Task $task The Task entity to update
     * @return Task The updated Task entity
     */
    public function update($task) {
        $stmt = $this->db->prepare("UPDATE tasks SET task_title = ?, task_description = ?,
            task_status = ?, assigned_user = ? WHERE task_id = ?");
        $stmt->execute(array($task->getTitle(), $task->getDescription(), 
            $task->getStatus(), $task->getAssignedUsername(), $task->getId()));

        return $task;
    }

    /**
     * Finds a Task entity by its ID
     * 
     * @param int $taskId The ID of the task to find
     * @return Task|null The Task entity if found, null otherwise
     */
    public function findById($taskId) {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE task_id");
        $stmt->execute(array($taskId));

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return $this->rowToEntity($row);
        }

        return null;
    }

    /**
     * Finds all Task entities for a given project ID
     * 
     * @param int $projectId The ID of the project
     * @return array An array of Task entities
     */
    public function findByProjectId($projectId) {
        $stmt = $this->db->prepare("SELECT * FROM tasks 
            WHERE project_id = ? ORDER BY task_created_at DESC");
        $stmt->execute(array($projectId));

        $tasks = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tasks[] = $this->rowToEntity($row);
        }

        return $tasks;
    }

    /**
     * Finds all Task entities assigned to a given user
     * 
     * @param string $username The username of the assigned user
     * @return array An array of Task entities
     */
    public function findByAssignedUser($username) {
        $stmt = $this->db->prepare("SELET * FROM tasks 
            WHERE assigned_user = ? ORDER BY task_created_at DESC");
        $stmt->execute(array($username));

        $tasks = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tasks[] = $this->rowToEntity($row);
        }

        return $tasks;
    }

    /**
     * Finds all Task entities for a given project ID and status
     * 
     * @param int $projectId The ID of the project
     * @param string $status The status of the tasks to find
     * @return array An array of Task entities
     */
    public function findByProjectAndStatus($projectId, $status) {
        $stmt = $this->db->prepare("SELECT * FROM tasks
            WHERE project_id = ? AND task_status = ? ORDER BY task_created_at DESC");
        $stmt->execute(array($projectId, $status));

        $tasks = array();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tasks[] = $this->rowToEntity($row);
        }

        return $tasks;
    }

    public function delete($taskId) {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE task_id = ?");
        $stmt->execute(array($taskId));

        return ($stmt->rowCount() > 0);
    }

    /**
     * Converts a database row to a Task entity
     * 
     * @param array $row The database row
     * @return Task The Task entity
     */
    private function rowToEntity($row) {
        $task = new Task();

        $task->setId($row['task_id']);
        $task->setTitle($row['task_title']);
        $task->setDescription($row['task_description']);
        $task->setStatus($row['task_status']);
        $task->setAssignedUsername($row['assigned_user']);
        $task->setProjectId($row['project_id']);

        $createdAt = DateTime::createFromFormat('Y-m-d H:i:s', $row['task_created_at']);
        $task->setCreatedAt($createdAt);

        $updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $row['task_updated_at']);
        $task->setUpdatedAt($updatedAt);

        return $task;
    }

    public function getProjectStatistics($projectId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total_tasks,
            SUM(CASE WHEN task_status = 'completed THEN 1 ELSE 0 END) as completed_tasks,
            SUM(CASE WHEN task_status = 'to do' THEN 1 ELSE 0 END) as todo_tasks
            FROM tasks WHERE project_id = ?");
        $stmt->execute(array($projectId));

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}

?>