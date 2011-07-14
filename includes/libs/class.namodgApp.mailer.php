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

/**
 * Namodg Mailer
 * 
 * This is a wrapper around php's mail() to allow sening email in a OOP way.
 * 
 * @package NamodgApp
 */
class NamodgAppMailer {
    
    /**
     * Mailer current version
     */
    const version = '1.2';
    
    /**
     * Reciever email
     * 
     * @var string
     */
    private $_to = '';

    /**
     * Sender email
     * 
     * @var string
     */
    private $_from = '';

    /**
     * Reply-To email
     * 
     * @var string
     */
    private $_replyTo = NULL;

    /**
     * Email subject
     * 
     * @var string
     */
    private $_subject = '';

    /**
     * The message (html + plain)
     * 
     * @var array
     */
    private $_body = array();

    /**
     * Message headers used in mail()
     * 
     * @var array
     */
    private $_headers = array();

    /**
     * Email charset
     * 
     * @var string
     */
    private $_charset = 'UTF-8';

    /**
     * Email encoding
     * 
     * @var string
     */
    private $_encoding = '8bit';

    /**
     * Email id (used in the boundary too)
     * 
     * @var string
     */
    private $_id = '';

    /**
     * Used to connect the html and text parts of the message
     * 
     * @var string
     */
    private $_boundary = '';

    /**
     * Fill some important vars at the run-time
     */
    public function __construct() {
        $this->_id = sh1(uniqid(microtime()));
        $this->_boundary = "b_" . $this->_id;
    }

    /**
     * Sets the sender of the mail
     *
     * @param string $name
     * @param string $email Must be a valid email (not checked in the mailer)
     * @return $this Allows chaining
     */
    public function from($name, $email) {
        $this->_from = $name . ' <' . $email . '>';
        return $this;
    }

    /**
     * Sets the mail recipient
     *
     * @param mixen $email Email address, accepts both a single address or an array of addresses
     * @return $this Allows chaining
     */
    public function to($email) {
        ( is_array($email) ) ? $this->_to = implode(', ', $email) : $this->_to = $email;
        return $this;
    }

    /**
     * Sets the reply-to email
     * 
     * @param string $email Email address
     * @return $this Allows chaining
     */
    public function replyTo($email) {
        $this->_replyTo = $email;
        return $this;
    }

    /**
     * Sets the email subject
     *
     * @param string $subject Email subject
     * @return $this Allows chaining
     */
    public function subject($subject) {
        $this->_subject = $subject;
        return $this;
    }

    /**
     * Sets the plain part of the message
     *
     * @param srting $content
     * @return $this Allows chaining
     */
    public function altBody($content) {
        $this->_addBodyPart('plain', $content);
        return $this;
    }

    /**
     * Sets the html part of the message
     *
     * @param srting $body
     * @return $this Allows chaining
     */
    public function body($body) {
        $this->_addBodyPart('html', $content);
        return $this;
    }

    /**
     * Send the email using the php mail()
     *
     * @return boolean Sending's status
     */
    public function send() {

        $this->_makeHeaders();

        $status = @mail($this->_to, $this->_subject, $this->_body['plain'] . PHP_EOL . $this->_body['html'], $this->_headers['all']);
        
        if ( $status === false ) {
            error_log('Namodg Mailer: mail() function is activated but unable to send emails. Please consult your server admins about this problem', 0);
        }
        
        return $status;
    }

    /**
     * Returns the proper RFC 822 formatted date.
     *
     * @package PHPMailer
     * @return string
     * @static
     */
    public static function RFCDate() {
        $tz = date('Z');
        $tzs = ($tz < 0) ? '-' : '+';
        $tz = abs($tz);
        $tz = (int) ($tz / 3600) * 100 + ($tz % 3600) / 60;
        $result = sprintf("%s %s%04d", date('D, j M Y H:i:s'), $tzs, $tz);

        return $result;
    }
    
    /**
     * Adds a body part to the body array
     * 
     * @param string $type (plain or html)
     * @param string $content
     * @return $this Allows chaining
     */
    private function _addBodyPart($type, $content) {
        $this->_body[$type] = '--' . $this->_boundary . PHP_EOL;
        $this->_body[$type] .= 'Content-Type: text/' . $type . '; charset=' . $this->_charset . PHP_EOL;
        $this->_body[$type] .= 'Content-Transfer-Encoding: ' . $this->_encoding . PHP_EOL . PHP_EOL;

        $this->_body[$type] .= $content . PHP_EOL;
        return $this;
    }
    
    /**
     * Generates the headers
     */
    private function _makeHeaders() {

        $this->_headers['From'] = $this->_from;
        if ( $this->_replyTo ) 
            $this->_headers['Reply-To'] = $this->_replyTo;
        $this->_headers["Mime-Version"] = "1.0";
        $this->_headers["Content-Type"] = "multipart/alternative; boundary=\"$this->_boundary\"";
        $this->_headers["X-Mailer"] = "Namodg-Mailer " . self::$version;
        $this->_headers["Date"] = self::RFCDate();
        $this->_headers["Message-ID"] = '<' . $this->_id . '@' . $_SERVER['SERVER_NAME'] . '>';

        $this->_headers['all'] = '';

        foreach ($this->_headers as $hdr => $val) {
            $this->_headers['all'] .= $hdr . ': ' . $val . PHP_EOL;
        }
    }

}