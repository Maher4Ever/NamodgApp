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

/**
 * Set the rules for all Namodg fields. This ensures all Namodg needs will be met from the extensions, but doesn't
 * ensure the returned value.
 * 
 * @package Namodg
 */
interface NamodgField {
    
    /**
     * This should return the name of the the data-holder
     * 
     * @return string
     */
    public function getName();
    
    /**
     * This should allow the change the data value
     * 
     * @param mixin
     */
    public function setValue($value);
    
    /**
     * This should return the orignial data value
     * 
     * @return mixin
     */
    public function getValue();

    /**
     * This shuold return an escaped and cleaned data value
     * 
     * @return mixin
     */
    public function getCleanedValue();
   
    /**
     * This should return the type of the data
     * 
     * @return string
     */
    public function getType();
    
    /**
     * This should return a boolean indicating the status of the data
     * 
     * @return boolean
     */
    public function isValid();
    
    /**
     * This should return the value inside a HTML tag
     * 
     * @return string
     */
    public function getHTML();
}

/**
 * The bluebrint for all NamodgField classes. It sets the default behavior of NamodgField objects.
 * 
 * @package Namodg
 */
abstract class NamodgField_Base implements NamodgField {

    /**
     * Field name
     *
     * @var string
     */
    private $_name = NULL;

    /**
     * Field value
     *
     * @var string
     */
    private $_value = NULL;

    /**
     * Field Options
     *
     * @var array
     */
    private $_options = array();

    /**
     * Field validation error
     *
     * @see $this->_setValidationError()
     * @var string
     */
    private $_validatonError = NULL;

    /**
     * Initialize the field object
     *
     * @param array $name
     * @param array $options
     */
    public function __construct($name, $options = array()) {
        $name = trim($name);
        $this->_name = empty($name) ? uniqid( $this->getType() . '_' ) : $name;
        $this->_addDefaultOptions(array(
            'id' => NULL,
            'class' => NULL,
            'required' => FALSE,
            'label' => NULL,
            'title' => NULL,
            'send' => TRUE
        ));
        $this->_setOptions($options);
    }

    /**
     * Name getter
     *
     * @return string
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * Value setter
     *
     * @param string $value
     * @return $this Allows chaining
     */
    public function setValue($value) {
        $this->_value = $value;
        return $this;
    }

    /**
     * Value getter
     *
     * @return string
     */
    public function getValue() {
        return $this->_value;
    }

    /**
     * Field type gette, based on it's name
     * 
     * @return string
     */
    public function getType() {
        $class = get_class($this);
        return (string)strtolower( substr($class, 12) ); // 'NamodgField_' == 12
    }

    /**
     * Field options setter
     *
     * @param string $id option id
     * @param string $value
     * @return $this Allows chaining
     */
    public function setOption($id, $value) {
        $this->_options[$id] = $value;
        return $this;
    }

    /**
     * Field option getter
     *
     * @param string $id
     * @return string
     */
    public function getOption($id) {
        return $this->_options[$id];
    }

    /**
     * Field error setter, changes the current field error to the last one
     * 
     * @param $errorID
     * @return $this Allows chaining
     */
    protected function _setValidationError($errorID) {
        $this->_validatonError = $errorID;
        return $this;
    }

    /**
     * Field error getter
     * 
     * @return string
     */
    public function getValidationError() {
        return $this->_validatonError;
    }

    /**
     * Allows to add extra default options
     * 
     * @param array $options
     * @return $this Allows chaining
     */
    protected function _addDefaultOptions($options) {
        if ( is_array($options) ) {
            $this->_options = array_merge($this->_options, $options);
        }
        return $this;
    }

    /**
     * Mereges the passed options array with the default options
     *
     * @param array $options
     * @return $this Allows chaining
     */
    private function _setOptions($options) {
        $this->_options = array_merge($this->_options, $options);
        return $this;
    }

}