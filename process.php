<?php

require_once 'libs/namodg/class.namodg.php';

$form = new Namodg('aaa6dd417d1f99aa760caccdd2e2ef49');

if ( $form->canBeProcessed() ) {
    
    $form->validate();
    
    if ( $form->isDataValid() ) {
        echo 'Everything is valid!';
    } else {
        print_r($form->getValidationErrors());
    }
    
}