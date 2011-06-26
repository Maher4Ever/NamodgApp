<?php

require_once 'libs/namodg/class.namodg.php';

$form = new Namodg(array(
    'key' => 'key'
));

/**
 * Check the data passed to this file. If it's not valid and can't be processed,
 * return the user to the index
 */
if ( ! $form->canBeProcessed() ) {
    header('Location: index.php');
    exit;
}

if ( $form->canBeProcessed() ) {
    
    $form->validate();
    
    if ( $form->isDataValid() ) {
        echo 'Everything is valid!';
    } else {
        print_r($form->getValidationErrors());
    }
    
}