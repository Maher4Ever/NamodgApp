<?php

/**
 * Namodg - Ajax contact form
 *
 * @author Maher Salam <admin@namodg.com>
 * @version 1.3
 * @copyright Copyright (c) 2010, Maher Salam
 *
 * Dual licensed under the MIT and GPL licenses:
 *   @license http://www.opensource.org/licenses/mit-license.php
 *   @license http://www.gnu.org/licenses/gpl.html
 */

// من هذه الصفحة يمكنك تعديل النموذج الخاص بالإتصال و إضافة خانات خاصة بك
 
?>

<form action="process.php" method="post" id="contact">
<div>
	<label for="name">اسم المرسل :</label>
	<div class="shade">	
		<input name="الاسم" id="name" class="required name" type="text" title="الاسم مطلوب" >
	</div>
	
	<label for="email">البريد الإلكتروني :</label>
	<div class="shade">
		<input name="الايميل" id="email" class="required email" type="text" title="الرجاء إستخدام أيميل خاص بك، الحقل مطلوب" >
	</div>
	
	<label for="captcha">التحقق البشري :</label>
	<div class="shade">				
		<p id="question" title="يستخدم هذا السؤال للتأكد من أن المرسل إنسان بشري و ليس روبوت يقوم بإرسال نشرات دعائية"><?php echo $captcha ?></p>
		<input name="التحقق" id="captcha" class="required captcha" type="text" title="الرجاء إدخال الناتج الصحيح، حقل مطلوب" >
		<input type="hidden" name="مخفي" value="<?php echo $token ?>">
	</div>
	
	<label for="message">الرسالة :</label>
	<div class="shade">			
		<textarea name="الرسالة" id="message" class="required" title="يمكنك إضافة رسالتك هنا، الرسالة مطلوبة" cols="30" rows="10"></textarea>
	</div>
	
	<button type="submit">ارسال الرسالة</button>
</div>
</form>