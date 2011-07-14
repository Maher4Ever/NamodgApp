<?php

define('NAMODG_APP_DIR', realpath( dirname(__FILE__) . '/..' ) . '/');

require_once 'config.php';
require_once 'libs/class.namodgApp.php';

$app = new NamodgApp($config);

$app->addForm();