<?php

/**
 * Namodg - Form Generatorr 
 * ========================
 * 
 * Namodg is a class which allows to easily create, render, validate and process forms
 * 
 * @author Maher Sallam <admin@namodg.com>
 * @link http://namodg.com
 * @copyright Copyright (c) 2010-2011, Maher Sallam
 *
 * Dual licensed under the MIT and GPL licenses:
 *   @license http://www.opensource.org/licenses/mit-license.php
 *   @license http://www.gnu.org/licenses/gpl.html
 */

/*
 * Include dependencies
 */
require_once 'class.namodg.defaultRenderers.php';
require_once 'class.namodg.defaultFields.php';

/**
 * Namodg is the main class to interact with the form. It offers an API that allows to 
 * add fields, maniuplate them, validate them and render the form with or without them.
 * 
 * Usage Example: 
 * <code>
 *   $form = new Namodg('da#das4243');
 *   $form->addTextField('name');
 *   echo $form;
 * </code>
 * 
 * @package Namodg
 */
class Namodg {

    /**
     * Namodg current version
     */
    const version = '1.4';
    
    /**
     * The key is used to encrypt/decrypt data
     * 
     * @static string
     */
    private static $_key = NULL;
    
    /**
     * Form Attributes Container
     *
     * @var array
     */
    private $_attrs = array();

    /**
     * NamodgField objects container
     *
     * @var array
     */
    private $_fields = array();

    /**
     * Validation Errors container
     *
     * @var array
     */
    private $_validationErrors = array();
    
    /**
     * Namodg has a $suppressErrors option. If it's set to true, any fatal error 
     * will be assigned to the following var.
     * 
     * @var string
     */
    private $_fatalError = NULL;

    /**
     * Initialize Namodg, this includes validating all configs.
     * 
     * @param array $config form config
     * @param boolean $suppressErrors allows developers to handle namodg's fatal errors in any way they want
     */
    public function __construct($config = array(), $suppressErrors = false) {
        
        // Default to showing errors if the passed option is not a boolean
        if ( ! is_bool($suppressErrors) ) {
            $suppressErrors = false;
        }
        
        try {
            
            if ( is_array($config) ) { // Then advanced configuration mode
                
                if ( ! isset ($config['key']) || empty ($config['key']) ) {
                    throw new NamodgException('no_key');
                } else {
                    self::$_key = $config['key'];
                }  
                
                $this->_setAttrs($config);
            
            } elseif ( is_string($config) ) { // Simple configuration mode
   
                self::$_key = $config;
                $this->_setAttrs();
                
            } else { // No configurations
                
                throw new NamodgException('no_key');
   
            }
            
            self::$_key = trim(self::$_key);
            
            // Key should be 10 chars log at least
            if ( empty(self::$_key) || strlen(self::$_key) < 10 ) {
                throw new NamodgException('weak_key');
            }
                
        } catch (NamodgException $e) {
            
            if ( $suppressErrors ) {
                $this->_fatalError = $e->getMessage();
            } else {
                exit('Namodg error: ' . $e->getError());
            }
            
        }
        
    }
    
    /**
     * Attribute getter method
     * 
     * @param string $id
     * @return string
     */
    public function getAttr($id) {
        return $this->_attrs[$id];
    }
    
    /**
     * All attributes getter method
     * @return array
     */
    public function getAttrs() {
        return $this->_attrs;
    }

    /**
     *  Fields getter method
     *
     * @param string the name of the field
     * @return NamodgField
     */
    public function getField($name) {
        return $this->_fields[$name];
    }
    
    /**
     * All fields getter method
     * 
     * @return array
     */
    public function getFields() {
        return $this->_fields;
    }

    /**
     * This method can be used to check if a Namodg form is submited to a page and it can be processed
     * 
     * @return boolean
     */
    public function canBeProcessed() {

        $method = $GLOBALS['_' . $this->getAttr('method')];

        // Stop if there is no request or if the request doesn't contain namodg data
        if ( ! $method && ! isset($method['namodg_fields']) ) {
            return false;
        }

        /*
         * Only add fields from the request if there are none to save process time when the fields and process logic are on the same page.
         * Note: $this->getFields() wasn't used here because empty() doesn't accept function's returns as a param.
         */
        if ( empty($this->_fields) ) {
            
            // We don't want to show errors when something goes wrong, because it will fail and false will be returned!
            $fields = @unserialize($this->_decrypt($method['namodg_fields']));

            if ( ! is_array($fields) ) {
                return false;
            }
            
            // Validate the objects
            foreach ($fields as $field) {
                if ( ! ($field instanceof NamodgField) ) {
                    return false;
                }
            }
            
            // Add the fields to this instance (without data)
            $this->_fields = $fields;
        }
        
        // Remove the namodg fields container (saves memory)
        unset($method['namodg_fields']);
        
        // Add sent data to the fields
        foreach ($method as $name => $value) {
            $field = $this->getField($name);
            
            // Only add fields' data and ignore the rest
            if ( $field ) {
                $field->setValue($value);
            }
        }
        
        // Yes, the form can be processed
        return true;
    }

