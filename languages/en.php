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
$phrase['validation']['required'] = 'Please enter a value';
$phrase['validation']['email_not_valid'] = 'Please enter a valid email';
$phrase['validation']['not_number'] = 'Please enter a valid number';
$phrase['validation']['captcha_answer_wrong'] = 'Please enter the correct answer';

// Email
$phrase['message']['one_line_data_title'] = 'Information';
$phrase['message']['multiple_lines_data_title'] = 'Extra Information';

// Mailer
$phrase['mailer']['sender_name'] = 'Namodg';
$phrase['mailer']['default_subject'] = 'New email from Namodg';

// Javscript Phrases
$phrase['js']['loading'] = 'Please wait';
$phrase['js']['ajax_error'] = 'Sorry, we could\'t send your data. Check your internet connection and try again.';

// Response
$phrase['response']['sending_succeeded'] = 'Your message has been sent';
$phrase['response']['success_message_headline'] = 'Thank you dear visitor';
$phrase['response']['success_message_body'] = 'Our mailman told us that your message has been delivered. We will try to reply as soon as possible. You can now check out our <a href="/">homepage</a> and explore the rest of our website.';
$phrase['response']['sending_failed'] = 'Your message couldn\'t be devliverd';
$phrase['response']['fail_message_headline'] = 'Oops, Something went wrong!';
$phrase['response']['fail_message_body'] = 'Our mailman has had bad luck while trying to deliver your message. Please let him rest for a while and then try again. In the meantime you could check out our <a href="/">homepage</a> and explore the rest of our website.';

//Errors
$phrase['errors']['php_version_not_supported'] = 'The used PHP version is not supported. Please upgrade to version 5.2.0 or higher.';
$phrase['errors']['config_array_not_valid'] = 'The enterd configurations are not valid. Please use the default configuration file to solve this problem.';
$phrase['errors']['language_code_length_not_valid'] = 'The language configuration should be 2 charecters long. For example: "en" for English.';
$phrase['errors']['language_file_not_found'] = 'The selected language file doesn\'t exist in the languages folder.';
$phrase['errors']['language_rtl_config_not_valid'] = 'The selected language file contains unvalid language-direction value';
$phrase['errors']['no_key'] = 'No entered key.';
$phrase['errors']['weak_key'] = 'The entered key is weak. The key should be 10 charecters long at least.';
$phrase['errors']['method_not_valid'] = 'The entered form method is not valid. The Form alowed methods are either POST or GET.';
$phrase['errors']['receipt_email_not_valid'] = 'The entered email is not valid. Please enter a valid email to recieve your messages.';
$phrase['errors']['reply_to_field_name_not_valid'] =  'The entered "reply_to_field_name" value doesn\'t match an added field. Please make sure that the value matches a field name in "includes/form.php"';
$phrase['errors']['mail_function_disabled'] = 'The "mail()" function is disabled in this server. This function is needed for sending the emails.';
$phrase['errors']['cache_not_writable'] = 'The "cache" folder is no writable. Please change this folder\'s permissions and to 777.';

//Misc
$phrase['misc']['fatal_errors_title'] = 'Sorry, please correct the following errors:';
$phrase['misc']['validation_errors_title'] = 'Sorry, please go back and correct the following errors:';
$phrase['misc']['selected'] = 'Selected';
$phrase['misc']['reload_page'] = 'Refresh page';
$phrase['misc']['new_message'] = 'New message';