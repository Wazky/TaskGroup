<?php
// file: /app/rest/BaseRest.php

// Required files
require_once(__DIR__."/../Model/Entity/User.php");
require_once(__DIR__."/../Model/Mapper/UserMapper.php");

/**
 * Class BaseRest
 * 
 * Superclass for Rest endpoints with HTTP Basic Auth
 * 
 * @author lipido <lipido@gmail.com>
 * @author isma <ismaelaqua@hotmail.com>
 */
class BaseRest {

    protected $currentUser = null;
    protected $userMapper = null;

    // UserMapper cache instance
    private static $userMapperInstance = null;

    public function __construct() { 
        // Singleton UserMapper Instance
        if (self::$userMapperInstance === null) self::$userMapperInstance = new UserMapper();

        $this->userMapper = self::$userMapperInstance;
    }

    /**
     * Authenticate the current request.
     * 
     * If the request does not contain auth credentials,
     * it will generate a 401 response code and end PHP processing.
     * If the request contain credentials, it will be checked agaisnt the database.
     * If the credentials are ok, it will return the User object just logged.
     * If the credentials are invalid, it will generate a 401 code and end PHP processing.
     * 
     * @return User the user just authenticated.
     */
    public function authenticateUser() {
        // Requesst does not cotain auth credentials
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            
            $this->unauthorized('This operation required authentication');

        }    
        // Request contains auth credentials            
            
        // Check credentials
        $authenticatedUsername =  $this->userMapper->isValidUser($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);

        // Invalid credentials
        if ($authenticatedUsername === false) {
            $this->unauthorized("Invalid username or password");
        }

        // Return authenticated user object 
        return new User($authenticatedUsername);
    }

    /**
     * ==============================
     * HTTP RESPONSE HELPERS
     * ==============================
     */

    /**
     * Generic JSON response
     * 
     * Sends a JSON response with the given data and HTTP status code.
     * 
     * @param mixed $data The data to send as JSON  
     * @param int $statusCode The HTTP status code (default 200)
     */
    protected function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');

        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Generic success response
     * 
     * Sends a JSON success response with the given data, message and HTTP status code.
     * 
     * @param mixed $data The data to send as JSON
     * @param string $message The success message
     * @param int $statusCode The HTTP status code (default 200)
     */
    private function sendSuccessResponse($data = null, $message = '', $statusCode = 200) {
        // Build response array
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('c')    // ISO 8601
        ];

        // Send JSON response
        $this->jsonResponse($response, $statusCode);
    }

    /**
     * Generic error response
     * 
     * Sends a JSON error response with the given message, errors and HTTP status code.
     * 
     * @param string $message The error message
     * @param array $errors Additional error details
     * @param int $statusCode The HTTP status code (default 400)
     */
    private function sendErrorResponse($message = '', $errors = [], $statusCode = 400) {
        // Build response array
        $response = [
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => date('c')    // ISO 8601
        ];

        // Send JSON response
        $this->jsonResponse($response, $statusCode);
    }

    /**
     * 200 OK
     */
    protected function ok($data = null, $message = 'Operation successful') {
        $this->sendSuccessResponse($data, $message, 200);
    }

    /**
     * 201 Created
     */
    protected function created($data = null, $message = 'Resource created successfully') {
        $this->sendSuccessResponse($data, $message, 201);
    }

    /**
     * 400 Bad Request
     */
    protected function badRequest($message = 'Bad Request', $errors = []) {
        $this->sendErrorResponse($message, $errors, 400);
    }

    /**
     * 401 Unauthorized
     */
    protected function  unauthorized($message = 'Unauthorized') {        
        header($_SERVER['WWW-Authenticate: Basic realm="Rest API of TaskGroup"']);
        $this->sendErrorResponse($message, [], 401);
    }

    protected function forbidden($message = 'Forbidden') {
        $this->sendErrorResponse($message, [], 403);
    }

    /**
     * 404 Not Found
     */
    protected function notFound($message = 'Resource not found') {
        $this->sendErrorResponse($message, [], 404);
    }

    /**
     * 409 Conflict
     */
    protected function conflict($message = 'Conflict', $errors = []) {
        $this->sendErrorResponse($message, $errors, 409);
    }

    /**
     * 422 Unprocessable Entity (validation error)
     */
    protected function unprocessableEntity($message = 'Unprocessable Entity', $errors = []) {
        $this->sendErrorResponse($message, $errors, 422);
    }

    /**
     * 500 Internal Server Error
     */
    protected function serverError($message = 'Internal Server Error', Exception $error = null) {

        // Coment the following lines to avoid exposing internal errors
        if ($error !== null) {
            $message .= ': '.$error->getMessage();
        }

        $this->sendErrorResponse($message, [], 500);
    }
}

?>