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
require_once 'core/namodg.field.class.php';
require_once 'namodg.defaultRenderers.class.php';

/**
 * Namodg Text Field
 *
 * @desc Used for data of type string
 */
class NamodgField_TextField extends NamodgField {

    public function isValid() {
        $value = $this->getValue();

        if ($this->getOption('required') && empty($value)) {
            $this->_setValidationError('required');
            return false;
        }

        return true;
    }

    public function getCleanedValue() {
        return filter_var( $this->getValue(), FILTER_SANITIZE_STRING);
    }

    public function getHTML() {
        $field = new NamodgFieldRenderer('input', $this);
        $field->addAttr('type', 'text');
        return $field->render();
    }
}

/**
 * Namodg Textarea
 *
 * @desc Used for multi-line data of type string
 */
class NamodgField_Textarea extends NamodgField_TextField {

    public function getHTML() {
        $field = new NamodgFieldRenderer('textarea', $this);
        return $field->render();
    }

}

/**
 * Namodg Email Field
 *
 * @desc Used for data of type string/email
 */
class NamodgField_Email extends NamodgField {

    public function isValid() {
        $value = $this->getValue();

        if ($this->getOption('required') && empty($value)) {
            $this->_setValidationError('required');
            return false;
        }

        if ( !filter_var( $value, FILTER_VALIDATE_EMAIL )) {
            $this->_setValidationError('email_not_valid');
            return false;
        }

        return true;
    }

    public function getCleanedValue() {
        return filter_var( $this->getValue(), FILTER_SANITIZE_EMAIL);
    }

    public function getHTML() {
        $field = new NamodgFieldRenderer('input', $this);
        $field->addAttr('type', 'text');
        $field->addValidationRule('email');
        return $field->render();
    }

}

/**
 * Namodg Number Field
 *
 * @desc Used for data of type integer
 */
class NamodgField_NumberField extends NamodgField {

    public function isValid() {
        $value = $this->getValue();

        if ($this->getOption('required') && empty($value)) {
            $this->_setValidationError('required');
            return false;
        }

        if ( !filter_var( $value, FILTER_VALIDATE_INT )) {
            $this->_setValidationError('not_number');
            return false;
        }

        return true;
    }

    public function getCleanedValue() {
        return filter_var( $this->getValue(), FILTER_SANITIZE_NUMBER_INT );
    }

    public function getHTML() {
        $field = new NamodgFieldRenderer('input', $this);
        $field->addAttr('type', 'text');
        $field->addValidationRule('number');
        return $field->render();
    }

}

/**
 * Namodg Select Field
 *
 * @desc Used for a chosable data of type string
 */
class NamodgField_Select extends NamodgField {

    public function __construct($name, $options = array()) {
        $this->_addDefaultOptions(array(
            'options' => array(),
            'empty' => NULL
        ));
        parent::__construct($name, $options);
    }

    public function getCleanedValue() {
        return filter_var( $this->getValue(), FILTER_SANITIZE_STRING);
    }

    public function isValid() {
        $value = $this->getValue();

        if (empty($value)) {
            $this->_setValidationError('required');
            return false;
        }

        return true;
    }

    public function getHTML() {
        $field = new NamodgSelectRenderer($this);
        return $field->render();
    }
}

/**
 * Namodg Captcha Field
 *
 * @desc Used to stop spam
 */
class NamodgField_Captcha extends NamodgField {

    private $_rand1 = NULL;
    private $_rand2 = NULL;

    public function __construct($name, $options = array()) {
        $this->_addDefaultOptions(array(
            'info' => NULL
        ));
        parent::__construct($name, $options);
        $this->_rand1 = mt_rand(1, 9);
        $this->_rand2 = mt_rand(1, 9);
        $this->setOption('required', true);
        $this->setOption('send', false);
    }

    public function isValid() {
        $value = $this->getValue();

        if (empty($value)) {
            $this->_setValidationError('required');
            return false;
        }

        $rands = $this->_getCaptchaQuestion();

        if ((int)$value !== ($rands[0] + $rands[1])) {
            $this->_setValidationError('captcha_answer_wrong');
            return false;
        }

        return true;
    }

    public function getCleanedValue() {
        return filter_var( $this->getValue(), FILTER_SANITIZE_NUMBER_INT);
    }

    public function getHTML() {
        $field = new NamodgFieldRenderer('input', $this);
        $field->addAttr('type', 'text');
        $field->addValidationRule('captcha');

        $rands = $this->_getCaptchaQuestion();

        $return = '<p class="captcha-question"';
        $return .= ($this->getOption('id')) ? ' id="' . $this->getOption('id').  '-question"' : '';
        $return .= ($this->getOption('info')) ? ' title="' . $this->getOption('info').  '"' : '';
        $return .= '>';
        $return .= $rands[0] . ' + ' . $rands[1] . '</p>' . PHP_EOL;

        $return .= $field->render();

        return $return;
    }

    protected function _getCaptchaQuestion() {
        return array(
            $this->_rand1,
            $this->_rand2
        );
    }
}

/**
 * Namodg Submit Button
 *
 * @desc Used to submit data
 */
class NamodgField_Submit extends NamodgField {

    public function __construct($name, $value, $options = array()) {
        parent::__construct($name, $options);
        $this->setValue($value);
        $this->setOption('send', false);
    }

    public function isValid() {
        return true;
    }

    public function getCleanedValue() {
        return filter_var( $this->getValue(), FILTER_SANITIZE_STRING);
    }

    public function getHTML() {
        $field = new NamodgFieldRenderer('button', $this);
        $field->addAttr('type', 'submit');
        return $field->render();
    }

}