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

    public function update(User $oldUser, User $newUser) {
        $stmt = $this->db->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE username = ?");
        $stmt->execute(array($newUser->getUsername(), $newUser->getEmail(), $newUser->getPassword(), $oldUser->getUsername()));
    }

    /**
     * Checks if a username or email already exists in the database
     * 
     * @param string $userIdentifier The username or email to check
     * @return bool True if the username or email exists, false otherwise
     */
    public function userIdentifierExists($userIdentifier) {
        $stmt = $this->db->prepare("SELECT COUNT(*)FROM users WHERE username = ? OR email = ?");
        $stmt->execute(array($userIdentifier, $userIdentifier));

        return ($stmt->fetchColumn() > 0);
    }

    /**
     * Validates user credentials and returns the username if authentication succeds.
     * 
     * Check if the provided identifier (username or email) and password match 
     * a user in the database.
     * 
     * @param string $userIdentifier The username or email 
     * @param string $password The password of the user
     * @return string|false The authenticated username on success,
     * false on failure
     */
    public function isValidUser($userIdentifier, $password) {
        $stmt = $this->db->prepare("SELECT username FROM users WHERE (username = ? OR email = ?) AND password = ?");
        $stmt->execute(array($userIdentifier, $userIdentifier, $password));

        $username = $stmt->fetchColumn(); // fetchColumn ya que es un solo valor

        return ($username) ? $username : false ;
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