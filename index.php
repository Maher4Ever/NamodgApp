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

// Start a new factory object
$builder = new namodg_factory;

$version = $builder->version;
$title = $builder->title;
?>

<?php include 'template/header.php'; ?>

<div id="header">
	<h1><img src="images/header/message-icon.png" alt="message">تواصل معنا</h1>
	<div id="header-right"></div>
	<div id="header-left"></div>
</div>
<div id="content">

<?php 

// Generate the form
echo $builder->generateForm();

?>

</div>

<?php  

$ajax = $builder->isAjax();

include 'template/footer.php'; ?>