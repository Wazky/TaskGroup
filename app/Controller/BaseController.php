<?php
// file: /app/Controller/BaseController.php

require_once(__DIR__.'/../core/ViewManager.php');
require_once(__DIR__.'/../core/I18n.php');

require_once(__DIR__.'/../Model/Entity/User.php');

/**
 * BaseController class
 * 
 * Implements the common functionality of all controllers
 * It provides access to the ViewManager and the current user instance.
 */
class BaseController {

    // AUTHENTICATION CONSTANTS

    private const AUTH_CONTROLLER_NAME = "auth";
    private const AUTH_LOGIN_ACTION = "login";

    /**
     * The ViewManager instance
     * @var ViewManager
     */
    protected $view;

    /**
     * The current user instance 
     * 
     * @var User
     */
    protected $currentUser;

    /**
     * Constructor
     */
    public function __construct() {
        
        $this->view = ViewManager::getInstance();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION["current_user"])) {

            $this->currentUser = $_SESSION["current_user"];
            // Pass current username to view
            $this->view->setVariable("current_user", $this->currentUser);
        }
    }

    protected function isUserLoggedIn() {
        return isset($_SESSION["current_user"]);
    }

    protected function requireAuthentication() {
        if (!$this->isUserLoggedIn()) {
            $this->view->redirect(self::AUTH_CONTROLLER_NAME, self::AUTH_LOGIN_ACTION);
            exit;
        }
    }

}

?>