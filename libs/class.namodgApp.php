<?php

require_once 'class.namodgApp.language.php';
require_once 'namodg/class.namodg.php';

class NamodgApp {
    
    private $_form = NULL;
    
    private $_template = NULL;
    
    private $_language = NULL;
    
    public function __construct( $config = array() ) {
        
        try {
            
            if ( ! is_array($config) || empty($config) ) {
                // What will happen ?
            }
            
            $this->_language = isset ($config['app']['language']) && $config['app']['language'] ? new NamodgLanguage($config['app']['language']) : new NamodgLanguage('ar');
            
            if ( ! isset ($config['app'], $config['namodg']) || ! is_array($config['app']) ) {
                throw new NamodgAppException('config_array_not_valid');
            }
            
        } catch (NamodgAppException $e) {
            echo $e->getMessage();
            
            //exit( $this->language()->getPhrase('errors', $e->getMessage()) );
        }
        
    }
    
    public function language() {
        return $this->_language;
    }
}

class NamodgAppException extends Exception {
    
}