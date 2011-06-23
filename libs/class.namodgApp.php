<?php

require_once 'class.namodgApp.language.php';
require_once 'namodg/class.namodg.php';
require_once 'class.rain.tpl.php';

class NamodgApp {
    
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
            }
                        
        } catch ( NamodgAppException $e ) { // Fatal config error           
            $this->_setConfig()
                 ->_addError($e->getMessage());
        }
        
        $this->_loadLanguage()
             ->_loadTemplate();
        
        if ( $this->_getErrors() ) {
            $this->tpl()->assign('error_title', $this->language()->getPhrase('misc', 'fatal_errors_title'));
            $this->tpl()->assign('errors', $this->_getErrors());
            $this->tpl()->assign('button', array( 'url' => $_SERVER['SCRIPT_NAME'], 'text' => $this->language()->getPhrase('misc', 'reload_page')) );
            exit( $this->tpl()->draw('run_errors', true) );
        }
    }
    
    public function language() {
        return $this->_language;
    }
    
    public function form() {
        return $this->_form;
    }
    
    public function tpl() {
        return $this->_tpl;
    }
    
    private function _loadLanguage() {
        $this->_language = new NamodgLanguage($this->_getConfig('language'));
        
        if ( $this->language()->getErrors() ) {
            $this->_addError( $this->language()->getErrors() );
        }
        
        return $this;
    }
    
    private function _loadTemplate() {
        $this->_tpl = new RainTPL();
        
        $this->tpl()->configure('tpl_dir', NAMODG_APP_DIR . 'templates/' . $this->_getConfig('template') . '/');
        $this->tpl()->configure('cache_dir', NAMODG_APP_DIR . 'cache/');
        
        $this->tpl()->assign('title', $this->_getConfig('page_title') );
        $this->tpl()->assign('description', $this->_getConfig('description') );
        $this->tpl()->assign('form_title', $this->_getConfig('form_title') );
        $this->tpl()->assign('version', Namodg::version);
        
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
    
    private function _getConfig($id) {
        return $this->_config[$id];
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
            $errors[] = $this->language()->getPhrase('errors', $error);
        }
        
        return $errors;
    }
}

class NamodgAppException extends Exception {
    
}