<?php

require_once 'libs/namodg/class.namodg.php';

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