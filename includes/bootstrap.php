<?php

define('NAMODG_APP_DIR', realpath( dirname(__FILE__) . '/..' ) . '/');

require_once 'config.php';
require_once 'libs/class.namodgApp.php';

$config = array(
    'app' => array(
        'language' => 'ar',
        'email' => 'maher@localhost'
    ),
    'namodg' => array(
        'key' => 'daslnd#$addas3842',
        'url' => 'send.php'
    )
);

$app = new NamodgApp($config);