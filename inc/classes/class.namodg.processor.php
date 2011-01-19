<?php

/**
 * Namodg - Ajax contact form
 *
 * @author Maher Salam <admin@namodg.com>
 * @version 1.3.1
 * @copyright Copyright (c) 2010, Maher Salam
 *
 * Dual licensed under the MIT and GPL licenses:
 *   @license http://www.opensource.org/licenses/mit-license.php
 *   @license http://www.gnu.org/licenses/gpl.html
 */

/**
 * The model class of namodg
 *
 * @package Namodg
 * @since Namodg 1.3
 */
class namodg_processor extends namodg {

    /**
     * @var array The data to process (default $_POST)
     */
    private $_data = array();
    /**
     * @var string The salt used to remake the token
     */
    private $_salt = '';
    /**
     * @var string The reciever email (from $_config)
     */
    private $_recieverEmail = '';
    /**
     * @var string The sender's name (from $_data)
     */
    private $_senderName = '';
    /**
     * @var string The sender email (from $_data)
     */
    private $_senderEmail = '';
    /**
     * @var array The html and text message to be sent
     */
    private $_message = array();
    /**
     *
     * @var array The fatal errors
     */
    public $errors = array();
    /**
     * @var boolean True if there is any validation error
     */
    public $validationError = false;

    /**
     * Start defining some vars at the runtime
     *
     */
    public function __construct($data) {
        parent::__construct();

        // Add the reciever email but check it before adding it
        $this->_recieverEmail = filter_var($this->_config['recieverEmail'], FILTER_VALIDATE_EMAIL) ? filter_var($this->_config['recieverEmail'], FILTER_SANITIZE_EMAIL) : die($this->_phrase['scriptErrors']['noRecieverEmail']);

        // The salt is inherted from the parent class
        $this->_salt = isset($this->_config['salt']) ? $this->_config['salt'] : '';

        // Add the data based on the language configuration
        if ($this->_lang == 'ar') {
            $this->_data = isset($data) ? (is_array($data) ? $this->_fixArabicNames($data) : die($this->_phrase['scriptErrors']['dataNotArray'])) : $this->_fixArabicNames($_POST);
        } else {
            $this->_data = isset($data) ? (is_array($data) ? $data : die($this->_phrase['scriptErrors']['dataNotArray'])) : $_POST;
        }
    }

    /**
     * Replace the arabic names in the data array
     *
     * @param array $data the data to be proccessed
     * @return array the data array with fixed keys
     */
    private function _fixArabicNames($data) {
        $arr = array();
        foreach ($data as $key => $val) {
            switch ($key) {
                case 'الاسم':
                    $arr['name'] = $data[$key];
                    break;
                case 'الايميل':
                    $arr['email'] = $data[$key];
                    break;
                case 'التحقق':
                    $arr['captcha'] = $data[$key];
                    break;
                case 'مخفي':
                    $arr['token'] = $data[$key];
                    break;
                case 'الرسالة':
                    $arr['message'] = $data[$key];
                    break;
                default:
                    $arr[$key] = $data[$key];
            }
        }
        return $arr;
    }
	
	/**
     * Check if the message was sent through an ajax request
     *
     * @return boolean
     */
    public function isAjax() {
        return ($this->_config['ajaxEnabled'] && isset($_SERVER['HTTP_X_REQUESTED_WITH']));
    }
	
