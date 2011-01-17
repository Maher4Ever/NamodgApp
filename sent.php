<?php include 'config.php'; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html dir="rtl" lang="ar">
	<head>
		<meta content="text/html; charset=windows-1256" http-equiv="Content-Type">
		<title><?php echo $title;?></title>
		<link type="text/css" rel="stylesheet" href="css/style.css">
	</head>
	<body>
		<div id="wrapper">
			<div id="content-top"></div>
			<div id="content" class="success">
					<div id="badge">
						<div class="wrapper"><h1>تواصل معنا</h1></div>
						<div class="corner"></div>
					</div>
					
					<img alt="تم الإرسال" src="img/sent.png">
					
					<h2>تم إرسال رسالتك بنجاح !!</h2>
					<p>يمكنك الآن العودة <a href="http://<?php echo $_SERVER['SERVER_NAME']?>">للرئيسية</a></p>
					
					<div style="clear:both;"></div>
			</div> <!-- End .wrapper -->
			<div id="content-bottom">
									
					<!-- نص الحقوق - الرجاء عدم إزالته -->
					<p id="rights">Developed & Designed by &copy; <a href="http://coolworlds.net" title="C O O L W O R L D S. NET" >coolworlds.net</a>, 2010 All rights reserved</p>
					<!-- نهاية نص الحقوق - الرجاء عدم إزالته-->
					
			</div>
		</div>
	</body>
</html>