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

/*
 * Include dependencies
 */
require_once 'class.namodg.renderer.php';

/**
 * This generates the openning of the form ONLY!
 */
class NamodgFormRenderer extends NamodgRenderer {
    
    private $_fields = NULL;
    
    private static $_key = NULL;
    
    public function __construct($fields, $key) {
        parent::__construct('form');
        $this->_fields = $fields;
        self::$_key = $key;
    }

    public function render() {
       
        // Form beginning tag
        $html = '<form ';
        foreach ( $this->getAllAttrs() as $attr => $value) {
            $html .= $attr . '="' . $value . '" ';
        }
        $html .= '>' . PHP_EOL;
        
        // W3C doesn't allow inputs inside forms directly
        $html .= "\t<div>" . PHP_EOL;
        
        // Build fields
        foreach ( $this->_fields as $field ) {     
            if ( $field->getOption('label') ) {
                $labelHTML = "\t\t";
                $labelHTML .= '<label ' . ( $field->getOption('id') ? 'for="' . $field->getOption('id') . '"' : '' ) . ' >';
                $labelHTML .= $field->getOption('label');
                $labelHTML .= '</label>' . PHP_EOL;
                
                $html .= $labelHTML;
            }
            
            $html .= "\t\t" . $field->getHTML() . PHP_EOL . PHP_EOL;
        }
        
        // Add namodg hidden field
        $html .= "\t\t<input type='hidden' name='namodg_fields' value='" . self::_encrypt( serialize($this->_fields) ) . "'>" . PHP_EOL;
        
        // Close the div
        $html .= "\t</div>" . PHP_EOL;
        
        // Close form html
        $html .= $this->_getClosingHTML();
        
        return $html;
    }

    /**
     * Basic encryption method
     *
     * @param string $str
     * @return string
     */
    private static function _encrypt($str){
      $result = '';      
      for($i=0, $length = strlen($str); $i<$length; $i++) {
         $char = substr($str, $i, 1);
         $keychar = substr(self::$_key, ($i % strlen(self::$_key))-1, 1);
         $char = chr(ord($char)+ord($keychar));
         $result.= $char;
      }
      return base64_encode($result);
    }
    
    /**
     * Draws the form closing HTML tag
     *
     * @return string
     */
    private function _getClosingHTML() {
        return '</form>' . PHP_EOL;
    }
}

/**
 * This is a general field renderer
 */
class NamodgFieldRenderer extends NamodgRenderer {

    private $_field = NULL;

    public function __construct($tag, NamodgField $field) {
        parent::__construct($tag);
        $this->_field = $field;
    }

    public function addValidationRule($rule) {
        if ( $this->getAttr('data-validation') ) {
            $this->addAttr('data-validation', $this->getAttr('data-validation') . ' ' . $rule);
        } else {
            $this->addAttr('data-validation', $rule);
        }
        return $this;
    }

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

    protected function _getField() {
        return $this->_field;
    }

    private function _getClosingHTML() {
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
 */
class NamodgSelectRenderer extends NamodgFieldRenderer {

    public function __construct(NamodgField $field) {
        parent::__construct('select', $field);
    }

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

    private function _getClosingHTML() {
        return '>';
    }
}