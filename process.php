<?php

require_once 'includes/classes/namodg.class.php';

$form = new Namodg(array(
    'key' => 'key'
));

if ( $form->canBeProcessed() ) {
    
    $form->validate();
    
    if ( $form->isDataValid() ) {
        echo 'Everything is valid!';
    } else {
        print_r($form->getValidationErrors());
    }
    
}