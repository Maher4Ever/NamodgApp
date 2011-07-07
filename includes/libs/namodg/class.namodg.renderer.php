<?php

/**
 * Namodg - Form Generatorr 
 * ========================
 * 
 * Namodg is a class which allows to easily create, render, validate and process forms
 * 
 * @author Maher Sallam <admin@namodg.com>
 * @link http://namodg.com
 * @copyright Copyright (c) 2010-2011, Maher Sallam
 *
 * Dual licensed under the MIT and GPL licenses:
 *   @license http://www.opensource.org/licenses/mit-license.php
 *   @license http://www.gnu.org/licenses/gpl.html
 */

/**
 * Set the rules for all renderers. 
 * 
 * @package Namodg
 * @subpackage NamodgRenderer
 */
interface NamodgRenderer {

    public function getTag();

    public function addAttr($id, $value);

    public function getAttr($id);

    public function render();
}

/**
 * The blueprint of all renderers. It sets the default behavior of render objects. It has extra helper methods
 * like addClass() and setID(). This renderer can be used to render any HTML tag.
 * 
 * @package Namodg
 * @subpackage NamodgRenderer
 */
abstract class NamodgRenderer_Base implements NamodgRenderer {

    /**
     * HTML Tag container
     * @var string
     */
    private $_tag = NULL;

    /**
     * Tag attributes
     *
     * @var array
     */
    private $_attrs = array();

    /**
     * Initialize the renderer
     *
     * @param string $tag
     */
    public function __construct($tag) {
        $this->_tag = (string)$tag;
    }

    /**
     * Tag getter
     * 
     * @return string
     */
    public function getTag() {
        return $this->_tag;
    }

    /**
     * Adds a new attribute to the attrs array
     * 
     * @param string $name
     * @param stinrg $value
     * @return $this Allows chaining
     */
    public function addAttr($name, $value) {

        $name = strtolower($name);

        if ($name == 'id') {
            $this->setID($value);
        } else {
            $this->_attrs[$name] = $value;
        }
        
        return $this;
    }

    /**
     * Atrribute getter
     *
     * @param string $name
     * @return string
     */
    public function getAttr($name) {
        return isset($this->_attrs[$name]) ? $this->_attrs[$name] : NULL;
    }

    /**
     * Returns an array that contains all the tag's attributes
     * 
     * @return array
     */
    public function getAllAttrs() {
        return $this->_attrs;
    }

    /**
     * Tag ID setter
     * 
     * @param string $idValue
     * @return $this Allows chaining
     */
    public function setID($idValue) {
        $this->_attrs['id'] = $idValue;
        return $this;
    }

    /**
     * Adds CSS classes to the tag
     *
     * @param string $class
     * @return $this Allows chaining
     */
    public function addClass($class) {
        if ( isset($this->_attrs['class']) ) {
            $this->_attrs['class'] .= ' ' . $class;
        } else {
            $this->addAttr('class', $class);
        }
        return $this;
    }
}