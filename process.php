<?php

/**
 * Namodg - Ajax contact form
 *
 * @author Maher Salam <admin@namodg.com>
 * @version 1.3.1
 * @copyright Copyright (c) 2010, Maher Salam
 *
 * Dual licensed under the MIT and GPL licenses:
 *   @license http://www.opensource.org/licenses/mit-license.php
 *   @license http://www.gnu.org/licenses/gpl.html
 */
 
// Include the classes
include 'inc/classes/class.namodg.php';
include 'inc/classes/class.namodg.factory.php';
include 'inc/classes/class.namodg.processor.php';

// Process the data with the processor
$data = new namodg_processor($_POST);

// Check ajax status and add it to a var
$ajax = $data->isAjax();

// Check the message status
$sent = $data->send();

// Start a new factory object
$factory = new namodg_factory;

if($sent) // If the message was sent, show the success page
{
	echo $factory->getMsg('success', $data->getSenderName(), $ajax);	
} 
elseif($data->validationError) // If there was any validation errors, show these errors
{		
	echo $factory->validationErrors($data->errors);	
} 
else // If everything else fail, show the error page
{
	echo $factory->getMsg('error', $data->getSenderName(), $ajax);
}