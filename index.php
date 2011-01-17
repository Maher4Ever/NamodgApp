<?php session_start(); //To send the random-vars to the next page
$rand1 = rand(1,9); //First random number
$rand2 = rand(10,20); //Second random number
$_SESSION['randResult'] = $rand1 + $rand2; 
include 'config.php'; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html dir="rtl" lang="ar">
	<head>
		<meta content="text/html; charset=windows-1256" http-equiv="Content-Type">
		<meta name="copyright" content="&copy; coolworlds.net">
		<meta name="generator" content="coolContact 1.0">
		<meta name="description" content="يمكنك من خلال هذه الصفحة التواصل مع القائمين على الموقع">
		<title><?php echo $title;?></title>
		<link type="text/css" rel="stylesheet" href="css/style.css">
	</head>
	<body>
		<div id="wrapper">
			<div id="content-top"></div>
			<div id="content">
					<div id="badge">
						<div class="wrapper"><h1>تواصل معنا</h1></div>
						<div class="corner"></div>
					</div>
					
					<div id="main">
						<form action="send_core.php" enctype="multipart/form-data" method="post" id="contact">
							<div class="name-wrapper">
								<p><label for="name">الاسم</label></p>
								<input id="name" class="required name" name="name" type="text" title="الاسم مطلوب" >
							</div>
							
							<div class="email-wrapper">
								<p><label for="email">الايميل</label></p>
								<input id="email" class="required email" name="email" type="text" title="مطلوب، الرجاء إدخال ايميل صحيح" >
							</div>
							
							<div class="security-wrapper">
								<p><label for="security"><acronym title="يستخدم هذا السؤال للتأكد من أن المرسل إنسان بشري و ليس روبوت يقوم بإرسال نشرات دعائية"><?php echo $rand1." + ".$rand2 ?></acronym></label></p>
								<input id="security" class="required security" name="security" type="text" title="الرجاء إدخال الناتج الصحيح للجمع" >
							</div>
							
							<div class="message-wrapper">
								<p><label for="message">الرسالة</label></p>
								<textarea id="message" class="required message" name="message" rows="11" cols="50" title="مطلوب"></textarea>	
							</div>
							<div class="submit-wrapper">
								<button name="submit" type="submit">ارسل الرسالة</button>
							</div>
							<div style="clear:both;"></div>
						</form>
					</div>
			</div> <!-- End .wrapper -->
			<div id="content-bottom">
									
					<!-- نص الحقوق - الرجاء عدم إزالته -->
					<p id="rights">Developed & Designed by &copy; <a href="http://coolworlds.net" title="C O O L W O R L D S. NET" >coolworlds.net</a>, 2010 All rights reserved</p>
					<!-- نهاية نص الحقوق - الرجاء عدم إزالته-->
					
			</div>
		</div>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		<script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.validate/1.6/jquery.validate.pack.js"></script>
		<script src="js/jquery.main.js" type="text/javascript"></script>
	</body>
</html>