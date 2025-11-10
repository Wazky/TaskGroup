<?php

class ViewManager {

    /**
     * Key for the default fragment
     * 
     * @var string 
     */
    const DEFAULT_FRAGMENT = "__default__";

    /**
     * An array to store the contents of the different fragments
     * 
     * @var mixed 
     */
    private $fragmentContents = array();

    /**
     * The current fragment being captured
     * 
     * @var string
     */
    private $currentFragment = self::DEFAULT_FRAGMENT;

    /**
     * Values of the variables to be passed to the views
     * 
     * @var mixed
     */
    private $variables = array();

    /**
     * The name of the layout to be used
     * 
     * @var string
     */
    private $layout = "dashboard"; // default layout

    /**
     * Constructor
     */
    private function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        ob_start();
    }

    // BUFFER MANAGEMENT METHODS

    /**
     * Saves into  the current fragment the output buffer contents.
     * Cleans the output buffer.
     * 
     * @return void
     */
    private function saveCurrentFragment() {
        // save the current fragment
        $this->fragmentContents[$this->currentFragment].= ob_get_contents();
        // clean the output buffer
        ob_clean();
    }

    /**
     * Changes the current fragment where output is being captured
     * 
     * The current fragment contents are saved before changing to the new fragment.
     * The next output will be captured to the new fragment.
     * 
     * @param string $name The name of the fragment to move to
     * @return void
     */
    public function moveToFragment($name) {
        // save the current fragment contents
        $this->saveCurrentFragment();
        $this->currentFragment = $name;
    }

    /**
     * Changes the current fragment to the default fragment
     * 
     * The current fragment contents are saved before changing to the default fragment.
     * The next output will be captured to the default fragment.
     * 
     * @return void
     */
    public function moveToDefaultFragment() {
        $this->moveToFragment(self::DEFAULT_FRAGMENT);
    }

    public function getFragment($fragmentName, $defaultValue = "") {
        if (!isset($this->fragmentContents[$fragmentName])) {
            return $defaultValue;
        }
        return $this->fragmentContents[$fragmentName];
    }

    // VARIABLE MANAGEMENT METHODS

    /**
     * Gets a variable to be passed to the views
     * 
     * If the variable is a flash variable stored in session, 
     * it is removed from session after being retrieved.
     * 
     * If the variable is not found, the default value is returned.
     * 
     * @param string $varName The name of the variable
     * @param mixed $defaultValue The default value to be returned if the variable is not found
     * @return mixed The value of the variable or the default value if not found
     */
    public function getVariable($varName, $defaultValue = null) {
        if (!isset($this->variables[$varName])) {

            // Check if it's a flash variable in session
            if (isset($_SESSION["flash_variables"]) && isset($_SESSION["flash_variables"][$varName])) {
                // Get the flash variable and remove it from session
                $toret = $_SESSION["flash_variables"][$varName];
                unset($_SESSION["flash_variables"][$varName]);

                return $toret;
            }
            
            // If not found, return default value
            return $defaultValue;
        }

        // Return the variable value
        return $this->variables[$varName];
    }

    /**
     * Establishes a variable to be passed to the views
     * 
     * @param string $varName The name of the variable
     * @param mixed $varValue The value of the variable
     * @param boolean $flash If true, the variable will be stored in session as a flash variable
     */
    public function setVariable($varName, $varValue, $flash=false) {
        $this->variables[$varName] = $varValue;

        // If flash is true, store the variable in session
        if ($flash == true) {
            
            if (!isset($_SESSION["flash_variables"])) {
                $_SESSION["flash_variables"][$varName] = $varValue;
                print_r($_SESSION["flash_variables"]);

            } else {
                $_SESSION["flash_variables"][$varName] = $varValue;
            }

        }
    }

    /**
     * Sets a flash message to be displayed to the user
     * 
     * @param string $flashMessage The flash message to be set
     * @return void
     */
    public function setFlash($flashMessage) {
        $this->setVariable("flash-message", $flashMessage, true);
    }

    /**
     * Gets the flash message to be displayed to the user
     * (and removes it from session if any)
     * 
     * @return string The flash message
     */
    public function popFlash() {
        return $this->getVariable("flash-message", "");
    }

    // RENDERING METHODS


    /**
     * Sets the layout to be used for rendering views
     * 
     * @param string $layoutname The name of the layout
     * @return void
     */
    public function setLayout($layoutname) {
        $this->layout = $layoutname;
    }

    /**
     * Renders an especific view of a especific controller
     * 
     * EJ:
     * $controllerName = projects
     * $viewName = list
     * Selected php file: /app/View/projects/list.php
     * 
     * It uses the layout established in the ViewManager
     * or the default layout if none is established.
     * 
     * @param string $controllerName The name of the controller
     * @param string $viewName The name of the view
     * @return void
     */
    public function render($controllerName, $viewName) {
        include(__DIR__."/../View/pages/".$controllerName."/".$viewName.".php");
        $this->renderLayout();
    }

    /**
     * Sends an HTTP 302 redirect to the given controller and action
     * 
     * @param string $controllerName The name of the controller
     * @param string $action The name of the action inside the controller
     * @param string $queryString An optional query string to be appended to the URL
     * @return void
     */
    public function redirect($controllerName, $action, $queryString = null) {
        header("Location: /TaskGroup/index.php?controller=".$controllerName."&action=".$action.(isset($queryString) ? "&".$queryString : ""));
        die();
    }

    /**
     * Send an HTTP 302 redirect to the referer URL
     * which is stored in the HTTP_REFERER server variable
     * (it is the previous page URL from which the user did the request)
     * 
     * @param string $queryString An optional query string to be appended to the URL
     * @return void
     */
    public function redirectToReferer($queryString = null) {
        header("Location: ".$_SERVER["HTTP_REFERER"].(isset($queryString) ? "&".$queryString : ""));
        die();
    }

    /**
     * Renders the layout established in the ViewManager
     * 
     * It includes the corresponding layout php file from /app/View/layouts/
     * and inside it, the fragments captured will be used.
     */
    public function renderLayout() {

        // save the current fragment before rendering the layout
        $this->moveToFragment("layout");

        // draw the layout (inside it, the fragments will be used)        
        include(__DIR__."/../View/layouts/".$this->layout.".php");

        ob_flush();
    }

    private static $viewManagerSingleton = null;
    public static function getInstance() {
        if (self::$viewManagerSingleton == null) {
            self::$viewManagerSingleton = new ViewManager();
        }
        return self::$viewManagerSingleton;
    }

}

// Force the initialization of the singleton
ViewManager::getInstance();

?>