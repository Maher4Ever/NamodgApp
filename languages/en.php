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

/*
 * Language direction, is it ltr (left to right) ?
 */
$language['ltr'] = true;

// Validation
$phrase['validation']['required'] = 'الحقل مطلوب';
$phrase['validation']['email_not_valid'] = 'البريد الإلكتروني المدخل غير صحيح';
$phrase['validation']['not_number'] = 'القيمة المدخلة ليست رقم صحيح';
$phrase['validation']['captcha_answer_wrong'] = 'جواب سؤال التحقق غير صحيح';

// Email
$phrase['message']['message_title'] = 'رسالة جديدة من نموذج';
$phrase['message']['one_line_data_title'] = 'المعلومات الأساسية';
$phrase['message']['multiple_lines_data_title'] = 'المعلومات الإضافية';

// Mailer
$phrase['mailer']['sender_name'] = 'سكربت "نموذج"';
$phrase['mailer']['default_subject'] = 'رسالة جديدة من "نموذج"';

// Javscript Phrases
$phrase['js']['loading'] = 'الرجاء الإنتظار';
$phrase['js']['ajax_error'] = 'عذراً ، حدثت مشكلة عند محاولة إرسال رسالتك. الرجاء محاولة الإرسال مرة أخرى';

// Response
$phrase['response']['sending_succeeded'] = 'تم إرسال رسالتك بنجاح !';
$phrase['response']['success_message_headline'] = 'شكراً لتواصلك معنا زائرنا العزيز';
$phrase['response']['success_message_body'] = 'أخبرنا ساعي بريدنا (صورته على اليسار) بأنه أوصل رسالتك. سنحاول الرد عليك في أقرب فرصة ممكنة. حاليا يمكنك العودة <a href="/">للرئيسية</a> لإكتشاف أقسام الموقع الأخرى.';
$phrase['response']['sending_failed'] = 'خطأ عند الإرسال !';
$phrase['response']['fail_message_headline'] = 'أوبس! عذراً منك زائرنا العزيز';
$phrase['response']['fail_message_body'] = 'حالف ساعي بريدنا سوء الحظ عند محاولة إيصال رسالتك. الرجاء منك تركه ينال قسط من الراحة و من ثم حاول من جديد. يمكنك في هذه الأثناء التوجه <a href="/">لرئيسيتنا</a> و إكتشاف أقسام الموقع الأخرى.';

//Errors
$phrase['errors']['language_code_length_not_valid'] = 'خيار اللغة يجب أن يكون عبارة عن حرفين يمثلان إختصار اللغة، مثلاً ar للعربية.';
$phrase['errors']['language_file_not_found'] = 'ملف اللغة التي تم تحديدها غير موجود في مسار ملفات اللغة languages.';
$phrase['errors']['language_rtl_config_not_valid'] = NULL;
$phrase['errors']['no_key'] = 'الرقم السري غير صحيح.';
$phrase['errors']['weak_key'] = NULL;
$phrase['errors']['method_not_valid'] = 'طرق الإتصال المسموح بها هي POST و GET فقط.';
$phrase['errors']['receipt_email_not_valid'] = 'إيميل المستلم في ملف الإعدادات غير صحيح، الرجاء إعداده ليتم إيصال رسائلك إليه.';
$phrase['errors']['reply_to_field_name_not_valid'] = 'أسم حقل "إيميل الرد" في ملف الإعدادات غير صحيح، الرجاء التاكد من أن الأسم يطابق أسم الحقل المراد في ملف index.php';
$phrase['errors']['mail_function_disabled'] = 'دالة ()mail غير فعالة في هذا الخادم، الرجاء تفعيل هذه الدالة لنتمكن من إرسال رسائلك.';
$phrase['errors']['cache_not_writable'] = 'مجلد cache ليس قابل للكتابة. الرجاء تغيير تصريحات الملف و السماح بالكتابة فيه.';

$phrase['errors']['config_array_not_valid'] = NULL;

//Misc
$phrase['misc']['fatal_errors_title'] = 'عذراً، الرجاء تصحيح الأخطاء التالية ليعمل "نموذج" :';
$phrase['misc']['validation_errors_title'] = 'عذراً، الرجاء العودة للصفحة السابقة لتصحيح البيانات التالية:';
$phrase['misc']['selected'] = 'المحدد';
$phrase['misc']['reload_page'] = 'إعادة تحميل الصفحة';
$phrase['misc']['new_message'] = 'رسالة جديدة';