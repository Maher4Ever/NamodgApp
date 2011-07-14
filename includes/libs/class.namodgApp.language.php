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

/**
 * Namodg Languages' Controller
 * 
 * Internationalization (I18n) layer. It's used to get phrases and info over
 * the selected language.
 * 
 * @package NamodgApp
 */
class NamodgAppLanguage {

    /**
     * Language ID
     *
     * @see NamodgLanguage::isCodeValid()
     * @var string (only two chars!)
     */
    private $_language = 'ar';
    
    /**
     * Language direction (Left to right ?)
     * 
     * @var boolean
     */
    private $_ltr = false;
    
    /**
     * The phrases of this language
     * 
     * @var array
     */
    private $_phrases = array();
    
    /**
     * Class errors.
     * 
     * @var array
     */
    private $_errors = array();
    
    /**
     * Initialize the language
     *
     * @param string $lang
     */
    public function __construct($lang) {
        
        $lang = trim($lang);
        
        try {
            
            if ( ! $this->_isLangCodeValid($lang) ) {
               throw new NamodgAppException('language_code_length_not_valid'); 
            }
            
            $this->_language = $lang;
            
        } catch( NamodgAppException $e ) {
            $this->_addError( $e->getMessage() );
        }
               
        $this->_loadPhrases();
    }
    
    /**
     * Phrases getter method
     * 
     * @param string $group the groups of phrases
     * @param string $id the id of the reqested phrase
     * @return string
     */
    public function getPhrase($group, $id) {
        return $this->_phrases[$group][$id];
    }
    
    /**
     * Phrases getter
     * 
     * @return array
     */
    public function getPhrases($group = NULL) {
        return $group ? $this->_phrases[ $group ] : $this->_phrases;
    }
    
    /**
     * Class errors getter.
     * 
     * @return array
     */
    public function getErrors() {
        return empty($this->_errors) ? FALSE : $this->_errors;
    }
    
    /**
     * Loads the language's phrases from the languages directory,
     * then adds them to the phrases array.
     */
    private function _loadPhrases() {
        
        $file = NAMODG_APP_DIR . 'languages/' . $this->_language . '.php';
        
        try {
            
            // Check language file
            if ( ! file_exists($file) ) { 
               if ( $this->_language == 'ar' ) {
                   
                   // If we are here, then the default language file is doesn't exist,
                   // so print an error and exit.
                   exit('NamodgApp Error: Default language file "ar.php" doesn\'t exist. Languages directory: "' . NAMODG_APP_DIR . 'languages"');
                   
               } else {
                   // The requested language is not the default, so just  throw an error
                   throw new NamodgAppException('language_file_not_found');
               }
            }
            
            include $file;

            if ( ! isset ($phrase) || ! is_array($phrase) ) {
                exit('NamodgApp Error: Phrases array "$phrase" couldn\'t be found inside the language file "' . $file . '"');
            }

            $this->_phrases = $phrase;

            if ( ! is_bool($language['ltr']) ) {
                $this->_addError('language_rtl_config_not_valid');
            } else {
                $this->_ltr = $language['ltr'];
            }
        
        } catch( NamodgAppException $e ) { // Language not found
            
            $this->_addError( $e->getMessage() );
            
            // Default to the default language
            $this->_language = 'ar';
            
            // Try to load the default language
            $this->_loadPhrases();
        } 
    }
    
    /**
     * Language direction getter (ltr ?)
     * 
     * @return boolean
     */
    public function isLTR() {
        return $this->_ltr;
    }

    /**
     * Checks the language ID, this ID has to be 2 charecters long
     * 
     * @param string $lang
     * @return boolean
     */
    private function _isLangCodeValid($lang) {
        
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
     * Adds errors to the errors' array
     * 
     * @param string $error 
     */
    private function _addError($error) {
        $this->_errors[] = $error;
    }
}