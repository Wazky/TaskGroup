<?php
//file: /app/api/AuthRest.php

//Required files
require_once(__DIR__."/../Model/Entity/User.php");
//require_once(__DIR__."/../Model/Mapper/UserMapper.php");
require_once(__DIR__."/BaseRest.php");


class AuthRest extends BaseRest {

    public function __construct() {
        parent::__construct();
    }

    /**
     * POST '/api/auth/register'
     */
    public function register($data) {
        try {
            // Check data presence
            if ($data === null || !is_object($data)) {
                $this->badRequest('No data received or invalid format', 400);
                return;
            }

            // Populate user object with param data
            $user = new User($data->username, $data->email, $data->password);

            // Check user validity
            $user->checkDataValidity();

            // Check username is not already in use
            if ($this->userMapper->userIdentifierExists($user->getUsername())) {
                $this->badRequest('Username already exists', 400);
            }
            // Check email is not already in user
            if ($this->userMapper->userIdentifierExists($user->getEmail())) {
                $this->badRequest('Email already exists', 400);
            }

            // Save user in the DB
            $this->userMapper->save($user);

            // Create format data response with the new user data
            $response = $this->formatUserResponse($user);

            // Send 200 OK (with the response)
            $this->created($response, "User registered successfully.");

        } catch (ValidationException $e) {
            $this->badRequest('Invalid user data', 400);

        } catch (Exception $e) {
            $this->serverError('Registration failed', $e);
        }
    }

    /**
     * POST '/api/auth/login'
     */
    public function login() {
        try {

            $currentLogged = parent::authenticateUser();

            $data = ["username" => $currentLogged->getUsername()];

            $this->ok($data, 'Login succesful.');

        } catch (Exception $e) {
            $this->serverError('Login failed', $e);
        }
    }
    
    /**
     * Format user response data
     * 
     * @param User $user The user to format
     * @return array The formatted user data
     */
    private function formatUserResponse(User $user) {
        return [
            "username" => $user->getUsername(),
            "email" => $user->getEmail(),
            //"password" => $user->getPassword()
        ];
    }


}

$authRest = new AuthRest();
URIDispatcher::getInstance()->map("POST", "/auth/register", array($authRest,"register"))
                            ->map("POST", "/auth/login", array($authRest,"login"))                                              

?>