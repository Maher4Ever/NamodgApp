<?php

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

/**
 * Set the rules for all renderers
 */
interface Renderable {

    public function getTag();

    public function addAttr($id, $value);

    public function getAttr($id);

    public function render();
}

/**
 * The blueprint of all renderers
 *
 * @desc Set the default behavior of render objects. It has extra helper methods
 *       like addClass() and setID(). The renderer can be used to render any HTML tag.
 */
abstract class NamodgRenderer implements Renderable {

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
     * @param string $attrID
     * @param stinrg $value
     */
    public function addAttr($attrID, $value) {

        $attrID = strtolower($attrID);

        if ($attrID == 'id') {
            return $this->setID($value);
        }

        $this->_attrs[$attrID] = $value;
    }

    /**
     * Atrribute getter
     *
     * @param string $attrID
     * @return string
     */
    public function getAttr($attrID) {
        return isset($this->_attrs[$attrID]) ? $this->_attrs[$attrID] : NULL;
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
     */
    public function setID($idValue) {
        $this->_attrs['id'] = $idValue;
    }

    /**
     * Adds CSS classes to the tag
     *
     * @param string $class
     */
    public function addClass($class) {
        if ( isset($this->_attrs['class']) ) {
          $this->_attrs['class'] .= ' ' . $class;
        } else {
            $this->addAttr('class', $class);
        }

    }
}