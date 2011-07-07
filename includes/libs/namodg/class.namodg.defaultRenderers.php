<?php

/**
 * Namodg - Form Generator 
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
require_once 'class.namodg.renderer.php';

/**
 * This class can be used to render a HTML form with it's fields.
 * It also offers an API to get parts of the form's HTML so that they can be used elsewhere.
 * 
 * @package Namodg
 * @subpackage NamodgRenderer
 */
class NamodgFormRenderer extends NamodgRenderer_Base {
    
    /**
     * NamodgField objects container
     *
     * @var array
     */
    private $_fields = NULL;
    
    /**
     * The key is used to encrypt the hidden Namodg field.
     * This field has a serialized version of the NamodgField objects
     *
     * @var string
     */
    private static $_key = NULL;
    
    /**
     * Initialize the form renderer
     *
     * @param array $fields NamodgField objects
     * @param string $key 
     */
    public function __construct($fields, $key) {
        parent::__construct('form');
        $this->_fields = $fields;
        self::$_key = $key;
    }
    
    /**
     * This allows to get the HTML of the opening form tag
     *  
     * @return string 
     */
    public function getOpeningHTML() {
        $html = '<form ';
        foreach ( $this->getAllAttrs() as $attr => $value) {
            $html .= $attr . '="' . $value . '" ';
        }
        $html .= '>' . PHP_EOL;
        
        // W3C doesn't allow inputs inside forms directly
        $html .= "\t<div>" . PHP_EOL;
        
        return $html;
    }

    /**
     * This allows to get the HTML of the closing form tag
     *
     * @return string
     */
    public function getClosingHTML() {
        
        // Add namodg hidden field
        $html = "\t\t<input type='hidden' name='namodg_fields' value='" . self::_encrypt( serialize($this->_getFields()) ) . "'>" . PHP_EOL;
        
        // Close the div
        $html .= "\t</div>" . PHP_EOL;
        
        // Close the form
        $html .= '</form>' . PHP_EOL;
        
        return $html;
    }
    
    /**
     * Renders the form's HTML with it's fields
     * 
     * @return string
     */
    public function render() {
       
        // Form beginning tag
        $html = $this->getOpeningHTML();
                
        // Build fields
        foreach ( $this->_getFields() as $field ) {     
            if ( $field->getOption('label') ) {
                $labelHTML = "\t\t";
                $labelHTML .= '<label ' . ( $field->getOption('id') ? 'for="' . $field->getOption('id') . '"' : '' ) . ' >';
                $labelHTML .= $field->getOption('label');
                $labelHTML .= '</label>' . PHP_EOL;
                
                $html .= $labelHTML;
            }
            
            $html .= "\t\t" . $field->getHTML() . PHP_EOL . PHP_EOL;
        }
                
        // Close form html
        $html .= $this->getClosingHTML();
        
        return $html;
    }
    
    /**
     * All fields getter method
     * 
     * @return array
     */
    protected function _getFields() {
        return $this->_fields;
    }

    /**
     * Basic encryption method
     *
     * @param string $str
     * @return string
     */
    protected static function _encrypt($str){
      $result = '';      
      for($i=0, $length = strlen($str); $i<$length; $i++) {
         $char = substr($str, $i, 1);
         $keychar = substr(self::$_key, ($i % strlen(self::$_key))-1, 1);
         $char = chr(ord($char)+ord($keychar));
         $result.= $char;
      }
      return base64_encode($result);
    }
}

/**
 * This is a general field renderer
 * 
 * @package Namodg
 * @subpackage NamodgRenderer
 */
class NamodgFieldRenderer extends NamodgRenderer_Base {
    
    /**
     * NamodgField object container
     *
     * @var NamodgField_Base
     */
    private $_field = NULL;
    
    /**
     * Initialize the field renderer
     * 
     * @param string $tag
     * @param NamodgField_Base $field 
     */
    public function __construct($tag, NamodgField_Base $field) {
        parent::__construct($tag);
        $this->_field = $field;
    }
    