    /**
     * Validates the fields and adds validation errors to thier container
     * 
     * @return $this Allows chaining
     */
    public function validate() {
        foreach ( $this->_fields as $field) {
            if ( ! $field->isValid() ) {
                $this->_addValidationError( $field->getName(), $field->getValidationError());
            }
        }
        return $this;
    }

    /**
     * Checks to see if there are no validation errors, which means the data is valid.
     * Note: $this->validate() must be run before this one to get the right result!
     * 
     * @return boolean
     */
    public function isDataValid() {
        return ( isset($this->_validationErrors) && count($this->_validationErrors) !== 0 ) ? false : true;
    }

    /**
     * Validation errors getter method
     * 
     * @return array
     */
    public function getValidationErrors() {
        return empty($this->_validationErrors) ? FALSE : $this->_validationErrors;
    }

    /**
     * Fatal error getter method
     * 
     * @return array
     */
    public function getFatalError() {
        return $this->_fatalError;
    }
    
    /**
     * Prints the form HTML when naomdg object is treated as a var
     * 
     * @return string the form HTML
     */
    public function __toString() {
        $form = new NamodgFormRenderer( $this->getFields() , self::$_key);
        $form->addAttr('action', $this->_attrs['url']);
        $form->addAttr('method', $this->_attrs['method']);

        if ( $this->_attrs['id'] ) {
            $form->setID( $this->_attrs['id'] );
        }

        if ( $this->_attrs['class'] ) {
            $form->addClass( $this->_attrs['class'] );
        }

        return $form->render();  
    }
    
    /**
     * This function allows the addition of fields to the form without instantiating a new object (must implement NamodgField) manually.
     * It tries to check if the called function is a class which implements NamodgField interface. If it does, it runs it. Otherwise
     * it triggers an error.
     * 
     * @param string $function
     * @param array $arguments
     * @return $this Allows chaining
     */
    public function  __call($function, $arguments) {

        // the function must start with 'add'
        if ( ! preg_match('/^add/', $function) ) {
            trigger_error('Call to undefined method ' . __CLASS__ . '::' . $function, E_USER_ERROR);
        }

        // make the class name, remove the 'add' part
        $class = 'NamodgField_' . substr($function, 3);

        // Replace the spaces in the field name with underscores
        if ( preg_match('/\s+/', $arguments[0]) ) {
            $arguments[0] = preg_replace('/\s+/', '_', $arguments[0]);
        }

        // Create a new object with unknows arguments
        $field = new ReflectionClass($class);
        
        // Pass the arguments to the class and add it to the fields array
        $this->_addField( $field->newInstanceArgs($arguments) );
        
        return $this;
    }

    /**
     * Makes sure the field class implements NamodgField interface, or lets PHP throw an error.
     * This is just a way to stay save. The trick is to use the typecasted parameter.
     *
     * @param NamodgField $field
     */
    private function _addField(NamodgField $field) {
        $this->_fields[$field->getName()] = $field;
    }

    /**
     * Settes the attributes of this form and validates them as well. If no attrs are passed,
     * it used the default attrs. This is handy for the simple configuration mode of Namodg.
     * 
     * @param array $attrs
     */
    private function _setAttrs($attrs = NULL) {
        
        $defaults = array (
            'id' => NULL,
            'class' => NULL,
            'method' => 'POST',
            'url' => $_SERVER['SCRIPT_NAME'],
        );

        $this->_attrs = $attrs ? array_merge( $defaults, array_map('trim', $attrs) ) : $defaults;
        
        if ( strtoupper($this->_attrs['method']) !== 'POST' && strtoupper($this->_attrs['method']) !== 'GET' ) {
            throw new NamodgException('method_not_valid');
        }
    }

    /**
     * Basic decryption method
     *
     * @param string $str
     * @return string
     */
    private static function _decrypt($str){
        $str = base64_decode($str);
        $result = '';
        for($i=0, $length = strlen($str); $i<$length; $i++) {
            $char = substr($str, $i, 1);
            $keychar = substr(self::$_key, ($i % strlen(self::$_key))-1, 1);
            $char = chr(ord($char)-ord($keychar));
            $result.=$char;
        }
        return $result;
    }

    /**
     * Addes validation errors to the errors array
     * 
     * @param string $fieldName
     * @param string $error
     */
    private function _addValidationError($fieldName, $error) {
        $this->_validationErrors[ $fieldName ] = $error;
    }
}

/**
 * Namodg Exception 
 * 
 * This is a special exception, because it can provide a string explaning the error if asked to.
 * 
 * @package Namodg
 */
class NamodgException extends Exception {
    
    public function getError() {
        $errors = array(
            'no_key' => 'No key is passed to namodg.',
            'weak_key' => 'The key must be at least 10 characters long.',
            'method_not_valid' => 'The method configuration must be one of two values: POST or GET.'
        );
        
        return in_array($this->getMessage(), array_flip($errors)) ? $errors [ $this->getMessage() ] : $this->getMessage();
    }
        
}