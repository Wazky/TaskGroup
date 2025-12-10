<?php
// file: /app/Model/Mapper/ProjectMapper.php

require_once(__DIR__ . '/../../core/PDOConnection.php');

/**
 * Class ProjectMapper
 * 
 * Maps Project entities to the database and vice versa
 */
class ProjectMapper {

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
     * Saves a Project entity to the database
     * 
     * @param Project $project The Project entity to save
     * @return Project|bool The saved Project entity or true/false for update
     */
    public function save($project) {
        if ($project->getId() === null) {
            return $this->insert($project);
        } else {
            return $this->update($project);
        }
    }

    /**
     * Inserts a new Project entity into the database
     * 
     * @param Project $project The project entity to insert
     * @return Project The inserted project entity with updated ID
     */
    private function insert($project) {
        $stmt = $this->db->prepare("INSERT INTO projects (project_name, project_description, project_owner) values (?, ?, ?)");
        $stmt->execute(array($project->getName(), $project->getDescription(), $project->getOwnerUsername()));

        // Get the inserted project ID
        $project->setId($this->db->lastInsertId());

        // Add the owner as a member
        $this->addMember($project->getOwnerUsername(), $project->getId());

        // Add initial members if any
        foreach ($project->getMembers() as $memberUsername) {
            $this->addMember($memberUsername, $project->getId());
        }

        return $project;
    }

    /**
     * Updates an existing Project entity in the database
     * 
     * @param Project $project The project entity to update
     * @return bool True if the update was successful, false otherwise
     */
    private function update($project) {
        // Update project details
        $stmt = $this->db->prepare("UPDATE projects SET project_name = ?, project_description = ? WHERE project_id = ?");
        $result = $stmt->execute(array($project->getName(), $project->getDescription(), $project->getId()));

        return $result;
    }

    private function updateProjectAndMembers($project) {
        // Update project details
        $stmt = $this->db->prepare("UPDATE projects SET project_name = ?, project_description = ? WHERE project_id = ?");
        $result = $stmt->execute(array($project->getName(), $project->getDescription(), $project->getId()));

        // Check if update was successful
        if ($result) {
            // Update members
            $this->updateMembers($project);
        }

        return $result;
    }


    /**
     * Finds a Project entity by its ID
     * 
     * @param int $projectId The ID of the project
     * @return Project|null The Project entity if found, null otherwise
     */
    public function findById($projectId) {
        $stmt = $this->db->prepare("SELECT * FROM projects WHERE project_id = ?");
        $stmt->execute(array($projectId));

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // If no project is found, return null
        if (!$row) {
            return null;
        }

        // Convert the database row to a Project entity and return it
        return $this->rowToEntity($row);
    }   

    /**
     * Finds Project entities by their owner's username
     * 
     * @param string $username The owner's username
     * @return array An array of Project entities
     */
    public function findByOwner($username) {
        $stmt = $this->db->prepare("SELECT * FROM projects WHERE project_owner = ? ORDER BY project_created_at DESC");
        $stmt->execute(array($username));

        $projects = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $projects[] = $this->rowToEntity($row);
        }

