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
 * Start NamodgApp
 */
require_once 'includes/bootstrap.php';

/**
 * Add the users' form and fields
 */
$app->addForm();

/**
 * Render the homepage
 */
$app->showHome();
