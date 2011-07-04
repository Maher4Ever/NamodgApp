<?php

/**
 * Namodg - Ajax Forms Generator
 *
 * @desc Namodg allows developers to make ajax-driven forms easily. It uses OOP aproach,
 *       which means developers has to write less code!
 * @author Maher Salam <admin@namodg.com>
 * @link http://namodg.com
 * @copyright Copyright (c) 2010-2011, Maher Salam
 *
 * Dual licensed under the MIT and GPL licenses:
 *   @license http://www.opensource.org/licenses/mit-license.php
 *   @license http://www.gnu.org/licenses/gpl.html
 */

/****************************************************************
الخيارات الأساسية - الرجاء تعديل الخيارات التالية ليعمل السكربت
/***************************************************************/

// أيميل مستلم الرسائل - في أغلب الأحيان هو أيميل صاحب الموقع
$config['app']['email'] = 'maher@localhost';

// المفتاح الخاص بسكربتك - الرجاء التوجه للصفحة التالية للحصول على مفتاحك
// http://namodg.com/keys
$config['namodg']['key'] = 'fsdf#RFsdfer3245';


/****************************************************************
الخيارات الإضافية - خيارات تخصيص السكربت
/***************************************************************/

// عنوان صفحة السكربت الرئيسية
$config['app']['home_title'] = 'تواصل معنا';

// عنوان النموذج - هذا العنوان يظهر فوق النموذج و يدل على الغرض منه
$config['app']['form_title'] = 'تواصل معنا';

// عنوان الرسالة المستلمة - قم بتغييره [ فقط ] إن كنت تريد تغيير العنوان الإفتراضي
$config['app']['message_title'] = 'رسالة جديدة من "نموذج"';


/****************************************************************
الخيارات المتقدمة - الرجاء عدم تعديلها إلا إذا كنت تعلم جيداً ما تعمله 
/***************************************************************/

// رابط الملف الي يقوم بمعالجة طلب الإرسال
$config['namodg']['url'] = 'send.php';

// لغة السكربت
$config['app']['language'] = 'en';

/*
أسم حقل البريد الإلكتروني الذي سيستعمل عند الرد على الرسائل - يستخدم عندما تقوم بطلب أكثر من أيميل من المستخدم
و تريد إن تحدد الإيميل الذي ستقوم بإرسال ردك إليه.
*/
$config['app']['replay_to_field_name'] = 'الايميل';
