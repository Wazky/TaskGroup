<?php
// file: /app/Controller/AuthController.php

require_once(__DIR__.'/../core/ViewManager.php');
require_once(__DIR__.'/../core/I18n.php');

require_once(__DIR__.'/../Model/Entity/User.php');
require_once(__DIR__.'/../Model/Mapper/UserMapper.php');

require_once(__DIR__.'/BaseController.php');

class AuthController extends BaseController {

    // AUTHENTICATION CONSTANTS

    private const AUTH_LAYOUT = "auth_base";            // Layout for authentication pages
    private const AUTH_CONTROLLER_NAME = "auth";        // Controller name for authentication
    private const AUTH_LOGIN_ACTION = "login";          // Login action name
    private const AUTH_REGISTER_ACTION = "register";    // Register action name
    
    // PROJECTS CONSTANTS

    private const PROJECTS_CONTROLLER_NAME = "projects"; // Controller name for projects
    private const PROJECTS_INDEX_ACTION = "index";      // Index action name for projects

    /**
     * Reference to the UserMapper to interact with the database
     * 
     * @var UserMapper
     */
    private $userMapper;

    /**
     * Constructor
     */
    public function __construct() {

        parent::__construct();
        $this->userMapper = new UserMapper();
        
        // Set the layout for authentication pages
        $this->view->setLayout(self::AUTH_LAYOUT);
    }
    
    /**
     * Action to login
     * 
     * Logins a user given a username/email and password
     * checking the credentials against the database
     * 
     * If called via GET, shows the login form
     * If called via POST, processes the login form
     * 
     * 
     */
    public function login() {
        // Handle POST request
        if (isset($_POST["auth-identifier"])) {

            // Validate user credentials
            if ($this->userMapper->isValidUser($_POST["auth-identifier"], $_POST["password"])) {

                // Set the current user in the session
                $_SESSION["current_user"] = $this->userMapper->getUser($_POST["auth-identifier"]);

                // Send user to the dashboard (projects index)
                $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_INDEX_ACTION);

            } else {
                $errors = array();
                $errors["general"] = i18n("Error trying to login: invalid credentials");
                $this->view->setVariable("errors", $errors);
            }
        }

        // Render the login view: View/pages/auth/login.php (also for GET requests) 
        $this->view->render(self::AUTH_CONTROLLER_NAME, self::AUTH_LOGIN_ACTION);  
    }

    public function register() {
        
        $user = new User();
        
        // Handle POST request
        if (isset($_POST["username"])) {

            $user->setUsername($_POST["username"]);
            $user->setEmail($_POST["email"]);
            $user->setPassword($_POST["password"]);

            try {
                // Validate new user data
                $user->checkDataValidity();

                // Check if username or email exists in the database
                if (!$this->userMapper->userIdentifierExists($user->getUsername()) && 
                    !$this->userMapper->userIdentifierExists($user->getEmail())) {

                    // Save the new user in the database
                    $this->userMapper->save($user);
                    
                    $this->view->setFlash(i18n("User account successfully created."));
                    // Set the current user in the session
                    $_SESSION["current_user"] = $user;

                    // Registration successful, redirect to projects index
                    $this->view->redirect(self::PROJECTS_CONTROLLER_NAME, self::PROJECTS_INDEX_ACTION);

                } else {
                // User data already exists in the database
                    $errors = array();
                    $errors["general"] = i18n("Error trying to register");

                    if ($this->userMapper->userIdentifierExists($user->getUsername())) {
                        $errors["username"] = i18n("Username already exists");
                    }

                    if ($this->userMapper->userIdentifierExists($user->getEmail())) {
                        $errors["email"] = i18n("Email already exists");
                    }

                    $this->view->setVariable("errors", $errors);
                }
            } catch (ValidationException $ex) {
                // Validation errors occurred
                $errors = array();
                $errors = $ex->getErrors();
                $errors["general"] = i18n("Error trying to register");
                $this->view->setVariable("errors", $errors);
            }
        }

        $this->view->setVariable("current_user", $user);

        // Render the register view: View/pages/auth/register.php (also for GET requests)
        $this->view->render(self::AUTH_CONTROLLER_NAME, self::AUTH_REGISTER_ACTION);
    }
    
}

?>