        return $projects;
    }

    public function findByOwnerWithCounts($username) {
        $stmt = $this->db->prepare("SELECT p.*,
            (SELECT COUNT(*) FROM project_members pm WHERE pm.project_id = p.project_id) AS member_count,
            (SELECT COUNT(*) FROM tasks t WHERE t.project_id = p.project_id) AS task_count
            FROM projects p
            WHERE p.project_owner = ? ORDER BY p.project_created_at DESC");
        $stmt->execute(array($username));

        $projects = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $projects[] = $this->rowToEntity($row);
        }

        return $projects;
    }

    /**
     * Finds Project entities by their member's username, excluding owned projects
     * 
     * @param string $username The member's username
     * @return array An array of Project entities
     */
    public function findByMemberOnly($username) {
        $stmt = $this->db->prepare("SELECT p.* FROM projects p JOIN project_members pm ON p.project_id = pm.project_id 
            WHERE pm.username = ? AND p.project_owner != ? ORDER BY p.project_created_at DESC");

        $stmt->execute(array($username, $username));
        
        $projects = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $projects[] = $this->rowToEntity($row);
        }

        return $projects;
    }

    public function findByMemberOnlyWithCounts($username) {
        $stmt = $this->db->prepare("SELECT p.*,
            (SELECT COUNT(*) FROM project_members pm WHERE pm.project_id = p.project_id) AS member_count,
            (SELECT COUNT(*) FROM tasks t WHERE t.project_id = p.project_id) AS task_count
            FROM projects p JOIN project_members pm ON p.project_id = pm.project_id 
            WHERE pm.username = ? AND p.project_owner != ? ORDER BY p.project_created_at DESC");

        $stmt->execute(array($username, $username));
        
        $projects = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $projects[] = $this->rowToEntity($row);
        }

        return $projects;        
    }

    /**
     * Finds all Project entities where the user is either owner or member
     * 
     * @param string $username The username of the user
     * @return array An array of Project entities
     */
    public function findAllByUser($username) {
        $stmt = $this->db->prepare("SELECT p.   * FROM projects p JOIN project_members pm ON p.project_id = pm.project_id 
            WHERE pm.username = ? ORDER BY p.project_created_at DESC");
        $stmt->execute(array($username));

        $projects =array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $projects[] = $this->rowToEntity($row);
        }

        return $projects;
    }

    /**
     * Finds all Project entities
     * 
     * @return array An array of Project entities
     */
    public function findAll() {
        $stmt = $this->db->prepare("SELECT * FROM projects ORDER BY project_created_at DESC");
        $stmt->execute();

        $projects = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $projects[] = $this->rowToEntity($row);
        }

        return $projects;
    }


    public function delete($projectId) {
        $stmt = $this->db->prepare("DELETE FROM projects WHERE project_id = ?");
        $stmt->execute(array($projectId));

        return ($stmt->rowCount() > 0);
    }

    public function addMember($username, $projectId) {
        // Check if the member already exists
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM project_members WHERE project_id = ? AND username = ?");
        $stmt->execute(array($projectId, $username));

        if ($stmt->fetchColumn() > 0) {
            return true; // Member already exists
        }

        // Add the new member
        $stmt = $this->db->prepare("INSERT INTO project_members (project_id, username) VALUES (?, ?)");
        return $stmt->execute(array($projectId, $username));        
    }

    public function removeMember($username, $projectId) {
        $stmt = $this->db->prepare("DELETE FROM project_members WHERE project_id = ? AND username = ?");
        $stmt->execute(array($projectId, $username));

        return ($stmt->rowCount() > 0);        
    }

    public function getProjectMembers($projectId) {
        $stmt = $this->db->prepare("SELECT username FROM project_members WHERE project_id = ?");
        $stmt->execute(array($projectId));

        $members = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $members[] = $row['username'];
        }

        return $members;
    }

    public function updateMembers($project) {
        $currentMembers = $this->getProjectMembers($project->getId());
        $newMembers = $project->getMembers();

        // Remove members that are no longer in the project
        foreach ($currentMembers as $memberUsername) {
            if (!in_array($memberUsername, $newMembers)) {
                $this->removeMember($memberUsername, $project->getId());
            }
        }
        // Add new members
        foreach ($newMembers as $memberUsername) {
            if (!in_array($memberUsername, $currentMembers)) {
                $this->addMember($memberUsername, $project->getId());
            }
        }
    }

    public function isUserMember($username, $projectId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM project_members WHERE project_id = ? AND username = ?");
        $stmt->execute(array($projectId, $username));

        return ($stmt->fetchColumn() > 0);
    }

    public function isUserOwner($username, $projectId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM projects WHERE project_id = ? AND project_owner = ?");
        $stmt->execute(array($projectId, $username));

        return ($stmt->fetchColumn() > 0);
    }

    /**
     * Converts a database row to a Project entity     
     * 
     * @param array $row The database row
     * @return Project The Project entity
     */
    private function rowToEntity($row) {
        // Create project entity
        $project = new Project();

        // Set attributes
        $project->setId($row['project_id']);
        $project->setName($row['project_name']);
        $project->setDescription($row['project_description']);
        $project->setOwnerUsername($row['project_owner']);
        $createdAt = DateTime::createFromFormat('Y-m-d H:i:s', $row['project_created_at']);
        $project->setCreatedAt($createdAt);

        if (isset($row['member_count'])) {
            $project->setMemberCount($row['member_count']);
        }

        if (isset($row['task_count'])) {
            $project->setTaskCount($row['task_count']);
        }

        return $project;
    }

}


?>