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
 * Define a constant pointing to NamodgApp dir. It's used internally to include libs correctly
 */
define('NAMODG_APP_DIR', realpath( dirname(__FILE__) . '/..' ) . '/');

/*
 * Include dependencies
 */
require_once 'config.php';
require_once 'libs/class.namodgApp.php';

/**
 * Initialize NamodgApp and assign it to $app.
 * $app is now the main and central controller for this application.
 */
$app = new NamodgApp($config);