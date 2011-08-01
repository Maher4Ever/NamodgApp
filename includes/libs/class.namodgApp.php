<?php

/**
 * NamodgApp - A beautiful ajax form
 * ========================
 * 
 * NamodgApp is customizable, configurable, ajax application which can be used
 * to recieve data from users. It's form is generated using Namodg which allows
 * developers to eaisly extend and change the functionality of NamodgApp.
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
require_once 'namodg/class.namodg.php';
require_once 'class.namodgApp.language.php';
require_once 'class.rain.tpl.php';

/**
 * NamodgApp offers a global access to all the application's functionality.
 * It's the central controller of NamodgApp. That means it's the only object
 * that has to be instantiated.
 * 
 * @package NamodgApp
 */
class NamodgApp {
    
    /**
     * The key is used to encrypt/decrypt data
     * 
     * @static string
     */
    private static $_key = NULL;
    
    /**
     * This is the form object
     * 
     * @var Namodg
     */
    private $_form = NULL;
    
    /**
     * This is the templating enigne
     * 
     * @var RainTPL
     */
    private $_tpl = NULL;
    
    /**
     * This is the languages' controller object
     * 
     * @var NamodgAppLanguage
     */
    private $_language = NULL;
    
    /**
     * Run-time errors
     * 
     * @var array
     */
    private $_errors = array();
    
    /**
     * Passed configurations are saved here
     * 
     * @var array
     */
    private $_config = array();
    
    /**
     * This is a flag used to check the status of the email
     * 
     * @var boolean
     */
    private $_emailSent = false;
    
    /**
     * Initialize NamodgApp, this includes validating the passed configs,
     * loading all libs and showing errors if there are any.
     * 
     * @param array $config The whole application config (Namodg + NamodgApp) 
     */
    public function __construct( $config = array() ) {
        
        try { // Try to normal route
            
            if ( ! is_array($config) ) {
                throw new NamodgAppException('config_not_array');
            }
            
            if ( ! isset ($config['app'], $config['form']) || ! is_array($config['app']) ) {
                throw new NamodgAppException('config_array_not_valid');
            }
            
            $this->_setConfig($config['app'])
                 ->_validateRequirements();
            
            // The form (Namodg) has the method to validate the key,
            // but we set 'key' as a config for the app  to avoid confiusing the user
            $config['form']['key'] = $config['app']['key'];
            
            // Initialize the form and suppress it's errors
            $this->_form = new Namodg($config['form'], true);
            
            if ( $this->form()->getFatalError() ) {
                $this->_addError( $this->form()->getFatalError() );
            } else {
                self::$_key = $config['app']['key'];
            }
            
            if ( filter_var($this->_getConfig('to_email'), FILTER_VALIDATE_EMAIL) === false ) {
                 $this->_addError('to_email_not_valid');
            }
            
            if ( filter_var($this->_getConfig('from_email'), FILTER_VALIDATE_EMAIL) === false ) {
                 $this->_addError('from_email_not_valid');
            }
            
        } catch ( NamodgAppException $e ) { // Fatal config error         
            $this->_setConfig() // Use default configs
                 ->_addError($e->getMessage());
        }
        
        $this->_loadLanguage()
             ->_loadTemplate();
        
        if ( $this->_getErrors() ) {
            $this->showRunErrors();
        }
    }
    
    /**
     * Languages' controller getter
     * 
     * @return NamodgAppLanguage
     */
    protected function _language() {
        return $this->_language;
    }
    
    /**
     * Templateing engine getter
     * 
     * @return RainTPL
     */
    protected function _tpl() {
        return $this->_tpl;
    }
    
    /**
     * Form object getter
     * 
     * @return Namodg 
     */
    public function form() {
        return $this->_form;
    }
    
    /**
     * Renders the home page for the chosen template
     */
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
    
    /**
     * Renders the validation errors for the chosen template
     */
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

