<?php
// file: /app/Controller/AuthController.php

require_once(__DIR__.'/../core/ViewManager.php');
require_once(__DIR__.'/../core/I18n.php');

require_once(__DIR__.'/../Model/Entity/User.php');
require_once(__DIR__.'/../Model/Mapper/UserMapper.php');

require_once(__DIR__.'/BaseController.php');

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

        
        $this->view->moveToFragment("logo");
        include(__DIR__."/../View/shared/components/logo.php");
        $this->view->moveToDefaultFragment();

        $this->view->setVariable("page-title", i18n("Login"));
        $this->view->setVariable("auth-title", i18n("Login"));
        $this->view->setVariable("auth-subtitle", i18n("Collaborative task manager"));

        $this->view->setVariable("auth-footer-text", i18n("Don't have an account?"));
        $this->view->setVariable("footer-controller", "auth");
        $this->view->setVariable("footer-action", "register");
        $this->view->setVariable("auth-footer-link-text", i18n("Sign up here"));

        $this->view->moveToFragment("footer");
        include(__DIR__."/../View/shared/components/footer.php");
        $this->view->moveToDefaultFragment();

        $this->view->render("auth", "login");  // View/pages/auth/login.php
    }

    

}

?>
