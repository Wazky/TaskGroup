<?php

/**
 * Class URIDispatcher (Singleton)
 * 
 * Class to map requests URI to function callbacks.
 * 
 * Expected use:
 *  1. Get the instance and call to map(...) method
 *   assign callback functions to specific url aptterns and http method.
 * 
 *  2. Call the dispatchRequest(...) method in order to process
 *   the current requets by finding a matching mapping previously provided.
 * 
 * @author lipido <lipido@gmail.com>
 * @author isma <ismaelaqua@hotmail.com>
 */
class URIDispatcher {

// VARIABLES

    /**
     * Reference to an array of mapping specififcations
     * 
     * @var array
     */
    private $mappings = array();

// SINGLETON

    // Singleton instance of URIDispatcher
    private static $uri_dispatcher_instance = null;

    /**
     * Return the singleton instance of URIDispatcher.
     *  (Create if if not created yet) 
     * 
     * @return URIDispatcher The singleton instance
     */
    public static function getInstance() {
        
        if (self::$uri_dispatcher_instance === null) self::$uri_dispatcher_instance = new URIDispatcher();
        
        return self::$uri_dispatcher_instance;
    }


    public function __construct() {
        $this->cors = false;
    }

    /**
     * Registers a route mapping an HTTP request to a function callback.
     * 
     * This method creates a route entry that will be matched agaisnt incoming requests.
     * When a request matches both the HTTP method and URL pattern, the associated
     * callback is invoked with extracted path parameters and optional JSON payload.
     * 
     * ### URL Pattern Syntax:
     * 
     * - Use `$1`, `$2`, etc. as positional placeholders to capture path segments
     * - Captured values are passed to the callback in numerical order
     * - Static segments must match exactly
     * 
     * ### Example:
     * ```
     * Patern:  /user/$2/projects/$1
     * Request: /user/maria_garcia/projects/13
     * Result:  callback(13, "maria_garcia") // $1=13, $2="maria_garcia"
     * ```
     * ### JSON Request Body Handling:
     * When `parseJSONInput` is `true` and the request has `Content-Type: application/json`
     * the parsed JSON object is appened as the last parameter to the callback.
     * 
     * ### Method Chainig:
     * Returns `$this` to allow fluent interface calls:
     * ```php
     * $dispatcher->map('GET', '/users', $callback)
     *            ->map('POST', '/users', $callback);
     * ```
     * 
     * @param string $httpMethod The required HTTP method (GET, POST, PUT, DELETE, etc.)
     * @param string $urlPattern The pattern to match the current request agaisnt
     * @param callback $callback The $<number> matched values
     * @param boolean $parseJSONInput Wheter a request body of type json should be parsed
     * 
     * @return self Returns $this for method chaining
     */
    public function map($httpMethod, $urlPattern, $callback, $parseJSONInput  = true) {

        // Add new array with the fields (httpMethod, urlPattern, callback, parseJSONInput)
        $this->mappings[] = [
            "httpMethod" => $httpMethod,
            "urlPattern" => $urlPattern,
            "callback" => $callback,
            "parseJSONInput" => $parseJSONInput
        ];

        return $this;
    }


