<?php
// file: /app/Model/Entity/User.php

require_once(__DIR__.'/../../core/ValidationException.php');
require_once(__DIR__.'/../../core/I18n.php');

/**
 * User entity class
 * 
 * Represents a user in the system
 */
class User {

    // VALIDATION CONSTANTS

    // Username constraints
    const MIN_USERNAME_LENGTH = 3;
    const MAX_USERNAME_LENGTH = 40;
    const USERNAME_PATTERN = "/^[a-zA-Z0-9_]+$/";

    // Email constraints
    const MAX_EMAIL_LENGTH = 100;

    // Password constraints
    const MIN_PASSWORD_LENGTH = 6;
    const MAX_PASSWORD_LENGTH = 255;

    // ERROR MESSAGES CONSTANTS
    const ERROR_REQUIRED = "is required.";
    const ERROR_TOO_SHORT = "must be at least %d characters long.";
    const ERROR_TOO_LONG = "cannot exceed %d characters.";
    const ERROR_INVALID_FORMAT = "has an invalid format.";  

    // ATTRIBUTES

    /**
     * The username of the user
     * 
     * @var string
     */
    private $username;

    /**
     * The email of the user
     * 
     * @var string
     */
    private $email;

    /**
     * The password of the user
     * 
     * @var string
     */
    private $password;

    /**
     * Constructor
     * 
     * @param string $username The username of the user
     * @param string $email The email of the user
     * @param string $password The password of the user
     */
    public function __construct($username =  null, $email = null, $password = null) {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    // GETTERS AND SETTERS

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * Validates the user data
     * 
     * @throws ValidationException If any validation errors occur
     * @return void
     */
    public function checkDataValidity() {
        $errors = array();

        // Username validation
        $errors["username"] = $this->validateUsername($this->username);

        // Email validation
        $errors["email"] = $this->validateEmail($this->email);

        // Password validation
        $errors["password"] = $this->validatePassword($this->password);

        // Filter the errors array to keep only non-empty error messages and check if there are any errors
        if (!empty(array_filter($errors))) {
            // Throw a validation exception with the collected error messages
            throw new ValidationException($errors, "User data validation failed.");
        }
    }

    /**
     * DEPRECATED
     *  Validates the username of the user
     * 
     * @return string An error message if validation fails, empty string otherwise
     */
    private function usernameValidation() {
        $baseError = i18n("Username");

        // Required check
        if (empty($this->username)) {
            return $baseError . " " . i18n(self::ERROR_REQUIRED);
        }

        // Min length check
        if (strlen($this->username) < self::MIN_USERNAME_LENGTH) {
            return $baseError . " " . sprintf(i18n(self::ERROR_TOO_SHORT), self::MIN_USERNAME_LENGTH);
        }

        // Max length check 
        if (strlen($this->username) > self::MAX_USERNAME_LENGTH) {
            return $baseError . " " . sprintf(i18n(self::ERROR_TOO_LONG), self::MAX_USERNAME_LENGTH);
        }

        // Format check
        if (!preg_match(self::USERNAME_PATTERN, $this->username)) {
            return $baseError . " " . i18n(self::ERROR_INVALID_FORMAT) . " " . i18n("Can only contain letters, numbers, and underscores (no spaces).");
        }

        // Additional username checks can be added here

        // If all checks pass, return empty string
        return "";
    }

    public static function validateUsername($username) {
        $errors = [];
        $baseError = i18n("Username");

        // Required check
        if (empty($username)) {
            return $baseError . " " . i18n(self::ERROR_REQUIRED);
        }

        // Min length check
        if (strlen($username) < self::MIN_USERNAME_LENGTH) {
            return $baseError . " " . sprintf(i18n(self::ERROR_TOO_SHORT), self::MIN_USERNAME_LENGTH);
        }

        // Max length check 
        if (strlen($username) > self::MAX_USERNAME_LENGTH) {
            return $baseError . " " . sprintf(i18n(self::ERROR_TOO_LONG), self::MAX_USERNAME_LENGTH);
        }

        // Format check
        if (!preg_match(self::USERNAME_PATTERN, $username)) {
            return $baseError . " " . i18n(self::ERROR_INVALID_FORMAT) . " " . i18n("Can only contain letters, numbers, and underscores (no spaces).");
        }

        // Additional username checks can be added here

        // If all checks pass, return true
        return true;

    }

    /**
     * DEPRECATED
     *  Validates the email of the user
     * 
     * @return string An error message if validation fails, empty string otherwise
     */
    private function emailValidation() {
        $baseError = "Email ";

        // Required check
        if (empty($this->email)) {
            return $baseError . self::ERROR_REQUIRED;
        }

        // Max length check
        if (strlen($this->email) > self::MAX_EMAIL_LENGTH) {
            return $baseError . sprintf(self::ERROR_TOO_LONG, self::MAX_EMAIL_LENGTH);
        }

        // Format check
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return $baseError . self::ERROR_INVALID_FORMAT;
        }

        // Additional email checks can be added here

        // If all checks pass, return empty string
        return "";
    }

    public static function validateEmail($email) {
        $baseError = "Email ";

        // Required check
        if (empty($email)) {
            return $baseError . self::ERROR_REQUIRED;
        }

        // Max length check
        if (strlen($email) > self::MAX_EMAIL_LENGTH) {
            return $baseError . sprintf(self::ERROR_TOO_LONG, self::MAX_EMAIL_LENGTH);
        }

        // Format check
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $baseError . self::ERROR_INVALID_FORMAT;
        }

        // Additional email checks can be added here

        // If all checks pass,
        return true;
    }
    
    /**
     * Validates the password of the user
     * 
     * @return string An error message if validation fails, empty string otherwise
     */
    private function passwordValidation() {
        $baseError = "Password ";

        // Required check
        if (empty($this->password)) {
            return $baseError . self::ERROR_REQUIRED;
        }

        // Min length check
        if (strlen($this->password) < self::MIN_PASSWORD_LENGTH) {
            return $baseError . sprintf(self::ERROR_TOO_SHORT, self::MIN_PASSWORD_LENGTH);
        }

        // Max length check
        if (strlen($this->password) > self::MAX_PASSWORD_LENGTH) {
            return $baseError . sprintf(self::ERROR_TOO_LONG, self::MAX_PASSWORD_LENGTH);
        }

        // Additional password strength checks can be added here

        // If all checks pass, return empty string
        return "";
    }

    public static function validatePassword($password) {
        $baseError = "Password ";

        // Required check
        if (empty($password)) {
            return $baseError . self::ERROR_REQUIRED;
        }

        // Min length check
        if (strlen($password) < self::MIN_PASSWORD_LENGTH) {
            return $baseError . sprintf(self::ERROR_TOO_SHORT, self::MIN_PASSWORD_LENGTH);
        }

        // Max length check
        if (strlen($password) > self::MAX_PASSWORD_LENGTH) {
            return $baseError . sprintf(self::ERROR_TOO_LONG, self::MAX_PASSWORD_LENGTH);
        }

        // Additional password strength checks can be added here

        // If all checks pass, return true
        return true;
    }

}

?>  