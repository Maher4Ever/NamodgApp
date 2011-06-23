<?php 

define('NAMODG_APP_DIR', realpath( dirname(__FILE__) ) . '/');

require_once 'libs/class.namodgApp.php';

$config = array(
    'app' => array(
        'language' => 's'
    ),
    'namodg' => array(
        
    )
);

$app = new NamodgApp($config);