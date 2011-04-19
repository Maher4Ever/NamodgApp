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
 * Include dependencies
 */
require_once 'includes/config.php';
require_once 'includes/classes/namodg.class.php';
require_once 'includes/classes/rain.tpl.class.php';

/*
 * Initialize a new Namodg object
 */
$form = new Namodg($config);

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
 * Add a select field
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

/**
 * Add a submit button
 */
$form->addSubmit('ارسال', 'ارسل الرسالة');


/**
 * Check the cache directory before initializing a template object
 */
if ( ! is_writable(dirname(__FILE__) . '/cache/') ) {
    $form->addFatalError('cache_not_writable');
}

/**
 * Initialize a new RainTPL object, for templating
 *
 * @link http://www.raintpl.com/
 */
$tpl = new RainTPL();

/**
 * Configure RainTPL
 *
 * @see http://www.raintpl.com/Documentation/Documentation-for-PHP-developers/Methods/Configure/
 */
RainTPL::configure('tpl_dir', 'templates/air/');
RainTPL::configure('cache_dir', 'cache/');

/**
 * Assign some variables
 *
 * @see http://www.raintpl.com/Documentation/Documentation-for-PHP-developers/Methods/Assign/
 */
$tpl->assign('title', $config['home_title']);
$tpl->assign('form_title', $config['form_title']);
$tpl->assign('version', Namodg::version);

/**
 * Check for fatal errors. If there are any, show them then exit.
 */
$errors = $form->getFatalErrors();
if ( ! empty( $errors ) ) {
    $tpl->assign('error_title', $form->getPhrase('misc', 'fatal_errors_title'));
    $tpl->assign('errors', $errors);
    $tpl->assign('button', array( 'url' => $_SERVER['SCRIPT_NAME'], 'text' => $form->getPhrase('misc', 'reload_page')) );

    $tpl->draw('run_errors');
    exit;
}

/**
 * Everything (hopefully) is alright, so display the form
 *
 * @see http://www.raintpl.com/Documentation/Documentation-for-PHP-developers/Methods/Assign/
 * @see http://www.raintpl.com/Documentation/Documentation-for-PHP-developers/Methods/Draw/
 */
$tpl->assign('form_open', $form->getOpeningHTML());
$tpl->assign('selected', $form->getPhrase('misc', 'selected'));
$tpl->assign('fields', $form->getFieldsAsArray());
$tpl->assign('form_close', $form->getClosingHTML());

$tpl->draw('home');