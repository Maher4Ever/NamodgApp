<?php

require_once 'namodg/class.namodg.php';
require_once 'class.namodgApp.language.php';
require_once 'class.namodgApp.mailer.php';
require_once 'class.rain.tpl.php';

class NamodgApp {
    
    private static $_key = NULL;
    
    private $_form = NULL;
    
    private $_tpl = NULL;
    
    private $_language = NULL;
    
    private $_errors = array();
    
    private $_config = array();
    
    private $_emailSent = false;
    
    public function __construct( $config = array() ) {
        
        try {
            
            if ( ! is_array($config) ) {
                throw new NamodgAppException('config_not_array');
            }
            
            if ( ! isset ($config['app'], $config['namodg']) || ! is_array($config['app']) ) {
                throw new NamodgAppException('config_array_not_valid');
            }
            
            $this->_setConfig($config['app'])
                 ->_validateRequirements();
            
            $this->_form = new Namodg($config['namodg'], true);
            if ( $this->form()->getFatalError() ) {
                $this->_addError( $this->form()->getFatalError() );
            } else {
                self::$_key = $config['namodg']['key'];
            }
            
            if ( ! filter_var($this->_getConfig('email'), FILTER_VALIDATE_EMAIL) ) {
                 $this->_addError('receipt_email_not_valid');
            }
            
        } catch ( NamodgAppException $e ) { // Fatal config error           
            $this->_setConfig()
                 ->_addError($e->getMessage());
        }
        
        $this->_loadLanguage()
             ->_loadTemplate();
        
        if ( $this->_getErrors() ) {
            $this->showRunErrors();
        }
    }
    
    protected function _language() {
        return $this->_language;
    }
    
    protected function _tpl() {
        return $this->_tpl;
    }
    
    public function form() {
        return $this->_form;
    }
    
    public function showHome() {
        $form = new NamodgFormRenderer($this->form()->getFields(), self::$_key);
        $form->addAttr('action', $this->form()->getAttr('url'));
        $form->addAttr('method', $this->form()->getAttr('method'));

        if ( $this->form()->getAttr('id') ) {
            $form->setID( $this->form()->getAttr('id') );
        }

        if ( $this->form()->getAttr('class') ) {
            $form->addClass( $this->form()->getAttr('class') );
        }
        $this->_tpl()->assign('form_open', $form->getOpeningHTML());
        $this->_tpl()->assign('selected', $this->_language()->getPhrase('misc', 'selected'));
        $this->_tpl()->assign('fields', $this->_getTemplateFields());
        $this->_tpl()->assign('form_close', $form->getClosingHTML());

        $this->_tpl()->draw('home');
    }
    
    public function showValidationFailure() {
        $form = new NamodgFormRenderer($this->form()->getFields(), self::$_key);
        $form->addAttr('action', $this->form()->getAttr('url'));
        $form->addAttr('method', $this->form()->getAttr('method'));

        if ( $this->form()->getAttr('id') ) {
            $form->setID( $this->form()->getAttr('id') );
        }

        if ( $this->form()->getAttr('class') ) {
            $form->addClass( $this->form()->getAttr('class') );
        }
        
        $this->_tpl()->assign('form_open', $form->getOpeningHTML());
        $this->_tpl()->assign('selected', $this->_language()->getPhrase('misc', 'selected'));
        $this->_tpl()->assign('fields', $this->_getTemplateFields( $withErrors = true ));
        $this->_tpl()->assign('form_close', $form->getClosingHTML());

        exit( $this->_tpl()->draw('validation_failure', true) );
    }
    
    public function showSendConformation() {
        $this->_tpl()->assign('title', $this->_language()->getPhrase('response', 'sending_succeeded'));

        $this->_tpl()->assign('status', $this->_language()->getPhrase('response', 'sending_succeeded'));
        $this->_tpl()->assign('headline', $this->_language()->getPhrase('response', 'success_message_headline'));
        $this->_tpl()->assign('message', $this->_language()->getPhrase('response', 'success_message_body'));
        $this->_tpl()->assign('button', array('text' => $this->_language()->getPhrase('misc', 'new_message'), 'url' => 'index.php'));

        $this->_tpl()->draw('send_conformation');
    }
    
