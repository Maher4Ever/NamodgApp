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

// من هذه الصفحة يمكنك تعديل القسم الذي يظهر بعد المحتوى في المصدر
 
?>
		<div id="footer"></div>
	</div> <!-- #wrapper -->
	
	<?php 
	#############################################################
	# بداية حقوق نموذج - الرجاء عدم الإزالة.
	#############################################################
	?>
	<p id="rights">
		<a href="http://coolworlds.net">
			<img id='coolworlds-logo' src="images/coolworlds-logo.png" alt="coolworlds.net" title="coolworlds.net">
		</a> - Powered by <a href="http://namodg.com" title="الموقع الرسمي للحصول على نسختك الخاصة من 'نموذج' - مجاناً">namodg 1.3.1</a>. All rights reserved.
	</p>
	<?php 
	#############################################################
	# نهاية حقوق نموذج - الرجاء عدم الإزالة.
	#############################################################
	?>
	
	<?php if ( $ajax ) : ?>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
	<script type="text/javascript" src="js/namodg.validation.js"></script>
	<script type="text/javascript" src="js/namodg.main.js"></script>
	<?php endif; ?>
</body>

</html>