    /**
     * Validate the data that was entered by the user (used only when there is no ajax)
     *
     * @return boolean
     */
    public function isDataVaild() {
        if ($this->_isDataEmpty($this->_data)) {
            $this->errors[] = $this->_phrase['validation']['emptyData'];
        }

        if (isset($this->_data['email']) && !filter_var($this->_data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = $this->_phrase['validation']['emailNotValid'];
        }

        if (isset($this->_data['captcha']) && hash('md5', $this->_salt . $this->_data['captcha']) != $this->_data['token']) {
            $this->errors[] = $this->_phrase['validation']['wrongAnswer'];
        }

        if (count($this->errors) === 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check is the values are empty or not
     *
     * @return boolean
     */
    private function _isDataEmpty($data) {
        foreach ($data as $val) {
            if (trim($val) === '') {
                return true;
            }
        }
    }

    /**
     * Check if there is an exploit in the input and then remove it
     *
     * @param string $val The value to be checked
     * @return string The cleaned value
     */
    private function _removeExploit($val) {
        $exploitPattrens = array('content-type', 'to:', 'bcc:', 'cc:', 'document.cookie', 'document.write', 'onclick', 'onload', '\n', '\r', '\t', '%0A', '%0D', '%08', '%09');

        foreach ($exploitPattrens as $exploit) {
            if (strpos($val, $exploit) !== false) {
                $val = str_replace($exploit, ' ', $val);
            }
        }

        return $val;
    }

    /**
     * Clean and format the data (remove unnecessary values and sanitize values)
     *
     * @param array $data The data to be cleaned and formated
     * @return array The formated data
     */
    private function _cleanData($data) {
        if (isset($data['captcha']))
            unset($data['captcha']);
        if (isset($data['token']))
            unset($data['token']);

        $data['email'] = filter_var(strtolower(trim($this->_removeExploit($data['email']))), FILTER_SANITIZE_EMAIL);

        foreach ($data as $id => &$val) {
            if ($id != 'email')
                $val = trim(filter_var($this->_removeExploit($val), FILTER_SANITIZE_STRING));
        }

        return $data;
    }

    /**
     * Fill the $_message var using the data
     *
     * @param array $data The data used to generate the msg
     */
    private function _generateMsg($data) {

        $message_start = "<div " . (($this->_lang == 'ar') ? " dir='rtl' " : '') . "style='padding: 50px 0 100px;background: #eeeeee; font-family: Arial, Helvetica, sans-serif;'>
<h1 align='center' style='font-size: 24px; font-weight: bold;color: #989898;margin-bottom: 35px'>" . $this->_getMessageTitle() . "</h1>
<table width='600' align='center' border='1' style='border-collapse: collapse; border: 1px solid #dddddd;font-size: 16px;' cellpadding='14' cellspacing='2'>";

        $message_end = "</table><p style='margin:0;color:#CACACA;font-size:10px;padding-top:20px;text-align:center;'><a style='color:#CACACA;text-decoration:none;' href='http://namodg.com'>Namodg v" . $this->version . "</a> - Developed &amp; Designed by Maher Salam, &copy; <a style='color:#CACACA;text-decoration:none;' href='http://coolworlds.net'>coolworlds.net</a></p></div>";

        $message = '';
        $messagePlein = '';

        foreach ($data as $id => $entrie) {
            if ($this->_lang == 'ar') { // rewrite the keys for the Arabic lang
                switch ($id) {
                    case 'name':
                    $id = 'الاسم';
                        break;
                    
                    case 'email':
                    $id = 'الايميل';
                        break;
                    
                    case 'message':
                    $id = 'الرسالة';
                        break;
                }
            }
            $message .= "<tr valign='top' bgcolor='#ffffff'><td width='90' align='left' style='color: #989898;'><b>" . $id . '</b></td><td>' . nl2br($entrie) . '</td></tr>';
            $messagePlein .= $id . ': ' . $entrie . PHP_EOL;
        }

        $this->_message['html'] = $message_start . $message . $message_end;
        $this->_message['plein'] = $messagePlein;
    }

    /**
     * Send the data using a mailer
     *
     * @param object $mailer The class used and a mailer
     * @return boolean The status of the send
     */
    private function _mailUsing($mailer) {

		if ( $this->_config['emailFromSender'] ) { // Send the message from the sender's email
		
			$mailer->from($this->_senderName, $this->_senderEmail);
			
		} else { // Send the message from the reciever's email, but use the sender's email as the reply-to address
			
			$mailer->from($this->_senderName, $this->_recieverEmail);
			$mailer->replyTo($this->_senderEmail);
		}
		
        $mailer->to($this->_recieverEmail);
        $mailer->subject($this->_getMessageTitle());

        $mailer->body($this->_message['html']); // set the body
        $mailer->altBody($this->_message['plein']);

        return $mailer->send();
    }

    /**
     * Prepare the data and then send it using a mailer
     *
     * @return boolean The status of the send
     */
    public function send() {

        if (!$this->isAjax() && !$this->isDataVaild()) {
            $this->validationError = true;
            return false;
        }

        $cleanData = $this->_cleanData($this->_data);
        $this->setSenderName($cleanData['name']);
        $this->setSenderEmail($cleanData['email']);
        $this->_generateMsg($cleanData);

        require "class.namodg.mailer.php";

        return $this->_mailUsing(new namodg_mailer);
    }

    /**
     * Setter method for the sender Name
     *
     * @param string $name
     */
    public function setSenderName($name) {
        if (empty($this->_senderName)) {
            $this->_senderName = trim($name);
        }
    }

    /**
     * Getter method for the sender Name
     *
     * @return string
     */
    public function getSenderName() {
        return $this->_senderName;
    }

    /**
     * Setter method for the sender Email
     *
     * @param string $email
     */
    public function setSenderEmail($email) {
        if (empty($this->_senderEmail)) {
            $this->_senderEmail = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        }
    }

    /**
     * Getter method for the message title
     *
     * @return string
     */
    private function _getMessageTitle() {
        return str_replace('{senderName}', $this->_senderName, $this->_phrase['defaultMessageTtitle']);
    }

}