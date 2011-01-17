<?php

/**
 * Namodg - Ajax contact form
 *
 * @author Maher Salam <admin@namodg.com>
 * @version 1.3
 * @copyright Copyright (c) 2010, Maher Salam
 *
 * Dual licensed under the MIT and GPL licenses:
 *   @license http://www.opensource.org/licenses/mit-license.php
 *   @license http://www.gnu.org/licenses/gpl.html
 */

/**
 * The main Namodg class
 *
 * @package Namodg
 * @since Namodg 1.3
 */
abstract class namodg {

    /**
     * @var float The current version of Namodg
     */
    public $version = 1.3;

    /**
     * @var string Include path for all core files
     */
    protected $_incPath = '';
    
    /**
     * @var array The config array for the whole script
     */
    protected $_config = array();

    /**
     * @var string The language of the interface
     */
    protected $_lang = '';

    /**
     * @var array The phrases used in the interface
     */
    protected $_phrase = array();

    /**
     * Fill some important vars at the runtime
     *
     * @method __construct
     * @param array $config The configuration for the present runtime
     */
    public function __construct($config = array()) {
        $this->_incPath = realpath(dirname(__FILE__) . '/../') . '/';
        $this->_config = (!empty($config) && is_array($config) ) ? $config : $this->_getVar('config.php', 'config');
        $this->_lang = $this->_config['language'];
        $this->_phrase = $this->_getVar('phrases-' . $this->_lang . '.php', 'phrase');
    }

    /**
     * Abstract method, returns a factory object so that it's not needed to include and initialize objects
     *
     * @return object
     */
    public function factory() {
        return $this->_getInstance('factory');
    }

    /**
     * Abstract method, returns a processor object so that it's not needed to include and initialize objects
     *
     * @return object
     */
    public function process($data) {
        return $this->_getInstance('processor', $data);
    }

    /**
     * Get a var from a file inside the include path
     *
     * @param string $file The file to ge the var from
     * @param string $varName The var name to return
     * @return mixed 
     */
    private function _getVar( $file, $varName ) {

        $file = $this->_incPath . $file;

        if ( ! is_file( $file ) ) {
            ( $this->_lang == 'ar' ) ? die( 'الملف: ' . basename( $file ) . ' غير موجود في مجلد "inc"' ) : die( 'File: ' . basename( $file ) . ' does not exist in the "inc" folder.' );
        }

        include $file;

        return $$varName;
    }

    /**
     * Abstract method, return an object
     *
     * @param object $name The object to return
     * @param mixed $arg Arguments passed to the returned class
     * @return object
     */
    private function _getInstance($name, $arg = '') {

        $class = 'namodg_' . $name;

        // Check if the object is already saved in the script and return it in that case.
        if ( isset( $this->_{$name} ) && ( $this->_{$name} instanceof $class ) ) {
            return $this->_{$name};
        }

        include $this->_incPath . 'classes/class.namodg.' . $name . '.php';
        $this->_{$name} = empty($arg) ? new $class : new $class($arg);
        return $this->_{$name};
    }
	
	/**
     * Return the ajax configuration
     *
     * @return boolean
     */
    public function isAjax() {
        return $this->_config['ajaxEnabled'];
    }

}