    /**
     * Helper method, allows to add validation rules to the field.
     * The added attr can be used by client-side languages to validate the form before the submission.
     * 
     * @param string $rule
     * @return NamodgFieldRenderer 
     */
    public function addValidationRule($rule) {
        if ( $this->getAttr('data-validation') ) {
            $this->addAttr('data-validation', $this->getAttr('data-validation') . ' ' . $rule);
        } else {
            $this->addAttr('data-validation', $rule);
        }
        return $this;
    }
    
    /**
     * Renders the field's HTML
     * 
     * @return string
     */
    public function render() {
        $html = '<' . $this->getTag() . ' ';

        $this->addAttr('name', $this->_getField()->getName());

        if ( $this->_getField()->getOption('id') ) {
            $this->setID( $this->_getField()->getOption('id') );
        }

        if ( $this->_getField()->getOption('class') ) {
            $this->addClass( $this->_getField()->getOption('class') );
        }

        if ( $this->_getField()->getOption('required') ) {
            $this->addValidationRule('required');
        }

        if ( $this->_getField()->getOption('title') ) {
            $this->addAttr('title', $this->_getField()->getOption('title'));
        }

        foreach ($this->getAllAttrs() as $attr => $value) {
            $html .= $attr . '="' . $value . '" ';
        }

        $html .= $this->_getClosingHTML();

        return $html;
    }
    
    /**
     *  Field getter method
     *
     * @return NamodgField_Base
     */
    protected function _getField() {
        return $this->_field;
    }
    
    /**
     * This allows to get the closing HTML of the field, based on the tag type.
     * 
     * @return string
     */
    protected function _getClosingHTML() {
        switch ( $this->getTag() ) {
            case 'input':
                return 'value="' . $this->_getField()->getValue() . '">';
            case 'textarea':
                return ' cols="30" rows="10">' . $this->_getField()->getValue() . '</textarea>';
            case 'button':
                return 'value="' . $this->_getField()->getValue() . '">' . $this->_getField()->getValue() . '</button>';
            default:
                return 'value="' . $this->_getField()->getValue() . '">';
        }
    }

}

/**
 * This renders the Namodg Select Field
 * 
 * @package Namodg
 * @subpackage NamodgRenderer
 */
class NamodgSelectRenderer extends NamodgFieldRenderer {
    
    /**
     * Initialize the select field renderer
     * 
     * @param NamodgField_Select $field 
     */
    public function __construct(NamodgField_Select $field) {
        parent::__construct('select', $field);
    }
    
    /**
     * Renders the select field's HTML
     * 
     * @return string
     */
    public function render() {
        $selectField = '<' . $this->getTag() . ' ';

        $this->addAttr('name', $this->_getField()->getName());

        if ( $this->_getField()->getOption('id') ) {
            $this->setID( $this->_getField()->getOption('id') );
        }

        if ( $this->_getField()->getOption('class') ) {
            $this->addClass( $this->_getField()->getOption('class') );
        }

        if ( $this->_getField()->getOption('required') ) {
            $this->addValidationRule('required');
        }

        if ( $this->_getField()->getOption('title') ) {
            $this->addAttr('title', $this->_getField()->getOption('title'));
        }

        foreach ($this->getAllAttrs() as $attr => $value) {
            $selectField .= $attr . '="' . $value . '" ';
        }

        $selectField .= $this->_getClosingHTML() . PHP_EOL;

        // Adding Options

        $options = "\t" . '<option value="">' . $this->_getField()->getOption('default') . '</option>' . PHP_EOL;

        // Make sure that the options are an array and it's not empty before proceeding
        if ( ( $tmpOptions = is_array($this->_getField()->getOption('options')) ) && ! empty($tmpOptions) ) {

            foreach ( $this->_getField()->getOption('options') as $option ) {
                if ( $this->_getField()->getValue() == $option ) {
                    $options .= "\t" . '<option selected="selected" value="'. $option .'">' . $option . '</option>' . PHP_EOL;
                } else {
                    $options .= "\t" . '<option value="'. $option .'">' . $option . '</option>' . PHP_EOL;
                }
            }
            
        }
        
        return $selectField . $options . '</select>';
    }

    /**
     * This allows to get the closing HTML of the select field.
     * Note: This is used just to confirm to Namodg Spec!
     * 
     * @return string
     */
    protected function _getClosingHTML() {
        return '>';
    }
}