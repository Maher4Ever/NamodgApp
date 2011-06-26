<?php

require_once 'namodg/class.namodg.php';
require_once 'class.namodgApp.language.php';
require_once 'class.rain.tpl.php';

class NamodgApp {
    
    private static $_key = NULL;
    
    private $_form = NULL;
    
    private $_tpl = NULL;
    
    private $_language = NULL;
    
    private $_errors = array();
    
    private $_config = array();
    
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
                        
        } catch ( NamodgAppException $e ) { // Fatal config error           
            $this->_setConfig()
                 ->_addError($e->getMessage());
        }
        
        $this->_loadLanguage()
             ->_loadTemplate();
        
        if ( $this->_getErrors() ) {
            $this->_tpl()->assign('error_title', $this->_language()->getPhrase('misc', 'fatal_errors_title'));
            $this->_tpl()->assign('errors', $this->_getErrors());
            $this->_tpl()->assign('button', array( 'url' => $_SERVER['SCRIPT_NAME'], 'text' => $this->_language()->getPhrase('misc', 'reload_page')) );
            exit( $this->_tpl()->draw('run_errors', true) );
        }
    }
    
    private function _language() {
        return $this->_language;
    }
    
    private function _tpl() {
        return $this->_tpl;
    }
    
    public function form() {
        return $this->_form;
    }
    
    public function showHome() {       
        $form = new NamodgFormRenderer($this->form()->getFields(), self::$_key);
        
        $this->_tpl()->assign('form_open', $form->getOpeningHTML());
        $this->_tpl()->assign('selected', $this->_language()->getPhrase('misc', 'selected'));
        $this->_tpl()->assign('fields', $this->_getTemplateFields());
        $this->_tpl()->assign('form_close', $form->getClosingHTML());

        $this->_tpl()->draw('home');
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

            if ( $withErrors && $errors[ $field->getName() ] ) {
                $fields[$i]['validation_error'] = $this->_language()->getPhrase('validation', $errors[ $field->getName() ]);
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
        
        return $this;
    }
    
    private function _setConfig($config = NULL) {
        
        $defaults = array(
            'template' => 'air',
            'language' => 'ar',
            'page_title' => 'Namodg',
            'description' => NULL,
            'form_title' => NULL
        );
        
        $this->_config = $config ? array_merge($defaults, $config) : $defaults;
        
        return $this;
    }
    
    private function _getConfig($id = NULL) {
        return $id ? $this->_config[$id] : $this->_config;
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

class NamodgAppException extends Exception {
    
}