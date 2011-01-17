<?php

/*
 * C O O L W O R L D S . NET
 *
 * Script Name: coolContact - Contact Form
 * Version: 1.2
 * Date: 30/07/2010
 * Author: Maher Salam
 * Author's URL: http://www.coolworlds.net 
 * 
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */

session_start();

$start_time = microtime(true);

register_shutdown_function('my_shutdown');

header('Content-Type: text/html; charset=windows-1256');

// Errors - Wrap errors with tags
$error_begin = "<div style='background-color: #f5f5f5;border: 3px solid #d9d9d9;margin: 90px auto 0px;padding: 5px 15px;width: 55%;height: 60px;' dir='rtl'><h4 style='line-height: 60px;font-size: 17px;text-align: center;margin:0;color:#626060'>";
$error_end = '</h4></div>';
$url = str_replace('inc/send_core.php','', (!empty($_SERVER['HTTPS'])) ? 'https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] );
$error_end_back = "¡ <a style='color:#0080f7' href='" . $url . "'>ÅÖÛØ åäÇ ááÚæÏÉ ááÕİÍÉ ÇáÓÇÈŞÉ</a></h4></div>";

function errorWrap($text) {
	global $error_begin, $error_end;
	$error = $error_begin . $text . $error_end ;
	return $error;
}

function errorBackWrap($text){
	global $error_begin, $error_end_back;
	$error = $error_begin . $text . $error_end_back ;
	return $error;
}

// Get Files - Get necessary files and check if they exist
 
if(file_exists(dirname(__FILE__) . '/class.phpmailer-lite.php')){
	require_once(dirname(__FILE__) . '/class.phpmailer-lite.php');
} else {
	die(errorWrap('ÎØÃ İí ÇáÊÑßíÈ: ãáİ class.phpmailer-lite.php ÛíÑ ãæÌæÏ Ãæ áã íÊã ÇáÚËæÑ Úáíå')) ;
}

if(file_exists(dirname(__FILE__) . '/config.php')){
	include dirname(__FILE__) . '/config.php';
} else {
	die(errorWrap(' ÎØÃ İí ÇáÊÑßíÈ: ãáİ ÇáÅÚÏÇÏÇÊ config.php ÛíÑ ãæÌæÏ Ãæ áã íÊã ÇáÚËæÑ Úáíå' ));
}

if(isset($_POST['hidden'])) { // Check if the form was submited before proceeding

// ID - Generate unique id for every sender

$id_var = $_SESSION['key'];
$id_key = hash('md5', $id_var);
$id = 'id_' . $id_key;

// Sanitize - A variable cleaning function

function clean_var($variable) {
    $variable = strip_tags(stripslashes(trim(rtrim($variable))));
  return $variable;
}

// Encoding - Change the encoding of strings

function utf8($var) {
	$var = iconv('windows-1256', 'UTF-8', $var);
	return $var;
}

function ar($var) {
	$var = iconv('UTF-8', 'windows-1256', $var);
	return $var;
}

//	Exploits - Identify some common exploits
	
$inpt_expl = '/(content-type|to:|bcc:|cc:|document.cookie|document.write|onclick|onload)/i';

// Identify & Define - Check the type of the request (assume ajax)

(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) ? $ajax = true : $ajax = false ;

$sender_name = clean_var($_POST['name']);

$sender_email = strtolower(clean_var($_POST['email']));

$after_send = "<img alt='Êã ÇáÅÑÓÇá' src='img/sent.png'>
<h2>Êã ÅÑÓÇá ÑÓÇáÊß ÈäÌÇÍ !!</h2>
<p>íãßäß ÇáÂä ÇáÚæÏÉ <a href='/'>ááÑÆíÓíÉ</a> Ãæ <a href='javascript:history.go(-2)'>ááÕİÍÉ ÇáÊí ßäÊ ÈåÇ ãä ŞÈá</a></p>";

$dangerous_error = "<img class='error_img' alt='ÎØÃ' src='img/error.png'>
<h2>Êã ÅßÊÔÇİ ãÍÇæáÉ ÅÓÊÛáÇá !!</h2>
<p>Şã ÈÇáÚæÏÉ ááÎáİ ãä ÎáÇá ÒÑ ÇáÚæÏÉ İí ÇáãÊÕİÍ</p>";

$send_error = "<img class='error_img' alt='ÎØÃ' src='img/error.png'>
<h2>ÚĞÑÇğ¡ ÍÏË ÎØÃ ÚäÏ ÇáÅÑÓÇá.</h2>
<p>ÇáÑÌÇÁ ÇáÚæÏÉ áÕİÍÉ <a href='javascript:history.go(-1)'>ÇáÓÇÈŞÉ</a> æ ãÍÇæáÉ ÇáÅÑÓÇá ãÑÉ ÃÎÑì</p>";

if(!$ajax) { // if normal request
	
	$sender_name = utf8(clean_var($_POST['name']));
	
	$sender_email = utf8(strtolower(clean_var($_POST['email'])));
	
	$after_send = "<meta http-equiv='refresh' content='0;url=../sent.php'>";
	
	$dangerous_error = errorWrap("<strong style='color: red'>ÊÍĞíÑ</strong>: Êã ÅßÊÔÇİ ãÍÇæáÉ ÅÓÊÛáÇá!");
	
	$send_error = errorBackWrap('ÚĞÑÇğ¡ ÍÏË ÎØÃ ÚäÏ ÇáÅÑÓÇá');
}			

if($message_title == '' || empty($message_title) || !$message_title) $message_title = 'ÑÓÇáÉ ÌÏíÏÉ ãä ' . ar($sender_name) . ' | coolContact';

$message_start = "<div dir='rtl' style='padding: 50px 0 100px;background: #eeeeee; font-family: Arial, Helvetica, sans-serif;'>
<h1 align='center' style='font-size: 24px; font-weight: bold;color: #989898;margin-bottom: 35px'>$message_title</h1>
<table width='600' align='center' border='1' style='border-collapse: collapse; border: 1px solid #dddddd;font-size: 16px;' cellpadding='14' cellspacing='2'>";

$message_end = "</table><p style='margin:0;color:#CACACA;font-size:10px;padding-top:20px;text-align:center;'><a style='color:#CACACA;text-decoration:none;' href='http://coolcontact.co.cc'>coolContact v1.2</a> - Developed &amp; Designed by Maher Salam, &copy; <a style='color:#CACACA;text-decoration:none;' href='http://coolworlds.net'>coolworlds.net</a></p></div>";
	
// Check - Execute some basic checks for the normal requests

if(!$ajax) { // checks for normal request
	
	if(empty($sender_name) || empty($sender_email) || empty($_POST['security']) || empty($_POST['message'])) { // check empty input values
		
		die(errorBackWrap('ÇáÑÌÇÁ ÊÚÈÆÉ ÌãíÚ ÇáÈíÇäÇÊ İí ÇáÎÇäÇÊ ÇáãäÇÓÈÉ'));
		
	} elseif($_POST['security'] != $_SESSION['randResult'] ) { // check spam code
		
		die(errorBackWrap('ÇáÑÌÇÁ ÇáÊÃßÏ ãä ÅÏÎÇá ÇáÌæÇÈ ÇáÕÍíÍ ááÓÄÇá'));
		
	} elseif(!eregi('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,6})', $sender_email)) { // check email
		
		die(errorBackWrap('ÇáÑÌÇÁ ÅÓÊÎÏÇã Ãíãíá ÕÍíÍ'));
		
	}
} 

// Function - Start the sending process

if(preg_match($inpt_expl, $sender_name) || preg_match($inpt_expl, $sender_email) || preg_match($inpt_expl, $_POST['message']) || $_POST['hidden'] != $id ) { // check dangerous inputs
	
	echo $dangerous_error;
	
} else { // then everything is clean
			
	$mail = new PHPMailerLite();
	$mail->Mailer = 'mail';
	
	$message = $message_start;
	
	$messageNoHtml;
	
	foreach($_POST as $key => $val){
		if($ajax) $val = ar($val);
		if($key == 'name') $key = 'ÇáÇÓã';
		if($key == 'email'){$key = 'ÇáÇíãíá'; $val = strtolower($val);}
		if($key == 'message') $key = 'ÇáÑÓÇáÉ';
		if($key <> 'submit' && $key <> 'security' && $key <> 'hidden'){
			$message .= "<tr valign='top' bgcolor='#ffffff'><td width='90' align='left' style='color: #989898;'><b>". $key . '</b></td><td>' . nl2br(clean_var($val)) . '</td></tr>';
			$messageNoHtml .= $key . ': ' . nl2br(clean_var($val)) . '\r\n';
		}
	}
	
	$message .= $message_end;	
	
	$mail->CharSet = 'UTF-8';
	
	$mail->SetFrom($sender_email, $sender_name);
	$mail->AddAddress($email);
	
	$mail->Subject = utf8($message_title);
	$mail->IsHTML(true); 
	
	$mail->Body = utf8($message);
	$mail->AltBody = utf8($messageNoHtml);
	

}

} // End submit check

function my_shutdown() {
	global $start_time;

	echo 'execution took: '.
			(microtime(true) - $start_time).
			' seconds.';
}

?>