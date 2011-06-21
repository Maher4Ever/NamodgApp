<?php

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

/**
 * Set the rules for all NamodgField classes.
 *
 * @desc This ensures all Namodg needs will be met from the extensions, but doesn't
 * ensure the returned value.
 * 
 */
interface NamodgDataHolder {

    public function getName();

    public function setValue($value);

    public function getValue();

    public function getCleanedValue();
        
    public function getType();

    public function isValid();

    public function getHTML();
}

/**
 * The bluebrint for all NamodgField classes.
 *
 * @desc Set the default behavior of NamodgField objects.
 */
abstract class NamodgField implements NamodgDataHolder {

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
        $this->_name = (string)$name;
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
     */
    public function setValue($value) {
        $this->_value = $value;
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
     */
    public function setOption($id, $value) {
        $this->_options[$id] = $value;
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
     */
    protected function _setValidationError($errorID) {
        $this->_validatonError = $errorID;
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
     */
    protected function _addDefaultOptions($options) {
        if ( is_array($options) ) {
            $this->_options = array_merge($this->_options, $options);
        }
    }

    /**
     * Mereges the passed options array with the default options
     *
     * @param array $options
     */
    private function _setOptions($options) {
        $this->_options = array_merge($this->_options, $options);
    }

}