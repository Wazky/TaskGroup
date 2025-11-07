<?php
// file: /app/Controller/BaseController.php

if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../config/paths.php';
}

require_once(BASE_URL.'/core/ViewManager.php');
require_once(BASE_URL.'/core/I18n.php');

require_once(BASE_URL.'/app/Model/Entity/User.php');

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

        if (isset($_SESSION["currentuser"])) {

            $this->currentUser = $_SESSION["currentuser"];
            // Pass current username to view
            $this->view->setVariable("currentUsername", $this->currentUser->getUsername());
        }
    }

}

?>