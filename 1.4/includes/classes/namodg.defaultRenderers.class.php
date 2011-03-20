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
require_once 'core/namodg.renderer.class.php';

/**
 * This generates the openning of the form ONLY!
 */
class NamodgFormRenderer extends NamodgRenderer {

    public function __construct() {
        parent::__construct('form');
    }

    public function render() {
        $html = '<form ';
        foreach ( $this->getAllAttrs() as $attr => $value) {
            $html .= $attr . '="' . $value . '" ';
        }
        $html .= '>' . PHP_EOL;
        return $html;
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

        $html .= $this->_closeTag();

        return $html;
    }

    protected function _getField() {
        return $this->_field;
    }

    private function _closeTag() {
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

        $selectField .= $this->_closeTag();

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

    private function _closeTag() {
        return '>';
    }
}