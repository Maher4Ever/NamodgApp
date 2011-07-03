<?php

require_once 'includes/bootstrap.php';

/**
 * Check the data passed to this file. If it's not valid and can't be processed,
 * redirect the user to the index
 */
if ( ! $app->form()->canBeProcessed() ) {
    header('Location: index.php');
}

/**
 * Validate the data
 */
$app->form()->validate();

if ( ! $app->form()->isDataValid() ) {
    $app->showValidationFailure();
}

$app->sendEmail();

if ( $app->isEmailSent() ) {
    $app->showSendConformation();   
} else {
    $app->showSendFailure();
}