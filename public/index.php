<?php
// file: /public/index.php

/**
 * Default controller if any controller is passed in the URL
 */
define("DEFAULT_CONTROLLER", "auth"); // Rechanged to "dashboard"

/**
 * Default action if any action is passed in the URL
 */
define("DEFAULT_ACTION", "login");  // Rechanged to "index"

/**
 * Main Router
 * Single entry-point for all requests of the MVC implementation
 * 
 * This router will create an instance of the corresponding controller,
 * based on the "controller" parameter and call the corresponding method,
 * based on the "action" parameter.
 * 
 * The rest of GET or POST parameters should be handled 
 * by the controller itself.
 * 
 * Parameters:
 * <ul>
 * <li>controller: The controller name (via HTTP GET)</li>
 * <li>action: The name inside the controller (via HTTP GET)</li>
 * </ul>
 * 
 * @return void
 * 
 */
function run() {

    /* Set to their default values if the controller or
    the action aren't already set on the URL*/
    try {
        if(!isset($_GET["controller"])) {
            $_GET["controller"] = DEFAULT_CONTROLLER;
        }

        if(!isset($_GET["action"])) {
            $_GET["action"] = DEFAULT_ACTION;
        }

        //Instantiate the corresponding controller
        $controller = loadController($_GET["controller"]);
    
        //Call the corresponding action
        $actionName = $_GET["action"];
        $controller->$actionName();
    
    } catch(Exception $ex) {
        // Handle exception
        die("An exception ocurred.".$ex->getMessage());
    }
}

/**
 * Load the required controller file and create the controller instance
 * 
 * @param string $controllerName The controller name found in the URL
 * @return Object A Controller instance
 */
function loadController($controllerName) {

    $controllerClassName = getControllerClassName($controllerName);

    if(!defined('BASE_URL')) {
        require_once(__DIR__.'/../config/paths.php');
    }

    echo "BASE URL: ". BASE_URL."<br>";
    echo "Loading controller: ". $controllerClassName . "<br>";
    echo "From file: ". BASE_URL.'/app/Controller/'.$controllerClassName.'.php' . "<br>";

    require_once(BASE_URL.'/app/Controller/'.$controllerClassName.'.php');
    return new $controllerClassName();
}

/**
 * Standarize the controller name of the URL to his
 * corresponding class name
 * 
 * $controllerName = "projects" --> "ProjectsController"
 * 
 * @param $controllerName The name of the controller found in the URL
 * @return string The controller class name
 */
function getControllerClassName($controllerName) {
    return strtoupper(substr($controllerName, 0, 1)).substr($controllerName, 1)."Controller";
}

// Execute the routing of this index.php (his run method) 
run();
?>