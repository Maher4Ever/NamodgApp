<?php

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