<?php

/**
 * C O O L W O R L D S . NET
 *
 * Script Name: coolContact - Contact Form
 * Version: 1.1
 * Author: Maher Salam
 * Author URI: http://www.coolworlds.net 
 */

session_start();

	header('Content-Type: text/html; charset=windows-1256');
	
	require_once('class.phpmailer.php');
	include 'config.php';
	
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) { // check ajax request
	
		$sender_name = $_POST['name'];
		
		$sender_email = $_POST['email'];
		
		$after_send = "<img alt=' „ «·≈—”«·' src='img/sent.png'>
		<h2> „ ≈—”«· —”«· ﬂ »‰Ã«Õ !!</h2>
		<p>Ì„ﬂ‰ﬂ «·¬‰ «·⁄Êœ… <a href='/'>··—∆Ì”Ì…</a> √Ê <a href='javascript:history.go(-1)'>··’›Õ… «· Ì ﬂ‰  »Â« „‰ ﬁ»·</a></p>";
	
	} else { // normal request
		
		$url= str_replace("send_core.php","", (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] );
	
		$sender_name = iconv("windows-1256", "UTF-8", $_POST['name']);
		
		$sender_email = iconv("windows-1256", "UTF-8", $_POST['email']);
		
		$after_send = "<meta http-equiv='refresh' content='0;url=sent.php'>";
	}			
		
		if(isset($_POST['submit'])) {
		
			if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['security']) && !empty($_POST['message'])) {
				
				if($_POST['security'] == $_SESSION['randResult']) {
						
						$mail = new PHPMailer();
						
						$message = $message_start;
						
						foreach($_POST as $key => $val){
							(isset($_SERVER['HTTP_X_REQUESTED_WITH']))? $val = iconv("UTF-8", "windows-1256", $val) : $val =  $val;
							if($key == 'name'){$key = '«·«”„';}
							if($key == 'email'){$key = '«·«Ì„Ì·';}
							if($key == 'message'){$key = '«·—”«·…';}
							if($key <> 'submit' && $key <> 'security'){
								$message .= "<tr valign='top' bgcolor='#ffffff'><td width='90' align='left' style='color: #989898;'><b>". $key . "</b></td><td>" . nl2br($val) . "</td></tr>";
								$messageNoHtml = $key . ": " .  $val . "\r\n";
							}
						}
						
						$message .= $message_end;	
						
						$mail->CharSet = 'UTF-8';
						
						$mail->SetFrom($sender_email, $sender_name);
						$mail->AddAddress($email);
						
						$mail->Subject = iconv("windows-1256", "UTF-8", $message_title);
						
						$mail->MsgHTML(iconv("windows-1256", "UTF-8", $message));
						$mail->AltBody = iconv("windows-1256", "UTF-8", $messageNoHtml);
						
						if(!$mail->Send()) {
						 	echo "ÕœÀ Œÿ√: " . $mail->ErrorInfo;
						} else {
						 	echo $after_send;
						}
						
		
				} else {
					echo "<div style='background-color: #f5f5f5;border: 3px solid #d9d9d9;margin: 90px auto 0px;padding: 5px 15px;width: 55%;height: 60px;' dir='rtl'>
					<h4 style='line-height: 60px;font-size: 17px;text-align: center;margin:0;color:#626060'>«·—Ã«¡ «· √ﬂœ „‰ ≈œŒ«· «·ÃÊ«» «·’ÕÌÕ ··”ƒ«·° <a style='color:#0080f7' href='" . $url . "'>≈÷€ÿ Â‰« ··⁄Êœ… ··’›Õ… «·”«»ﬁ…</a></h4>";
		
				}
		
			} else {
				echo "<div style='background-color: #f5f5f5;border: 3px solid #d9d9d9;margin: 90px auto 0px;padding: 5px 15px;width: 55%;height: 60px;' dir='rtl'>
				<h4 style='line-height: 60px;font-size: 17px;text-align: center;margin:0;color:#626060'>«·—Ã«¡  ⁄»∆… Ã„Ì⁄ «·»Ì«‰«  ›Ì «·Œ«‰«  «·„‰«”»…° <a style='color:#0080f7' href='" . $url . "'>≈÷€ÿ Â‰« ··⁄Êœ… ··’›Õ… «·”«»ﬁ…</a></h4>";
			}
		
		}

?>