    /**
     * Processes the current HTTP request by finding and executing a matching route.
     * 
     * Iterates through all registered route mappings to find one that matches both
     * the HTTP method and URL pattern of the current request.
     * When a match is found:
     * 
     * 1. Extracts URL parameters from the path (e.g., $1, $2 values)
     * 2. Parses JSON request body if applicable
     * 3. Invokes the registered callback with all parameters
     * 4. Sets CORS heaeders when enabled
     * 
     * ### Special Behaviors:
     * - **CORS Preflight**: OPTIONS request trigger collection of allowed methods
     * - **JSON Parsin**: Automatic parsing of `application/json` request bodies
     * - **Early Exit**: Returns `true` on first successful dispatch
     * 
     * @return bool True if a matching route was found and executed, false otherwise
     */
    public function dispatchRequest() {
        $dispatchAsCORS = false;
        $allowedMethods = array();

        // Iterate over the route mappings
        foreach ($this->mappings as $mapEntry) {
            
            $parameters = array();
            
            // Check if current requeset matches this route definition
            if ($this->matchRequest($mapEntry['httpMethod'], $mapEntry['urlPattern'], $parameters)) {

                // --- Handle CORS Preflight (OPTIONS request) ---
                if (($this->cors === true) && ($_SERVER['REQUEST_METHOD'] === 'OPTIONS')) {
                    $dispatchAsCORS = true;
                    $allowedMethods[] = strtoupper($mapEntry["httpMethod"]);
                
                } 

                // --- Normal Request Processing ---
                // Parse JSON request body if applicable
                if ($mapEntry['parseJSONInput'] && isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {                        
                    
                    $jsonInput = file_get_contents("php://input");
                    error_log("JSON raw input: ".$jsonInput);

                    $jsonData = json_decode($jsonInput);
error_log("JSON decode success: " . (json_last_error() === JSON_ERROR_NONE ? 'YES' : 'NO'));
error_log("JSON decode error message: " . json_last_error_msg());
error_log("JSON decoded type: " . gettype($jsonData));
                    if (!is_array($parameters)) $parameters = array();

                    array_push($parameters, $jsonData);

                    //$parameters[] = json_decode(file_get_contents("php://input"));
                }

                error_log("Params for callback: ".print_r($parameters, true));

                // Set CORS headers for actual requests
                if ($this->cors === true) {
                    header('Access-Control-Allow-Origin: '.$this->allowedOrigin);
                }

                // Execute the registerred callback with extracted parameters
                call_user_func_array($mapEntry['callback'], $parameters);

                // Request successfully dispatched 
                return true;
                
            }
        }

        // --- Handle CORS Preflight Response ---
        if ($dispatchAsCORS) {
            header('Access-Control-Allow-Origin: '.$this->allowedOrigin);
            header('Access-Control-Allow-Headers:'.$this->allowedRequestHeaders);
            header('Access-Control-Allow-Methods: '.implode(',', $allowedMethods).',OPTIONS');
            
            return true;    // Preflight handled successfully
        }

        // No matching route found
        return false;
    }

    /**
     * Determines if the current HTTP request matches a given route pattern.
     * 
     * Compares the request method and URL agaisnt a specified pattern, extracting
     * path parameters when placeholderes ($1, $2, etc.) are used. E
     * Enables automatic OPTIONS method handling when CORS is enabled.
     * 
     * ### Parameter Extraction:
     * Pattern: `/user/$2/projects/$1`,
     * Request: `/user/marria_garcia/projects/13`
     * Result `[1 => "13", 2 => "maria_garcia"]
     * Callback: `callback("13","maria_garcia")`
     * 
     * ### CORS Support:
     * When CORS si enabled, OPTIONS requests are automatically allowed for
     * preflight checks, regardless of explicitr route definitions.
     * 
     * @param string $httpMethod Required HTTP method (GET, POST, etc.)
     * @param string $urlPattern URL pattern with optional placeholders
     * @param string &$matchedParameters Output array for extracted parameters
     * 
     * @return boll True if request matches pattern, false otherwise
     */
    public function matchRequest($httpMethod, $urlPattern, &$matchedParameters = array()) {

        // Validate HTTP method, with CORS perflight exception
        if ($_SERVER['REQUEST_METHOD'] != strtoupper($httpMethod) && ($this->cors == false || $_SERVER['REQUEST_METHOD'] != 'OPTIONS')) {
            return false;
        }

        // Extract clean request path:
        // Example REQUEST_URI="/rest/api/users", PHP_SELF="/rest/index.php"
        // Result: "/api/users"
        $script_name_length = strlen($_SERVER['PHP_SELF']);
        $basename_length = strlen(basename($_SERVER['PHP_SELF']));

        $path = substr($_SERVER['REQUEST_URI'], ($script_name_length - $basename_length - 1)); 
        $path = parse_url($path)['path'];


        // Split path and pattern to compare by segments
        $pathTokens = explode("/", $path);
        $patternTokens = explode("/", $urlPattern);

        // Check if different number of segments between path and pattern
        if (sizeof($pathTokens) != sizeof($patternTokens)) {
            return false;
        }

        // Compare each segment, extracting parameters from $n placeholders        
        $matchedParameters = array();
        for ($i = 0; $i < sizeof($pathTokens); $i++) {

            $pathSegment = $pathTokens[$i];
            $patternSegment = $patternTokens[$i];

            // Continue if segments match exactly
            if ($pathSegment === $patternSegment) continue;

            // Pattern segment is a placeholder ($1, $2, etc.)
            if (preg_match('/\$([0-9]+?)/', $patternSegment, $matches) == 1) {
                $matchedParameters[$matches[1]] = $pathTokens[$i];
            
            } else {    // Pattern segment mismatch the path segment 
                return false;
            }
        }

        // If placeholders, order by number to use them for callback
        if (sizeof($matchedParameters) > 0) {
            krsort($matchedParameters);
        }
        
        // HTTP request match the route pattern
        return true;
    }

    public function enableCORS($allowedOrigin, $allowedRequestHeaders) {
        $this->cors = true;
        $this->allowedOrigin = $allowedOrigin;
        $this->allowedRequestHeaders = $allowedRequestHeaders;
    }


}

?>