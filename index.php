<?php 

require_once 'libs/namodg/class.namodg.php';

// $form = new Namodg('aaa6dd417d1f99aa760caccdd2e2ef49');

$form = new Namodg(array(
    'key' => 'aaa6dd417d1f99aa760caccdd2e2ef49',
    'url' => 'process.php'
));

/**
 * Add a text field
 */
$form->addTextField('الاسم', array(
    'required' => true,
    'id' => 'name',
    'label' => 'اسم المرسل :',
    'title' => 'الاسم مطلوب'
));

/**
 * Add an email field
 */
$form->addEmail('الايميل', array(
    'required' => true,
    'id' => 'email',
    'label' => 'البريد الإلكتروني :',
    'title' => 'الرجاء إستخدام أيميل خاص بك، الحقل مطلوب'
));

/**
 * Add a number field
 */
$form->addNumberField('الرقم', array(
    'required' => true,
    'id' => 'number',
    'label' => 'رقم الهاتف :',
    'title' => 'رقمك الشخصي المتوفر لديك دائماً، الرقم مطلوب'
));

/**
 * Add a select dropdown
 */
$form->addSelect('الغرض', array(
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
$form->addCaptcha('التحقق', array(
    'id' => 'captcha',
    'class' => 'captcha',
    'label' => 'التحقق البشري :',
    'title' => 'الرجاء إدخال الناتج الصحيح، حقل مطلوب',
    'info' => 'يستخدم هذا السؤال للتأكد من أن المرسل إنسان بشري و ليس روبوت يقوم بإرسال نشرات دعائية'
 ));

/**
 * Add a textarea
 */
$form->addTextarea('الرسالة', array(
    'required' => true,
    'id' => 'message',
    'label' => 'الرسالة :',
    'title' => 'يمكنك إضافة رسالتك هنا، الرسالة مطلوبة'
));

$form->addSubmit('ارسال', 'ارسل الرسالة');
?>
<!DOCTYPE HTML>
<html dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>Namodg Test</title>
</head>
<body>
    <?php echo $form; ?>
</body>
</html>