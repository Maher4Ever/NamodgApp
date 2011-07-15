<?php

/**
 * NamodgApp - A beautiful ajax form
 * ========================
 * 
 * NamodgApp is customizable, configurable, ajax application which can be used
 * to recieve data from users. It's form is generated using Namodg which allows
 * developers to eaisly extend and change the functionality of NamodgApp.
 * 
 * @author Maher Sallam <admin@namodg.com>
 * @link http://namodg.com
 * @copyright Copyright (c) 2010-2011, Maher Sallam
 *
 * Dual licensed under the MIT and GPL licenses:
 *   @license http://www.opensource.org/licenses/mit-license.php
 *   @license http://www.gnu.org/licenses/gpl.html
 */

/****************************************************************
الخيارات الأساسية - الرجاء تعديل الخيارات التالية ليعمل السكربت
/***************************************************************/

/*
 * ايميل مستلم الرسالة
 * 
 * هذا الإيميل سيستلم الرسائل المرسلة من قبل نموذج. يمكنك إستخدام أي أيميل هنا من أي مزود
 * بريد إلكتروني أمثال (Gmail, Hotmail, Yahoo..). كما يمكنك إستخدام إيميل على نطاق موقعك
 * (إنظر لخيار إيميل نموذج)
 */
$config['app']['to_email'] = 'maher@localhost';

/* 
 * ايميل نموذج
 * 
 * هذا الإيميل سيستخدم كإيميل مرسل الرسالة. الرجاء إستخدام إيميل يحتوي على نفس نطاق (domain)
 * موقعك. مثلاً: إن كنت تريد تركيب نموذج على موقع www.example.com فالرجاء إستخدام إيميل
 * كـ: admin@example.com. أن كنت لا تستطيع ذلك فقم بإستخدام نفس إيميل مستلم الرسالة.
 * 
 * ملاحظة: إعداد هذا الخيار إلى إيميل على نفس نطاق موقعك سيحمي الرسائل التي تصلك من نموذج
 * من فرزها كرسالة مزعجة (spam) بنسبة كبيرة جداً.
 */
$config['app']['from_email'] = 'namodg@localhost';

/* 
 * مفتاح نموذج
 * 
 * مفتاح نموذج هو عبارة عن مجموعة من الأحرف و الأرقام العشوائية. المفتاح يستخدم لتشفير
 * أجزاء من بيانات نموذج لضمان عدم العبث بها.
 * 
 * للحصول على مفتاحك الرجاء التوجه إلى: http://namodg.com/keys
 */
$config['app']['key'] = 'fsdf#RFsdfer3245';


/****************************************************************
الخيارات الإضافية - خيارات تخصيص السكربت
/***************************************************************/

/* 
 * عنوان الصفحات
 *  
 * هنا يمكنك تحديد العنوان الذي سيظهر في شريط المتصفح العلوي. هذا هو العنوان
 * الذي يستخدم داخل تاج (<title>)
 */
$config['app']['page_title'] = 'تواصل معنا';

/* 
 * عنوان نموذجك
 *  
 * الصفحة الرئيسية لنموذج تحتوي على نموذج يحتوي على حقولك الموجودة بملف (includes/form.php).
 * هنا يمكنك تحديد النص الذي سيظهر على الشريط العلوي لذلك النموذج.
 */
$config['app']['form_title'] = 'تواصل معنا';

/* 
 * عنوان الرسالة الإلكترونية
 *  
 * هنا يمكنك تحديد عنوان الرسالة الإلكترونية التي ستصلك من نموذج.
 * إختيار عنوان مناسب سيساعدك على فرز الرسائل بشكل أفضل في بريدك الإلكتروني.
 */
$config['app']['message_title'] = 'رسالة جديدة من "نموذج"';


/****************************************************************
الخيارات المتقدمة - الرجاء عدم تعديلها إلا إذا كنت تعلم جيداً ما تعمله 
/***************************************************************/

/* 
 * أسم صفحة معالجة الطلبات
 *  
 * إن كنت تريد إستخدام نموذج كجزء من تطبيق أخر و كان هذا التطبيق يحتوي على ملف send.php،
 * فمن هنا يمكنك تغيير أسم صفحة send.php إلي أسم أخر. تأكد من تغيير أسم ملف send.php إلى
 * الأسم الجديد بعد تغيير هذا الخيار.
 */
$config['form']['url'] = 'send.php';

/* 
 * لغة سكربت نموذج
 *  
 * هنا يمكنك لغة السكربت. هذه القيمة يجب أن تكون عبارة عن حرفين يرمزان لإختصار اللفة (مثلاً ar للعربية).
 * تأكد من وجود ملف اللغة التي تريد تحديدها في مجلد languages.
 *
 * ملاحظة: إن قمت بتغيير اللغة العربية، تأكد من تغيير جميع العناوين (عنوان الصفحة، عنوان نموذجك..) في 
 * هذا الملف و تغيير خيارات حقولك في ملف includes/form.php
 */
$config['app']['language'] = 'ar';

/*
 * حقل مرسل البريد الإلكتروني
 * 
 * أسم حقل البريد الإلكتروني الذي سيستعمل عند الرد على الرسائل - يستخدم عندما تقوم بطلب أكثر من أيميل من المستخدم
 * و تريد إن تحدد الإيميل الذي ستقوم بإرسال ردك إليه.
*/
$config['app']['reply_to_field_name'] = 'الايميل';
