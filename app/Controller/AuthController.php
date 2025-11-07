<?php
// file: /app/Controller/AuthController.php

if (!defined('BASE_URL')) {
    require_once(__DIR__ . '/../../config/paths.php');
}


require_once(BASE_URL.'/core/ViewManager.php');
require_once(BASE_URL.'/core/I18n.php');

require_once(BASE_URL.'/app/Model/Entity/User.php');
require_once(BASE_URL.'/app/Model/Mapper/UserMapper.php');

require_once(BASE_URL.'/app/Controller/BaseController.php');

class AuthController extends BaseController {

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
        $this->view->setLayout("auth_base");
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
                $_SESSION["currentuser"] = $this->userMapper->getUser($_POST["auth-identifier"])->getUsername();

                // Send user to the restricted area (dashboard)
                $this->view->redirect("dashboard", "index");

            } else {
                $errors = array();
                $errors["general"] = i18n("User is not valid");
                $this->view->setVariable("errors", $errors);
            }
        }

        // Render the login view (also for GET requests)
        $this->view->setVariable("auth", "login");  // View/pages/auth/login.php
    }

    

}

?>