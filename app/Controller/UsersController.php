<?php
// file: app/Controller/UserController.php

require_once(__DIR__.'/../core/ViewManager.php');
require_once(__DIR__.'/../core/I18n.php');

require_once(__DIR__.'/../Model/Entity/User.php');
require_once(__DIR__.'/../Model/Mapper/UserMapper.php');

require_once(__DIR__.'/BaseController.php');

class UsersController extends BaseController {

    // USER CONSTANTS

    private const USER_LAYOUT = "dashboard";            // Layout for user pages
    private const USER_CONTROLLER_NAME = "users" ;        // Controller name for user pages    
    private const USER_DETAIL_ACTION = "detail";        // Detail action name

    // AUTHENTICATION CONSTANTS

    private const AUTH_LAYOUT = "auth_base";            // Layout for authentication pages
    private const AUTH_CONTROLLER_NAME = "auth";        // Controller name for authentication
    private const AUTH_LOGIN_ACTION = "login";          // Login action name
    private const AUTH_REGISTER_ACTION = "register";    // Register action name

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

        // Set the layout for user pages
        $this->view->setLayout(self::USER_LAYOUT);
    }

    public function detail() {
        // check authentication
        $this->requireAuthentication();

        if (!isset($_GET["id"])) {
            $this->prepareAlert(i18n("User ID is required."), "alert-info", "bi-info-circle");
        }
        
        $userIdentifier = $_GET["id"];
        $user = $this->userMapper->getUser($userIdentifier);

        if ($user === null) {
            $this->prepareAlert(i18n("User not found."), "alert-info", "bi-info-circle");
        }

        $this->view->setVariable("user", $user);
        $this->view->render(self::USER_CONTROLLER_NAME, self::USER_DETAIL_ACTION);

    }

    private function prepareAlert($message, $type = "alert-info", $icon = "bi-info-circle") {
        $this->view->setFlash(i18n($message));
        $this->view->setVariable("flash-type", $type, true);
        $this->view->setVariable("flash-icon", $icon, true);
    }

}

?>