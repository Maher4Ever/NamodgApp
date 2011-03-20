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
 * Namodg Language
 *
 * @desc Allow the translation of Namodg later on
 */
class NamodgLanguage {

    /**
     * Language ID
     *
     * @see NamodgLanguage::isCodeVlid()
     * @var string only two chars!
     */
    private $_language = NULL;

    /**
     * The path to the translation files
     *
     * @var string
     */
    private $_dir = NULL;

    /**
     * The phrases of this language
     * 
     * @var array
     */
    private $_phrases = array();


    /**
     * Initialize the language
     *
     * @param string $lang
     */
    public function __construct($lang) {
        if ( $this->isCodeValid($lang) ) {
            $this->_language = (string)$lang;
        }
    }

    /**
     * Takes a translation array name and a directory then it fills the phrases with this array
     *
     * @param string $arrayName
     * @param string $folder
     */
    public function parseArrayFromFolder($arrayName, $folder) {

        $this->_dir = $folder . DIRECTORY_SEPARATOR;

        $file = $this->_dir . $this->_language . '.php';

        if ( ! $this->doesFileExsists() ) {
            $file = $this->_dir . 'ar.php';
        }

        include $file;

        $phrases = $$arrayName;

        if (is_array($phrases)) {
            $this->_phrases = $phrases;
        }
    }

    /**
     * Check the language ID, this ID has to be 2 charecters long
     * 
     * @param string $lang
     * @return boolean
     */
    public function isCodeValid($lang = '') {
        if ( empty($lang) ) {
            $lang = $this->_language;
        }

        /**
         * ISO 639-1 languages codes
         * 
         * @link http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
         */
        $langCodes = array (
            'ab', 'aa', 'af', 'ak', 'sq', 'am', 'ar', 'an', 'hy', 'as', 'av', 'ae', 'ay', 'az', 'bm', 'ba', 'eu', 'be', 'bn', 'bh', 'bi',
            'bs', 'br', 'bg', 'my', 'ca', 'ch', 'ce', 'ny', 'zh', 'cv', 'kw', 'co', 'cr', 'hr', 'cs', 'da', 'dv', 'nl', 'dz', 'en', 'eo',
            'et', 'ee', 'fo', 'fj', 'fi', 'fr', 'ff', 'gl', 'ka', 'de', 'el', 'gn', 'gu', 'ht', 'ha', 'he', 'hz', 'hi', 'ho', 'hu', 'ia',
            'id', 'ie', 'ga', 'ig', 'ik', 'io', 'is', 'it', 'iu', 'ja', 'jv', 'kl', 'kn', 'kr', 'ks', 'kk', 'km', 'ki', 'rw', 'ky', 'kv',
            'kg', 'ko', 'ku', 'kj', 'la', 'lb', 'lg', 'li', 'ln', 'lo', 'lt', 'lu', 'lv', 'gv', 'mk', 'mg', 'ms', 'ml', 'mt', 'mi', 'mr',
            'mh', 'mn', 'na', 'nv', 'nb', 'nd', 'ne', 'ng', 'nn', 'no', 'ii', 'nr', 'oc', 'oj', 'cu', 'om', 'or', 'os', 'pa', 'pi', 'fa',
            'pl', 'ps', 'pt', 'qu', 'rm', 'rn', 'ro', 'ru', 'sa', 'sc', 'sd', 'se', 'sm', 'sg', 'sr', 'gd', 'sn', 'si', 'sk', 'sl', 'so',
            'st', 'es', 'su', 'sw', 'ss', 'sv', 'ta', 'te', 'tg', 'th', 'ti', 'bo', 'tk', 'tl', 'tn', 'to', 'tr', 'ts', 'tt', 'tw', 'ty',
            'ug', 'uk', 'ur', 'uz', 've', 'vi', 'vo', 'wa', 'cy', 'wo', 'fy', 'xh', 'yi', 'yo', 'za', 'zu'
        );

        return (strlen($lang) == 2 && in_array($lang, $langCodes));
    }

    /**
     * Checks the translation file
     *
     * @return boolean
     */
    public function doesFileExsists() {
         $file = $this->_dir . $this->_language . '.php';
         return file_exists($file);
    }

    /**
     * Phrases getter
     * 
     * @return array
     */
    public function getPhrases() {
        return (array)$this->_phrases;
    }
}