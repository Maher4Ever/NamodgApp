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

$app->form()
        
    /**
     * Add a text field
     */
    ->addTextField('الاسم', array(
        'required' => true,
        'id' => 'name',
        'label' => 'اسم المرسل :',
        'title' => 'الاسم مطلوب'
    ))

    /**
     * Add an email field
     */
    ->addEmail('الايميل', array(
        'required' => true,
        'id' => 'email',
        'label' => 'البريد الإلكتروني :',
        'title' => 'الرجاء إستخدام أيميل خاص بك، الحقل مطلوب'
    ))

    /**
     * Add a number field
     */
    ->addNumberField('الرقم', array(
        'required' => true,
        'id' => 'number',
        'label' => 'رقم الهاتف :',
        'title' => 'رقمك الشخصي المتوفر لديك دائماً، الرقم مطلوب'
    ))

    /**
     * Add a select dropdown
     */
    ->addSelect('الغرض', array(
        'required' => true,
        'id' => 'purpose',
        'options' => array('اتصال عام', 'إبلاغ عن مشكلة', 'طلب وضع إعلان'),
        'default' => 'الرجاء الإختيار',
        'label' => 'الغرض من الاتصال :',
        'title' => 'غرضك من التواصل معنا، الغرض مطلوب'
    ))

    /**
     * Add a captcha field
     */
    ->addCaptcha('التحقق', array(
        'id' => 'captcha',
        'class' => 'captcha',
        'label' => 'التحقق البشري :',
        'title' => 'الرجاء إدخال الناتج الصحيح، حقل مطلوب',
        'info' => 'يستخدم هذا السؤال للتأكد من أن المرسل إنسان بشري و ليس روبوت يقوم بإرسال نشرات دعائية'
    ))

    /**
     * Add a textarea
     */
    ->addTextarea('الرسالة', array(
        'required' => true,
        'id' => 'message',
        'label' => 'الرسالة :',
        'title' => 'يمكنك إضافة رسالتك هنا، الرسالة مطلوبة'
    ))

    /**
     * Add a submit button
     */
    ->addSubmit('ارسال', 'ارسل الرسالة');