    /**
     * Renders the send conformation page for the chosen template
     */    
    public function showSendConformation() {
        $this->_tpl()->assign('title', $this->_language()->getPhrase('response', 'sending_succeeded'));

        $this->_tpl()->assign('status', $this->_language()->getPhrase('response', 'sending_succeeded'));
        $this->_tpl()->assign('headline', $this->_language()->getPhrase('response', 'success_message_headline'));
        $this->_tpl()->assign('message', $this->_language()->getPhrase('response', 'success_message_body'));
        $this->_tpl()->assign('button', array('text' => $this->_language()->getPhrase('misc', 'new_message'), 'url' => 'index.php'));

        $this->_tpl()->draw('send_conformation');
    }

    /**
     * Renders the send faliure page for the chosen template
     */    
    public function showSendFailure () {
        $this->_tpl()->assign('title', $this->_language()->getPhrase('response', 'sending_failed'));

        $this->_tpl()->assign('status', $this->_language()->getPhrase('response', 'sending_failed'));
        $this->_tpl()->assign('headline', $this->_language()->getPhrase('response', 'fail_message_headline'));
        $this->_tpl()->assign('message', $this->_language()->getPhrase('response', 'fail_message_body'));
        $this->_tpl()->assign('button', array('text' => $this->_language()->getPhrase('misc', 'new_message'), 'url' => 'index.php'));

        $this->_tpl()->draw('send_failure');
    }

    /**
     * Renders the run-time errors for the chosen template
     */
    public function showRunErrors() {
        $this->_tpl()->assign('error_title', $this->_language()->getPhrase('misc', 'fatal_errors_title'));
        $this->_tpl()->assign('errors', $this->_getErrors());
        $this->_tpl()->assign('button', array( 'url' => $_SERVER['SCRIPT_NAME'], 'text' => $this->_language()->getPhrase('misc', 'reload_page')) );
        exit( $this->_tpl()->draw('run_errors', true) );
    }
    
    /**
     * This method includes the fields and validates them. It's used because
     * users use the golbal $app to add the fields to the form and it's not avaiable 
     * when the constructor is invoked.
     * 
     * @global NamodgApp $app 
     */
    public function addForm() {
        global $app;
        include NAMODG_APP_DIR . 'includes/form.php';
        $this->_validateAddedFields();
    }
    
    /**
     * Sends an email using Swift. It should be noted that data must be
     * validated before attempting to send it.
     */
    public function sendEmail() {
        
        require_once NAMODG_APP_DIR  . 'includes/libs/swift/swift_required.php';
        
        $message = Swift_Message::newInstance();
        
        $message
            ->setSubject( $this->_getConfig('message_title') ? 
                            $this->_getConfig('message_title') : 
                            $this->_language()->getPhrase('mailer', 'default_subject') 
                        )

            ->setFrom(array($this->_getConfig('from_email') => $this->_language()->getPhrase('mailer', 'sender_name')))

            ->setTo($this->_getConfig('to_email'))

            ->setBody($this->_generateEmail(), 'text/html')

            ->addPart($this->_generateEmail('txt'), 'text/plain');
                
        if ( $this->_getConfig('reply_to_field_name') ) {
            $email = $this->form()->getField( $this->_getConfig('reply_to_field_name') )->getCleanedValue();
            $message->setReplyTo($email);
        }
        
        // Add NamodgApp header
        $message->getHeaders()->addTextHeader('X-Generator', 'NamodgApp v1.4');
        	
        try { // Try sending using sendmail
            $transport = Swift_SendmailTransport::newInstance();
            $mailer = Swift_Mailer::newInstance($transport);
            $this->_emailSent = @$mailer->send($message);
        } catch(Swift_TransportException $e) { // Can't use sendmail, try mail()
            $transport = Swift_MailTransport::newInstance();  
            $mailer = Swift_Mailer::newInstance($transport);
            $this->_emailSent = @$mailer->send($message);  
        }
		
        if ( ! $this->_emailSent ) {
                @error_log('NamodgApp: mail() function is activated but unable to send emails. Please consult your server admins about this problem', 0);
        } else {
                $this->_emailSent = true;
        }
        
    }
    
