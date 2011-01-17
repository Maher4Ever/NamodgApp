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
 
// يمكنك من خلال هذه الصفحة تغيير الجمل المستخدمة في السكربت
// ----------------------------------------
// ملاحظة: الكلمات التي بداخل الأقواس {} سيتم إستبدالها بالكلمات المناسبة لذا الرجاء عدم تغيير هذه الكلمات أو حذفها
// مثال: {senderName} سيتم إستبداله بإسم المرسل

// Validation Errors
$phrase['validation']['header'] = 'عذراً، الرجاء العودة للصفحة السابقة لتصحيح البيانات التالية:';
$phrase['validation']['emptyData'] = 'الرجاء تعبئة جميع الخانات المطلوبة.';
$phrase['validation']['emailNotValid'] = 'الرجاء إستخدام بريد إلكتروني صحيح لنتستطيع التواصل معك عند الحاجة.';
$phrase['validation']['wrongAnswer'] = 'الرجاء إدخال الجواب الصحيح لسؤال التحقق.';
$phrase['validation']['button'] = 'العودة للخلف';

// Sucess
$phrase['success']['pageTitle'] = 'تم إرسال رسالتك بنجاح !';
$phrase['success']['headline'] = 'شكرا لتواصلك معنا {senderName}';
$phrase['success']['body'] = 'سنحاول الرد عليك في أقرب فرصة ممكنة. حاليا يمكنك العودة <a href="/">للرئيسية</a>';
$phrase['success']['previous'] = 'أو <a href="javascript:history.go(-2)">للصفحة</a> التي كنت تتصفحها سابقاً.';
$phrase['success']['button'] = 'رسالة جديدة';

// Errors
$phrase['error']['pageTitle'] = 'خطأ عند الإرسال !';
$phrase['error']['headline'] = 'أوبس! عذرا منك {senderName}';
$phrase['error']['body'] = 'حدثت مشكلة عند إرسال رسالتك، الرجاء المحاولة مجددا. يمكنك أيضا العودة <a href="/">للرئيسية</a> إن أردت المحاولة لاحقاً';
$phrase['error']['previous'] = 'أو <a href="javascript:history.go(-2)">للصفحة</a> التي كنت تتصفحها سابقاً.';
$phrase['error']['button'] = 'رسالة جديدة';

// Message
$phrase['defaultMessageTtitle'] = 'رسالة جديدة من {senderName} | "نموذج"';

// Script Errors
$phrase['scriptErrors']['dataNotArray'] = 'البيانات المطلوب إرسالها ليست على هيئة مصفوفة (array(';
$phrase['scriptErrors']['noRecieverEmail'] = 'الرجاء إضافة ايميل المستلم في ملف config.php ليمكننا إرسال الرسالة له';
$phrase['scriptErrors']['responseTypeNotSet'] = 'الرجاء إضافة نوع الرسالة المراد إنتاجها (خطأ أو نجاح)';