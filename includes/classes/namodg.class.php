<?php

/*
 * Include dependencies
 */
require_once 'namodg.defaultRenderers.class.php';
require_once 'namodg.defaultFields.class.php';

/**
 * Namodg - Ajax Forms Generator
 *
 * @desc Namodg allows developers to make ajax-driven forms easily. It uses OOP aproach,
 *       which means developers has to write less code!
 * @author Maher Salam <admin@namodg.com>
 * @link http://namodg.com
 * @copyright Copyright (c) 2010-2011, Maher Salam
 *
 * Dual licensed under the MIT and GPL licenses:
 *   @license http://www.opensource.org/licenses/mit-license.php
 *   @license http://www.gnu.org/licenses/gpl.html
 */
class Namodg {

    /**
     * Namodg current version
     */
    const version = '1.4';
    
    /**
     * The key is used to encrypt/decrypt data
     * 
     * @var string
     */
    private $_key = NULL;
    
    /**
     * Form Attributes Container
     *
     * @var array
     */
    private $_attrs = array();

    /**
     * Namodg fields objects container
     *
     * @var array
     */
    private $_fields = array();

    /**
     * Validation Errors Container
     *
     * @var array
     */
    private $_validationErrors = array();
    
    /**
     * Fatal errors will be added here when the suppressErrors option is set
     * 
     * @var array
     */
    private $_fatalErrors = array();

    /**
     * Initialize Namodg
     * 
     * @param array $congif form config
     * @param boolean $suppressErrors allows developers to handle namodg's fatal errors in any way they want
     */
    public function __construct($config = array(), $suppressErrors = false) {
        
        if ( ! is_bool($suppressErrors) ) {
            $suppressErrors = false;
        }
        
        try {

            if ( ! isset ($config['key']) || empty ($config['key']) ) {
                throw new NamodgException('no_key');
            } else {
                $this->_key = $config['key'];
            }
            
            unset ($config['key']);
            
            $this->_setAttrs($config);
            
        } catch (NamodgException $e) {
            
            if ( $suppressErrors ) {
                $this->_fatalErrors[ $e->getID() ] = $e->getMessage();
            } else {
                echo 'Namodg error: ', $e->getMessage();
                exit(1);
            }
            
        }
        
    }
    
    public function getAttr($id) {
        return $this->_attrs[$id];
    }
    
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
    
    public function getFields() {
        return $this->_fields;
    }

    /**
    * Checks to see if the data was sent and that if it contains valid NamodgField Objects.
    *
    * @return boolean
    */
    public function canBeProcessed() {

        $method = $GLOBALS['_' . $this->_attrs['method']];

        // Stop if there is no request or if the request doesn't contain namodg data
        if ( ! $method && ! isset($method['namodg_fields']) ) {
            return false;
        }

        // Only add fields from the request if there are none to save process time when the display and process page are the same
        if ( empty($this->_fields) ) {
            $fields = unserialize($this->_decrypt($method['namodg_fields']));

            if ( ! is_array($fields) ) {
                return false;
            }

            foreach ($fields as $field) {
                if ( ! ($field instanceof NamodgField) ) {
                    return false;
                }
            }
            $this->_fields = $fields;
        }
        
        // Remove the namodg fields container
        unset($method['namodg_fields']);
        
        // Store the data from the request to be used later
        foreach ($method as $name => $value) {
            $field = $this->getField($name);
            if ( $field ) {
                $field->setValue($value);
            }
        }
        
        return true;
    }

    /**
     * Validates the fields and sets an error if a field is not valid
     *
     */
    public function validate() {
        foreach ( $this->_fields as $field) {
            if ( ! $field->isValid() ) {
                $this->_addValidationError( $field->getName(), $field->getOption('label'), $field->getValidationError());
            }
        }
    }

    /**
     * Checks to see if there are no errors, which means the data is valid.
     * The 'validate' method must be run before this one though!
     * 
     * @return boolean
     */
    public function isDataValid() {
        return ( isset($this->_validationErrors) && count($this->_validationErrors) !== 0 ) ? false : true;
    }

    /**
     * Errors getter method
     * 
     * @return array
     */
    public function getValidationErrors() {
        return (array)$this->_validationErrors;
    }

    /**
     * Fatal errors getter method
     * 
     * @return array
     */
    public function getFatalErrors() {
        return $this->_fatalErrors;;
    }

    public function __toString() {
        $form = new NamodgFormRenderer( $this->getFields() , $this->_key);
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
     * This makes new NamodgField classes work without adding them to the script's core,
     * It tries to check if the called function is a NamodgField class and if it is, it runs it. Otherwise
     * it triggers an error
     *
     * @param string $function
     * @param array $arguments
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
    }

    /**
     * Makes sure the field class implements NamodgDataHolder interface, or lets PHP throw an error.
     * This is just a way to stay save.
     *
     * @param NamodgDataHolder $field
     */
    private function _addField(NamodgDataHolder $field) {
        $this->_fields[$field->getName()] = $field;
    }

    /**
     * Merge the passed config array with the default config
     * 
     * @param array $attrs
     * @return array the new config array
     */
    private function _setAttrs($attrs) {
        
        if ( ! is_array($attrs) ) {
            throw new NamodgException('config_not_array');
        }
        
        $defaults = array (
            'id' => NULL,
            'class' => NULL,
            'method' => 'POST',
            'url' => $_SERVER['SCRIPT_NAME'],
        );

        $this->_attrs = array_merge( $defaults, array_map('trim', $attrs) );
        
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
            $keychar = substr($this->_key, ($i % strlen($this->_attrs['key']))-1, 1);
            $char = chr(ord($char)-ord($keychar));
            $result.=$char;
        }
        return $result;
    }

    /**
     * Addes validation errors to the errors array
     * 
     * @param string $fieldName
     * @param string $label
     * @param string $error
     */
    private function _addValidationError($fieldName, $label, $error) {
        if ( ! isset($this->_validationErrors) ) {
            $this->_validationErrors = array();
        }
        
        $this->_validationErrors[$fieldName] = array(
            'fieldLabel' => $label,
            'error' => ( isset($this->_phrases['validation'][$fieldName][$error]) && ! empty($this->_phrases['validation'][$fieldName][$error]) ) ? $this->_phrases['validation'][$fieldName][$error] : $this->_phrases['validation'][$error]
        );
    }
}

class NamodgException extends Exception {
    
}