    /**
     * Validates the fields after being added to the form
     */
    private function _validateAddedFields() {
        $replyField = $this->_getConfig('reply_to_field_name');
        if ( $replyField && ! empty($replyField) && ! array_key_exists($replyField, $this->form()->getFields()) ) {
            $this->_addError('reply_to_field_name_not_valid');
        }
        
        if ( $this->_getErrors() ) {
            $this->showRunErrors();
        }
    }
    
    /**
     * Returns the status of the email send
     * 
     * @return boolean
     */
    public function isEmailSent() {
        return $this->_emailSent;
    }
    
    /**
     * Used to checks if the request is Ajax or not
     *
     * @return boolean
     */
    public function isAjaxRequest() {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }
    
    /**
     * Generates an email with the sent data. It can return the email in two formats:
     *  1- (txt): a multiline ID:VALUE pair
     *  2- (html): pases the generated date to the email template and returns the result
     * 
     * @param string $type (txt or html)
     * @return string The generated message in the requested type
     */
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
            
            if ( !$field->getOption('required') && empty($value) ) {
                continue;
            }
            
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
     * Generates the fields used in the templates
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
            
            // Stop if there is no label
            if ( ! $field->getOption('label')) {
                $i++;
                continue;
            }
            
            $fields[$i]['label'] = $field->getOption('label');
            
            // Generate the label html
            $labelHTML = '<label' . ( $field->getOption('id') ? ' for="' . $field->getOption('id') . '" ' : '' ) . '>';
            $labelHTML .= $field->getOption('label');
            $labelHTML .= '</label>';
            $fields[$i]['label_html'] = $labelHTML;
            $i++;
        }

        return $fields;
    }
    
    /**
     * Loads the languages' controller (NamodgAppLanguage)
     * 
     * @return NamodgApp Allows chaining
     */
    private function _loadLanguage() {
        $this->_language = new NamodgAppLanguage($this->_getConfig('language'));
        
        if ( $this->_language()->getErrors() ) {
            $this->_addError( $this->_language()->getErrors() );
        }
        
        return $this;
    }
    
    /**
     * Loads the Templating engine (RainTPL)
     * 
     * @return NamodgApp Allows chaining
     */
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
    
    /**
     * Sets the configurations of NamodgApp. If nothing is passed,
     * it uses the default settings.
     * 
     * @param array $config
     * @return NamodgApp Allows chaining
     */
    private function _setConfig($config = NULL) {
        
        $defaults = array(
            'debug' => FALSE,
            'to_email' => NULL,
            'from_email' => NULL,
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
    
    /**
     * Config(s) getter. It returns the requested config if an ID is passed,
     * otherwise it returns the the configs array.
     * 
     * @param string $id
     * @return mixen 
     */
    private function _getConfig($id = NULL) {
        return $id ? ( isset($this->_config[$id]) ? $this->_config[$id] : false ) : $this->_config;
    }
    
    /**
     * Validates NamodgApp requirements. This includes the checking the availability 
     * of the used functions, folders' permissions and PHP version. 
     * 
     * @return NamodgApp Allows chaining
     */
    private function _validateRequirements() {
        
        if (version_compare(PHP_VERSION, '5.2.0') < 0) {
            $this->_addError('php_version_not_supported');
        }
        
        if ( ! is_writable(NAMODG_APP_DIR . 'cache') ) {
            $this->_addError('cache_not_writable');
        }
        
        if ( ! function_exists('mail') || ! is_callable('mail') ) {
            $this->_addError('mail_function_disabled');
        }
        
        return $this;
    }
    
    /**
     * Adds error(s) to the errors' array. It accepts a string or an array.
     * 
     * @param mixen $error (string or array)
     * @return NamodgApp Allows chaining
     */
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
    
    /**
     * Errors getter. Returns false if there are no errors.
     * 
     * @return array
     */
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

/**
 * NamodgApp Exceptions. Used when things go wrong!
 * 
 * @package NamodgApp
 */
class NamodgAppException extends Exception {}
