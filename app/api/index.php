<?php 
// Simple REST router

try {
    require_once(dirname(__FILE__)."/URIDispatcher.php");

    // Obtain all the files and directories of this directory (/rest)
    $files_in_script_dir = scandir(__DIR__);
    // Iterate over the archives
    foreach ($files_in_script_dir as $filename) {
        // Dinamically include Rest files (*Rest.php)
        if (preg_match('/.*REST\\.PHP/', strtoupper($filename))) {
            include_once(__DIR__."/".$filename);
        }
    }

    // Instanciate the dispacher
    $dispatcher = URIDispatcher::getInstance();

    // Enable CORS (allow other sites to use your API)
    $dispatcher->enableCORS('*', 'origin, content-type, accept, authorization');
    
    // Proccess the HTTP request 
    $dispatched = $dispatcher->dispatchRequest();

    // No matching route found
    if (!$dispatched) {
        header($_SERVER['SERVER_PROTOCOL'].'400 Bad Request');
        die ("No diaspatcher found for this request");
    }

} catch (Throwable $ex) {
    header($_SERVER["SERVER_PROTOCOL"].'500 Internal server error');
    header("Content-Type: application/json");
    die(json_encode(array("error" => $ex->getMessage())));
}


?>