<?php
// file: /app/core/ValidationException.php

/**
 * Class ValidationException
 * 
 * A Exception class to represent validation errors
 * which are contained in a array of errors.
 * 
 * Errors should be indexed by the field name
 * of its corresponding form field.
 */
class ValidationException extends Exception {

    /**
     * @var mixed An array of validation errors indexed by field name
     */
    private $errors = array();

    /**
     * Constructor
     * @param array $errors An array of validation errors indexed by field name
     * @param string $msg An optional message for the exception
     */
    public function __construct(array $errors, $msg=NULL) {
        parent::__construct($msg);
        $this->errors = $errors;
    }
    
    /**
     * Gets the validation errors
     * 
     * @return mixed An array of validation errors indexed by field name
     */
    public function getErrors() {
        return $this->errors;
    }
        
}

?>