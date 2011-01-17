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
		<meta name="description" content="����� �� ���� ��� ������ ������� �� �������� ��� ������">
		<title><?php echo $title;?></title>
		<link type="text/css" rel="stylesheet" href="css/style.css">
	</head>
	<body>
		<div id="wrapper">
			<div id="content-top"></div>
			<div id="content">
					<div id="badge">
						<div class="wrapper"><h1>����� ����</h1></div>
						<div class="corner"></div>
					</div>
					
					<div id="main">
						<form action="send_core.php" enctype="multipart/form-data" method="post" id="contact">
							<div class="name-wrapper">
								<p><label for="name">�����</label></p>
								<input id="name" class="required name" name="name" type="text" title="����� �����" >
							</div>
							
							<div class="email-wrapper">
								<p><label for="email">�������</label></p>
								<input id="email" class="required email" name="email" type="text" title="����ȡ ������ ����� ����� ����" >
							</div>
							
							<div class="security-wrapper">
								<p><label for="security"><acronym title="������ ��� ������ ������ �� �� ������ ����� ���� � ��� ����� ���� ������ ����� ������"><?php echo $rand1." + ".$rand2 ?></acronym></label></p>
								<input id="security" class="required security" name="security" type="text" title="������ ����� ������ ������ �����" >
							</div>
							
							<div class="message-wrapper">
								<p><label for="message">�������</label></p>
								<textarea id="message" class="required message" name="message" rows="11" cols="50" title="�����"></textarea>	
							</div>
							<div class="submit-wrapper">
								<button name="submit" type="submit">���� �������</button>
							</div>
							<div style="clear:both;"></div>
						</form>
					</div>
			</div> <!-- End .wrapper -->
			<div id="content-bottom">
									
					<!-- �� ������ - ������ ��� ������ -->
					<p id="rights">Developed & Designed by &copy; <a href="http://coolworlds.net" title="C O O L W O R L D S. NET" >coolworlds.net</a>, 2010 All rights reserved</p>
					<!-- ����� �� ������ - ������ ��� ������-->
					
			</div>
		</div>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		<script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.validate/1.6/jquery.validate.pack.js"></script>
		<script src="js/jquery.main.js" type="text/javascript"></script>
	</body>
</html>