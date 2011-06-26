<?php

require_once 'namodg/class.namodg.defaultRenderers.php';

class NamodgAppFormRenderer extends NamodgFormRenderer {
    
}

class NamodgAppTemplate {
    
    private $_tpl = NULL;
    
    public function __construct($config) {
        $this->_tpl = new RainTPL();
        
        $this->_tpl()->configure('tpl_dir', 'templates/' . $config['template'] . '/');
        $this->_tpl()->configure('cache_dir', 'cache/');
        
        $this->_tpl()->assign('title', $config['page_title'] );
        $this->_tpl()->assign('description', $config['description'] );
        $this->_tpl()->assign('form_title', $config['form_title'] );
        $this->_tpl()->assign('version', Namodg::version);
    }
    
    public function assign($id, $value) {
        $this->_tpl()->assign($id, $value);
    }
    
    public function showHome() {
        $tpl->assign('form_open', $form->getOpeningHTML());
        $tpl->assign('selected', $form->getPhrase('misc', 'selected'));
        $tpl->assign('fields', $form->getFieldsAsArray());
        $tpl->assign('form_close', $form->getClosingHTML());

        $tpl->draw('home');
    }
    
    private function _tpl() {
        return $this->_tpl;
    } 
    
}