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
 * Start NamodgApp
 */
require_once 'includes/bootstrap.php';

/**
 * Check the data passed to this file. If there is no data or the data
 * can't be decrypted using the key, redirect the user to the homepage.
 */
if ( ! $app->form()->canBeProcessed() ) {
    header('Location: index.php');
}

/**
 * Validate the data
 */
$app->form()->validate();

/**
 * Check the validation status
 */
if ( ! $app->form()->isDataValid() ) {
    
    /**
     * Show the validation errors page
     */
    $app->showValidationFailure();
    
} else {
    
    /**
     * Send the email
     */
    $app->sendEmail();
    
    /**
     * Check the send send status
     */
    if ( $app->isEmailSent() ) {
        
        /**
         * Show send conformation page
         */
        $app->showSendConformation();  
        
    } else {
        
        /**
         * Show send failure page
         */
        $app->showSendFailure();
        
    }   
    
}