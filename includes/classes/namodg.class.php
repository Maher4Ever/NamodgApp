<?php

/*
 * Include dependencies
 */
require_once 'namodg.defaultRenderers.class.php';
require_once 'namodg.defaultFields.class.php';
require_once 'core/namodg.language.class.php';
require_once 'core/namodg.mailer.php';

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
     * Configurations container
     *
     * @var array
     */
    private $_config = array();

    /**
     * An array that containes the phrases of the chosen language
     *
     * @var array
     */
    private $_phrases = array();

    /**
     * Namodg fields objects container
     *
     * @var array
     */
    private $_fields = array();

    /**
     * Errors container
     *
     * @var array
     */
    private $_errors = array();

    /**
     * Initialize Namodg
     *
     * @param array $config
     */
    public function __construct($config = array()) {

        // Stop if the configurations are not an array
        if ( ! is_array($config) ) {
            // Default the language to arabic because there is no config
            $this->_fillPhrases();
            $this->addFatalError('config_not_array');
            return;
        }
        
        $this->_config = $this->_replaceDefalutConfig($config);
        
        $this->_fillPhrases( $this->_config['language'] );
        $this->_validateConfig();
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
    * Checks to see if the data was sent and that if it contains valid NamodgField Objects.
    *
    * @return boolean
    */
    public function canBeProcessed() {

        $method = $GLOBALS['_' . $this->_config['method']];

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
        $this->_addFieldsDataFromRequest($method);
        
        return true;
    }

    /**
     * Checks to see if the request was Ajax
     *
     * @return boolean
     */
    public function isAjaxRequest() {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
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
        return ( isset($this->_errors['validation']) && count($this->_errors['validation']) !== 0 ) ? false : true;
    }

    /**
     * Errors getter method
     * 
     * @return array
     */
    public function getValidationErrors() {
        return (array)$this->_errors['validation'];
    }

    /**
     * Draws the form beginning HTML tag
     * 
     * @return string
     */
    public function getOpeningHTML() {
        $form = new NamodgFormRenderer();
        $form->addAttr('action', $this->_config['url']);
        $form->addAttr('method', $this->_config['method']);

        if ( $this->_config['id'] ) {
            $form->setID( $this->_config['id'] );
        }

        if ( $this->_config['class'] ) {
            $form->addClass( $this->_config['class'] );
        }

        return $form->render();
    }

    /**
     * Draws all namodg fields, and return them as an array
     *
     * @param boolean $withErrors returns the error of each fields with it
     * @return array
     */
    public function getFieldsAsArray($withErrors = false) {
        $fields = array();
        $i = 0;

        foreach ( $this->_fields as $field ) {

            $fields[$i]['field_html'] = $field->getHTML();
            $fields[$i]['field_type'] = $field->getType();
            $fields[$i]['value'] = $field->getValue();

            if ( $withErrors && isset($this->_errors['validation'][$field->getName()]) ) {
               $fields[$i]['validation_error'] =  $this->_errors['validation'][$field->getName()]['error'];
            }

            if ( $field->getOption('title') ) {
                $fields[$i]['title'] = $field->getOption('title');
            }

            if ( ! $field->getOption('label')) {
                $i++;
                continue;
            }

            $fields[$i]['label'] = $field->getOption('label');
            
            $labelHTML = '<label ' . ( $field->getOption('id') ? 'for="' . $field->getOption('id') . '"' : '' ) . ' >';
            $labelHTML .= $field->getOption('label');
            $labelHTML .= '</label>';
            $fields[$i]['label_html'] = $labelHTML;
            $i++;
        }

        return $fields;
    }

    /**
     * Helper method. Returns the same array as the one above, except it returns the error
     * of each field if it has one
     * 
     * @return boolean
     */
    public function getFieldsWithErrorsAsArray() {
        return $this->getFieldsAsArray($withErrors = true);
    }

    /**
     * Draws the form closing HTML tag
     *
     * @return string
     */
    public function getClosingHTML() {
        $closing = '<div><input type="hidden" name="namodg_fields" value="' . $this->_encrypt( serialize($this->_fields) ) . '"></div>' . PHP_EOL;
        $closing .= '</form>';
        return $closing;
    }

    /**
     * Phrases getter method
     * 
     * @param string $group the groups of phrases
     * @param string $id the id of the reqested phrase
     * @return string
     */
    public function getPhrase($group, $id) {
        return $this->_phrases[$group][$id];
    }

    /**
     * Returns the phrases used in the runtime via Javascript
     * 
     * @return array
     */
    public function getJsPhrases() {
        return $this->_phrases['js'];
    }

    /**
     * Fatal errors getter method
     * 
     * @return array
     */
    public function getFatalErrors() {
        
        // Late checkings - after adding the fields
        if ( $this->_config['replay_to_field_name'] && !empty($this->_config['replay_to_field_name']) && !array_key_exists($this->_config['replay_to_field_name'], $this->_fields) ) {
            $this->addFatalError('reply_to_field_name_not_valid');
        }
        
        if ( ! isset ($this->_errors['fatal']) ) {
            $this->_errors['fatal'] = array();
        }
        
        return $this->_errors['fatal'];
    }

    /**
     * Helper method. Returns the data used in the email message as an array
     * @return array
     */
    public function getMessageData() {
        return $this->_generateMessageDataAs('array');
    }

    /**
     * Sends the message generated via the template engine
     *
     * @param string $messageHTML
     * @return boolean the status of the sending process
     */
    public function sendUsingTemplate($messageHTML) {
        $mailer = new NamodgMailer();

        $mailer->from($this->getPhrase('mailer', 'sender_name'), $this->_config['email']);

        $mailer->to($this->_config['email']);

        if ( ! empty($this->_config['replay_to_field_name']) ) {
            $mailer->replyTo( $this->getField( $this->_config['replay_to_field_name'] )->getCleanedValue() );
        }
        
        $mailer->subject($this->_config['message_title']);

        $mailer->body($messageHTML);
        $mailer->altBody($this->_generateMessageDataAs('plain'));

        return $mailer->send();
    }

    /**
     * Mailer errors getter
     * 
     * @return array
     */
    public function getMailerErrors() {
        return $this->_errors['mailer'];
    }

    /**
     * Adds fatal errors to the errors array
     * 
     * @param string $id the error id
     */
    public function addFatalError($id) {
        if ( ! isset($this->_errors['fatal']) ) {
            $this->_errors['fatal'] = array();
        }
        array_push($this->_errors['fatal'], $this->_phrases['errors'][$id]);
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

        // Create a new class with unknows arguments
        $class = new ReflectionClass($class);

        // Pass the arguments to the class and add it to the fields array
        $this->_addField( $class->newInstanceArgs($arguments) );
    }

    /**
     * Merge the passed config array with the default config
     * 
     * @param array $config
     * @return array the new config array
     */
    private function _replaceDefalutConfig($config) {

        $defaults = array (
            'id' => NULL,
            'class' => NULL,
            'method' => 'POST',
            'url' => $_SERVER['SCRIPT_NAME'],
            'language' => 'ar',
            'email' => NULL
        );

        $config = array_map('trim', $config);
        
        return ( empty($config) ) ? $defaults : array_merge($defaults, $config);
    }

    /**
     * Validates the configurations array
     * 
     */
    private function _validateConfig() {
        
        if ( empty($this->_config['key']) ) {
            $this->addFatalError('no_key');
        }

        if ( strtoupper($this->_config['method']) !== 'POST' && strtoupper($this->_config['method']) !== 'GET' ) {
            $this->addFatalError('method_not_valid');
        }

        if ( empty($this->_config['email']) || ! filter_var($this->_config['email'], FILTER_VALIDATE_EMAIL)) {
            $this->addFatalError('receipt_email_not_valid');
        }

        if ( ! function_exists('mail') || ! is_callable('mail') ) {
            $this->addFatalError('mail_function_disabled');
        }
        
    }
    
    /**
     * Fill the phrases array
     *
     * @param string $langId
     * @param boolean $configError
     */
    private function _fillPhrases($langId = '') {
        
        $lang = new NamodgLanguage($langId);
        
        $lang->parseArrayFromFolder('phrase', dirname(__FILE__) . '/../../languages');

        $this->_phrases = $lang->getPhrases();
        
        // Wait untill the phrases are loaded before adding the errors!
        if ( ! $lang->isCodeValid() ) {
            $this->addFatalError('language_code_length_not_valid');
        }

        if ( ! $lang->doesFileExsists() ) {
            $this->addFatalError('language_file_not_found');
        }
    }

    /**
     * Makes sure the field class is a NamodgField object, or lets PHP throw an error.
     * This is just to stay save.
     *
     * @param NamodgField $fieldObj
     */
    private function _addField(NamodgField $fieldObj) {
        $this->_fields[$fieldObj->getName()] = $fieldObj;
    }

    /**
     * Stores the sent data into the field
     *
     * @param string $method
     */
    private function _addFieldsDataFromRequest($method) {
        foreach ($method as $name => $value) {
            if ( $this->getField($name) ) {
                $this->getField($name)->setValue($value);
            }
        }
    }

    /**
     * Basic encryption method
     *
     * @param string $str
     * @return string
     */
    private function _encrypt($str){

      if (empty($this->_config['key'])) {
          return $str;
      }
      
      $result = '';      
      for($i=0, $length = strlen($str); $i<$length; $i++) {
         $char = substr($str, $i, 1);
         $keychar = substr($this->_config['key'], ($i % strlen($this->_config['key']))-1, 1);
         $char = chr(ord($char)+ord($keychar));
         $result.= $char;
      }
      return base64_encode($result);
    }

    /**
     * Basic decryption method
     *
     * @param string $str
     * @return string
     */
    private function _decrypt($str){

        if (empty($this->_config['key'])) {
          return $str;
        }

        $str = base64_decode($str);
        $result = '';
        for($i=0, $length = strlen($str); $i<$length; $i++) {
            $char = substr($str, $i, 1);
            $keychar = substr($this->_config['key'], ($i % strlen($this->_config['key']))-1, 1);
            $char = chr(ord($char)-ord($keychar));
            $result.=$char;
        }
        return $result;
    }

    /**
     * Generates the data used in the message and returs in the
     * type specified by the user
     *
     * @param $type array or string
     * @return mixen
     */
    private function _generateMessageDataAs($type) {
        $return = ($type == 'array') ? array() : '';

        foreach ( $this->_fields as $field ) {

            // Don't process the fields that won't be sent
            if ( $field->getOption('send') == false) {
                continue;
            }

            $id = ( $field->getOption('label') ) ? $field->getOption('label') : $field->getName();
            $id = trim(str_replace(':', '', $id));
            $value = trim($field->getCleanedValue());
            
            if ( $type == 'array' ) {
                if ( $field->getType() == 'textarea' ) {
                    $return['multiple_lines'][] = array(
                        'id' => $id . ' : ',
                        'value' => nl2br($value)
                    );
                } else {
                    $return['one_line'][] = array(
                        'id' => $id . ' : ',
                        'value' => $value
                    );
                }
            } elseif ( $type == 'plain') {
                $return .= $id . ' : ' . $value . PHP_EOL;
            }
        }

        return $return;
    }

    /**
     * Addes validation errors to the errors array
     * 
     * @param string $fieldName
     * @param string $label
     * @param string $error
     */
    private function _addValidationError($fieldName, $label, $error) {
        if ( ! isset($this->_errors['validation']) ) {
            $this->_errors['validation'] = array();
        }
        
        $this->_errors['validation'][$fieldName] = array(
            'fieldLabel' => $label,
            'error' => ( isset($this->_phrases['validation'][$fieldName][$error]) && ! empty($this->_phrases['validation'][$fieldName][$error]) ) ? $this->_phrases['validation'][$fieldName][$error] : $this->_phrases['validation'][$error]
        );
    }
}