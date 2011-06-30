<?php 

require_once 'includes/bootstrap.php';

/**
 * Add a text field
 */
$app->form()->addTextField('الاسم', array(
    'required' => true,
    'id' => 'name',
    'label' => 'اسم المرسل :',
    'title' => 'الاسم مطلوب'
));

/**
 * Add an email field
 */
$app->form()->addEmail('الايميل', array(
    'required' => true,
    'id' => 'email',
    'label' => 'البريد الإلكتروني :',
    'title' => 'الرجاء إستخدام أيميل خاص بك، الحقل مطلوب'
));

/**
 * Add a number field
 */
$app->form()->addNumberField('الرقم', array(
    'required' => true,
    'id' => 'number',
    'label' => 'رقم الهاتف :',
    'title' => 'رقمك الشخصي المتوفر لديك دائماً، الرقم مطلوب'
));

/**
 * Add a select dropdown
 */
$app->form()->addSelect('الغرض', array(
    'required' => true,
    'id' => 'purpose',
    'options' => array('اتصال عام', 'إبلاغ عن مشكلة', 'طلب وضع إعلان'),
    'default' => 'الرجاء الإختيار',
    'label' => 'الغرض من الاتصال :',
    'title' => 'غرضك من التواصل معنا، الغرض مطلوب'
));

/**
 * Add a captcha field
 */
$app->form()->addCaptcha('التحقق', array(
    'id' => 'captcha',
    'class' => 'captcha',
    'label' => 'التحقق البشري :',
    'title' => 'الرجاء إدخال الناتج الصحيح، حقل مطلوب',
    'info' => 'يستخدم هذا السؤال للتأكد من أن المرسل إنسان بشري و ليس روبوت يقوم بإرسال نشرات دعائية'
 ));

/**
 * Add a textarea
 */
$app->form()->addTextarea('الرسالة', array(
    'required' => true,
    'id' => 'message',
    'label' => 'الرسالة :',
    'title' => 'يمكنك إضافة رسالتك هنا، الرسالة مطلوبة'
));

/**
 * Add a submit button
 */
$app->form()->addSubmit('ارسال', 'ارسل الرسالة');

$app->showHome();