    public function showSendFailure () {
        $this->_tpl()->assign('title', $this->_language()->getPhrase('response', 'sending_failed'));

        $this->_tpl()->assign('status', $this->_language()->getPhrase('response', 'sending_failed'));
        $this->_tpl()->assign('headline', $this->_language()->getPhrase('response', 'fail_message_headline'));
        $this->_tpl()->assign('message', $this->_language()->getPhrase('response', 'fail_message_body'));
        $this->_tpl()->assign('button', array('text' => $this->_language()->getPhrase('misc', 'new_message'), 'url' => 'index.php'));

        $this->_tpl()->draw('send_failure');
    }

    public function showRunErrors() {
        $this->_tpl()->assign('error_title', $this->_language()->getPhrase('misc', 'fatal_errors_title'));
        $this->_tpl()->assign('errors', $this->_getErrors());
        $this->_tpl()->assign('button', array( 'url' => $_SERVER['SCRIPT_NAME'], 'text' => $this->_language()->getPhrase('misc', 'reload_page')) );
        exit( $this->_tpl()->draw('run_errors', true) );
    }
    
    public function sendEmail() {
        $mailer = new NamodgAppMailer();
        
        $mailer->from($this->_language()->getPhrase('mailer', 'sender_name'), $this->_getConfig('email') )
               ->to($this->_getConfig('email'))
               ->subject($this->_getConfig('email_title') ? $this->_getConfig('email_title') : $this->_language()->getPhrase('mailer', 'default_subject'))
               ->body($this->_generateEmail())
               ->altBody($this->_generateEmail('txt'));
        
        if ( $this->_getConfig('reply_to_field_name') ) {
            $email = $this->form()->getField( $this->_getConfig('reply_to_field_name') )->getCleanedValue();
            $mailer->replyTo($email);
        }
        
        $this->_emailSent = $mailer->send();
    }
    
    public function isFormValid() {
        $replyField = $this->_getConfig('reply_to_field_name');
         if ( $replyField && ! empty($replyField) && ! array_key_exists($replyField, $this->form()->getFields()) ) {
            $this->_addError('reply_to_field_name_not_valid');
            return false;
         }
         return true;
    }

    public function isEmailSent() {
        return $this->_emailSent;
    }
    
    /**
     * Checks to see if the request was Ajax
     *
     * @return boolean
     */
    public function isAjaxRequest() {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }
        
    private function _generateEmail($type = NULL) {
        $email = ($type === 'txt') ? '' : array();

        foreach ( $this->form()->getFields() as $field ) {

            // Don't process the fields that won't be sent
            if ( $field->getOption('send') == false) {
                continue;
            }

            $id = ( $field->getOption('label') ) ? $field->getOption('label') : $field->getName();
            $id = trim(str_replace(':', '', $id));
            $value = trim($field->getCleanedValue());
            
            if ( $type != 'txt' ) {
                if ( $field->getType() == 'textarea' ) {
                    $email['multiple_lines'][] = array(
                        'id' => $id . ' : ',
                        'value' => nl2br($value)
                    );
                } else {
                    $email['one_line'][] = array(
                        'id' => $id . ' : ',
                        'value' => $value
                    );
                }
            } else {
                $email .= $id . ' : ' . $value . PHP_EOL;
            }
        }
        
        if ( $type === 'txt' ) {
            return $email;
        }
        
        // HTML message
        $this->_tpl()->assign('message_title', $this->_getConfig('email_title') ? $this->_getConfig('email_title') : $this->_language()->getPhrase('mailer', 'default_subject'));
        $this->_tpl()->assign('one_line_data_title', $this->_language()->getPhrase('message', 'one_line_data_title'));
        $this->_tpl()->assign('multiple_lines_data_title', $this->_language()->getPhrase('message', 'multiple_lines_data_title'));
        $this->_tpl()->assign('data', $email);
        $this->_tpl()->assign('generator', 'This message was generated by <a href="http://namodg.com" style="color: #8fbacb;">Namodg</a> v' . Namodg::version . ' - all rights recieved');
        return $this->_tpl()->draw('email', true);
    }
    
