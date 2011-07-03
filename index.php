<?php 

require_once 'includes/bootstrap.php';

include 'includes/form.php';

if ( $app->isFormValid() ) {
    $app->showHome();
} else {
    $app->showRunErrors();
}
