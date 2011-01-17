<?php session_start();

	header('Content-Type: text/html; charset=windows-1256');
	
	include 'config.php';
	
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) { // check ajax request
	
		$sender_mail_headers = "From: ". iconv("UTF-8", "windows-1256", $_POST['name']) ."<" . iconv("UTF-8", "windows-1256", $_POST['email']) . ">" . "\r\n";
		
		$after_send = "<img alt=' „ «·≈—”«·' src='img/sent.png'>
		<h2> „ ≈—”«· —”«· ﬂ »‰Ã«Õ !!</h2>
		<p>Ì„ﬂ‰ﬂ «·¬‰ «·⁄Êœ… <a href='http://" . $_SERVER['SERVER_NAME'] . "'>··—∆Ì”Ì…</a> √Ê <a href='javascript:history.go(-1)'>··’›Õ… «· Ì ﬂ‰  »Â« „‰ ﬁ»·</a></p>";
	
	} else { // normal request
		
		$url= str_replace("send_core.php","", (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] );
	
		$sender_mail_headers = "From: ". $_POST['name'] ."<" . $_POST['email'] . ">" . "\r\n";
		
		$after_send = "<meta http-equiv='refresh' content='0;url=sent.php'>";
	}			
		
		if(isset($_POST['submit'])) {
		
			if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['security']) && !empty($_POST['message'])) {
				
				if($_POST['security'] == $_SESSION['randResult']) {
	
						$message = $message_start;
						
						foreach($_POST as $key => $val){
							(isset($_SERVER['HTTP_X_REQUESTED_WITH']))? $val = iconv("UTF-8", "windows-1256", $val) : $val =  $val;
							if($key == 'name'){$key = '«·«”„';}
							if($key == 'email'){$key = '«·«Ì„Ì·';}
							if($key == 'message'){$key = '«·—”«·…';}
							if($key <> 'submit' && $key <> 'security'){
								$message .= "<tr valign='top' bgcolor='#ffffff'><td width='90' align='left' style='color: #989898;'><b>". $key . "</b></td><td>" . nl2br($val) . "</td></tr>";
							}
						}
			
						$message .= $message_end;
						
						$mail_headers = $sender_mail_headers;
						$mail_headers .= 'MIME-Version: 1.0' . "\r\n";
						$mail_headers .= 'Content-type: text/html; charset=windows-1256' . "\r\n";
			
						mail($email, $message_title, $message, $mail_headers);
						
						echo $after_send;
		
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