    /**
     * Draws all namodg fields, and return them as an array
     *
     * @param boolean $withErrors returns the error of each fields with it
     * @return array
     */
    private function _getTemplateFields($withErrors = false) {
        
        $fields = array();
        $i = 0;
        
        if ( $withErrors ) {
            $errors = $this->form()->getValidationErrors();
        }
        
        foreach ( $this->form()->getFields() as $field ) {

            $fields[$i]['field_html'] = $field->getHTML();
            $fields[$i]['field_type'] = $field->getType();
            $fields[$i]['value'] = $field->getValue();

            if ( $withErrors ) {
                if ( isset($errors[ $field->getName() ]) ) {
                    $fields[$i]['validation_error'] = $this->_language()->getPhrase('validation', $errors[ $field->getName() ]);
                } else {
                    $fields[$i]['validation_error'] = false;
                }
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
    
    private function _loadLanguage() {
        $this->_language = new NamodgAppLanguage($this->_getConfig('language'));
        
        if ( $this->_language()->getErrors() ) {
            $this->_addError( $this->_language()->getErrors() );
        }
        
        return $this;
    }
    
    private function _loadTemplate() {
        $this->_tpl = new RainTPL();
        
        $this->_tpl()->configure('tpl_dir', 'templates/' . $this->_getConfig('template') . '/');
        $this->_tpl()->configure('cache_dir', 'cache/');
        
        $this->_tpl()->assign('title', $this->_getConfig('page_title') );
        $this->_tpl()->assign('description', $this->_getConfig('description') );
        $this->_tpl()->assign('form_title', $this->_getConfig('form_title') );
        $this->_tpl()->assign('version', Namodg::version);
        $this->_tpl()->assign('ltr', $this->_language()->isLTR());
        $this->_tpl()->assign('js_phrases', json_encode( $this->_language()->getPhrases('js') )) ;
        
        return $this;
    }
    
    private function _setConfig($config = NULL) {
        
        $defaults = array(
            'debug' => FALSE,
            'email' => NULL,
            'template' => 'air',
            'language' => 'ar',
            'page_title' => 'Namodg',
            'description' => NULL,
            'form_title' => NULL,
            'email_title' => NULL
        );
        
        $this->_config = $config ? array_merge($defaults, array_map('trim', $config)) : $defaults;
        
        return $this;
    }
    
    private function _getConfig($id = NULL) {
        return $id ? ( isset($this->_config[$id]) ? $this->_config[$id] : false ) : $this->_config;
    }
    
    private function _validateRequirements() {
        if ( ! is_writable(NAMODG_APP_DIR . 'cache') ) {
            $this->_addError('cache_not_writable');
        }
        
        if ( ! function_exists('mail') || ! is_callable('mail') ) {
            $this->_addError('mail_function_disabled');
        }
        
        return $this;
    }
    
    private function _addError($error) {
        if ( is_array($error) ) {
            foreach($error as $oneError) {
                $this->_addError($oneError);
            }
        } else {
            $this->_errors[] = $error;
        }
        
        return $this;
    }
    
    private function _getErrors() {
        if ( empty($this->_errors) ) {
            return false;
        }
        
        $errors = array();
        
        foreach($this->_errors as $error) {
            $errors[] = $this->_language()->getPhrase('errors', $error);
        }
        
        return $errors;
    }
}

class NamodgAppException extends Exception {}
