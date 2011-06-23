<?php

require_once 'class.namodgApp.language.php';
require_once 'namodg/class.namodg.php';

class NamodgApp {
    
    private $_form = NULL;
    
    private $_template = NULL;
    
    private $_language = NULL;
    
    private $_errors = array();
    
    public function __construct( $config = array() ) {
        
        try {
            
            if ( ! is_array($config) ) {
                throw new NamodgAppException('config_not_array');
            }
            
            if ( ! isset ($config['app'], $config['namodg']) || ! is_array($config['app']) ) {
                throw new NamodgAppException('config_array_not_valid');
            }
            
            $this->_language = isset ($config['app']['language']) && $config['app']['language'] ? $config['app']['language'] : 'ar';
            $this->_loadLanguage();
            
            $this->_form = new Namodg($config['namodg'], true);
            
            if ( $this->form()->getFatalError() ) {
                $this->_addError( $this->form()->getFatalError() );
            }
            
            if ( ! empty($this->_errors) ) {
                print_r($this->_errors);
            }
            
        } catch ( NamodgAppException $e ) {
            // Default to something and show the errors!
            $this->_addError($e->getMessage());
            if ( ! empty($this->_errors) ) {
                print_r($this->_errors);
            }
        }
    }
    
    public function language() {
        return $this->_language;
    }
    
    public function form() {
        return $this->_form;
    }
    
    private function _loadLanguage() {
        $this->_language = new NamodgLanguage($this->_language);
        
        if ( $this->language()->getErrors() ) {
            $this->_addError( $this->language()->getErrors() );
        }
    }
    
    private function _addError($error) {
        if ( is_array($error) ) {
            foreach($error as $oneError) {
                $this->_addError($oneError);
            }
        } else {
            $this->_errors[] = $error;
        }        
    }
}

class NamodgAppException extends Exception {
    
}