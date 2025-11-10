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

        if (isset($_SESSION["current-user"])) {

            $this->currentUser = $_SESSION["current-user"];
            // Pass current username to view
            $this->view->setVariable("current-user", $this->currentUser->getUsername());
        }
    }

}

?>