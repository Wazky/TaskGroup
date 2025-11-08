<?php
//file: /app/Model/Mapper/UserMapper.php

// Include the PDOConnection (to get the database connection)
require_once(__DIR__ . '/../../core/PDOConnection.php'); 

/**
 * Class UserMapper
 * 
 * Maps User entities to the database and vice versa
 */
class UserMapper {

    /**
     * The database connection
     * 
     * @var PDO 
     */
    private $db;

    /**
     * Constructor
     */
    public function __construct() {
        $this->db = PDOConnection::getInstance();
    }

    /**
     * Saves a User entity to the database
     * 
     * @param User $user The User entity to save
     * @throws PDOException If an error occurs during the database operation
     * @return void
     */
    public function save($user) {
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password) values (?, ?, ?)");
        $stmt->execute(array($user->getUsername(), $user->getEmail(), $user->getPassword()));
    }

    /**
     * Checks if a username or email already exists in the database
     * 
     * @param string $userIdentifier The username or email to check
     * @return bool True if the username or email exists, false otherwise
     */
    public function usernameExists($userIdentifier) {
        $stmt = $this->db->prepare("SELECT COUNT(*)FROM users WHERE username = ? OR email = ?");
        $stmt->execute(array($userIdentifier, $userIdentifier));

        return ($stmt->fetchColumn() > 0);
    }

    /**
     * Validates if the provided user identifier and password match a user in the database
     * 
     * @param string $userIdentifier The username or email of the user
     * @param string $password The password of the user
     * @return bool True if the credentials are valid, false otherwise
     */
    public function isValidUser($userIdentifier, $password) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE (username = ? OR email = ?) AND password = ?");
        $stmt->execute(array($userIdentifier, $userIdentifier, $password));

        return ($stmt->fetchColumn() > 0);
    }

    /**
     * Retrieves a User entity from the database by username or email
     * 
     * @param string $userIdentifier The username or email of the user
     * @return User|null The User entity if found, null otherwise
     */
    public function getUser($userIdentifier) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE (username = ? OR email = ?)");
        $stmt->execute(array($userIdentifier, $userIdentifier));

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // If no user is found, return null
        if (!$row) {
            return null;
        }

        // Convert the database row to a User entity and return it
        return $this->rowToEntity($row);
    }

    /**
     * Converts a database row to a User entity     
     * 
     * @param array $row The database row
     * @return User The User entity
     */
    private function rowToEntity($row) {
        // Create a new User entity
        $user = new User();

        // Set the properties of the User entity
        $user->setUsername($row['username']);
        $user->setEmail($row['email']);
        $user->setPassword($row['password']);

        return $user;
    }


    // Additional methods for user retrieval, update, and deletion can be added here

}

?>