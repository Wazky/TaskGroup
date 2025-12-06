<?php
//file: /app/rest/UserRest.php

// Required files
require_once(__DIR__."/../Model/Entity/User.php");
require_once(__DIR__."/../Model/Mapper/UserMapper.php");
require_once(__DIR__."/BaseRest.php");

/**
 * Class UserRest
 * 
 * 
 */
class UserRest extends BaseRest {

    public function __construct() {
        parent::__construct();        
    }

    /**
     * GET '/api/users'
     */
    public function index() {}

    /**
     * POST '/api/users' (Delegated to AuthRest)
     */
    public function store() {}

    /**
     * GET '/api/users/{id}'
     */
    public function show($userIdentifier) {
        try {
            // Check auth
            $currentUser = parent::authenticateUser();

            // Validate user identifier
            if (!is_string($userIdentifier)) $this->badRequest('Invalid user identifier (username or email).');

            // Find the User object
            $requestedUser = $this->userMapper->getUser($userIdentifier);

            // If null, user wasn't found
            if ($requestedUser === null) $this->notFound('User not found');

            // Remove password param if current user is not the requested 
            if ($requestedUser->getUsername() !== $currentUser->getUsername()) $requestedUser->setPassword(null);

            // 
            $response = $this->formatUserResponse($requestedUser);

            // Return response
            $this->ok($response, 'User retrieved successfully');

        } catch (Exception $e) {
            $this->serverError("Could not retrieve user ", $e);
        }
    }

    /**
     * PUT '/api/users/{id}'
     */
    public function update($userIdentifier, $userData) {
        try {
            // Check auth
            $currentUser = parent::authenticateUser();

            // Validate user identifier
            if (!is_string($userIdentifier)) $this->badRequest('Invalid user identifier (username or email).', 400);

            // Find the user
            $requestedUserOld = $this->userMapper->getUser($userIdentifier);
            
            // Check if requested user is null
            if ($requestedUserOld === null) $this->notFound('User not found');
            
            // Check if user to update is not the current user 
            if ($requestedUserOld->getUsername() !== $currentUser->getUsername()) $this->forbidden('You are not the user to update');

            // Check if data object does not exists or has no properties
            if (!is_object($userData) || empty(get_object_vars($userData))) $this->badRequest('No data received or invalid format', 400); 

            // Update user with data to be upload 
        // **Delegate into a function**
            $requestedUserNew = new User($requestedUserOld->getUsername(), $requestedUserOld->getEmail(), $requestedUserOld->getPassword());
            
            // Check if username param is provided and different from actual
            if (isset($userData->username) && ($userData->username !== null) && ($requestedUserOld->getUsername() !== $userData->username)) {
                $username = $userData->username;
                // Check if username is valid and not in use 
                if ((User::validateUsername($username)) && !($this->userMapper->userIdentifierExists($username))) {
                    $requestedUserNew->setUsername($username);
                }
            }
            // Check if email param is provided adn different from actual
            if (isset($userData->email) && ($userData->email !== null) && ($requestedUserOld->getEmail() !== $userData->email)) {
                $email = $userData->email;
                // Check if email is valid and not in use
                if ((User::validateEmail($email)) && !($this->userMapper->userIdentifierExists($email))) {
                    $requestedUserNew->setEmail($email);
                }
            }
            // Check if password is provided and different from actual
            if (isset($userData->password) && ($userData->password !== null) && ($requestedUserOld->getPassword() !== $userData->password)) {
                $password = $userData->password;
                // Check if password is valid
                if (User::validatePassword($password)) {
                    $requestedUserNew->setPassword($password);
                }
            }
        // **Delegate into a function**
        
        // Update the user entry in DB
        $this->userMapper->update($requestedUserOld, $requestedUserNew);

        // Create format data response with the updated user data
        $response = $this->formatUserResponse($requestedUserNew);

        // Send 200 OK (with the repsonse)
        $this->ok($response, 'User updated successfully');

        } catch (ValidationException $e) {
            $this->badRequest('Invalid user data', 400);

        } catch (Exception $e) {
            $this->serverError('Could not update user ', $e);
        }
    }

    /**
     * DELETE '/api/users/{id}'
     */
    public function destroy($userIdentifier) {}

    private function formatUserResponse(User $user) {
        return [
            "username" => $user->getUsername(),
            "email" => $user->getEmail(),
            "password" => $user->getPassword()
        ];
    }

}

$userRest = new UserRest();
URIDispatcher::getInstance()->map("GET", "/users", array($userRest,"index"))                            
                            ->map("POST", "/users", array($userRest,"store"))
                            ->map("GET", "/users/$1", array($userRest,"show"))
                            ->map("PUT", "/users/$1", array($userRest,"update"))
                            ->map("DELETE", "/users/$1", array($userRest,"destroy"))


?>