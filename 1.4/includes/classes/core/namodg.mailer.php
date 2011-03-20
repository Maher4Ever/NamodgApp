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

/**
 * Namodg Mailer
 *
 * @ver 1.1
 * @package Namodg
 * @since Namodg 1.3
 */
class NamodgMailer {

    /**
     * @var string The reciever email
     */
    private $_to = '';

    /**
     * @var string The sender email
     */
    private $_from = '';

    /**
     * @var string The Reply-To email
     */
    private $_replyTo = NULL;

    /**
     * @var string The email subject (default in $_phrase)
     */
    private $_subject = '';

    /**
     * @var array The message (html + plain)
     */
    private $_body = array();

    /**
     * @var array The message headers used in mail()
     */
    private $_headers = array();

    /**
     * @var string The email charset (default UTF-8)
     */
    private $_charset = 'UTF-8';

    /**
     * @var string The email encoding
     */
    private $_encoding = '8bit';

    /**
     * @var string The email id (used in the boundary too)
     */
    private $_id = '';

    /**
     * @var string Used to connect the html and text parts of the message
     */
    private $_boundary = '';

    /**
     * Fill some important vars at the runtime
     */
    public function __construct() {
        $this->_id = md5(uniqid(time()));
        $this->_boundary = "b_" . $this->_id;
    }

    /**
     * Set the sender of the mail
     *
     * @param string $name
     * @param string $email Must be a valid email (not checked in the mailer)
     */
    public function from($name, $email) {
        $this->_from = $name . ' <' . $email . '>';
    }

    /**
     * Set the mail recipient
     *
     * @param string $email Email address, accept both a single address or an array of addresses
     */
    public function to($email) {
        ( is_array($email) ) ? $this->_to = implode(', ', $email) : $this->_to = $email;
    }

    /**
     * Set the reply-to email
	 *
     * @param string $email Email address
     */
    public function replyTo($email) {
        $this->_replyTo = $email;
    }

    /**
     * Set the email subject
     *
     * @param string $subject The email subject
     */
    public function subject($subject) {
        $this->_subject = $subject;
    }

    /**
     * Make the plain part of the message
     *
     * @param srting $body
     */
    public function altBody($body) {

        $this->_body['plain'] = '--' . $this->_boundary . PHP_EOL;
        $this->_body['plain'] .= "Content-Type: text/plain; charset=" . $this->_charset . PHP_EOL;
        $this->_body['plain'] .= "Content-Transfer-Encoding: " . $this->_encoding . PHP_EOL . PHP_EOL;

        $this->_body['plain'] .= $body . PHP_EOL;
    }

    /**
     * Make the html part of the message
     *
     * @param srting $body
     */
    public function body($body) {

        $this->_body['html'] = '--' . $this->_boundary . PHP_EOL;
        $this->_body['html'] .= "Content-Type: text/html; charset=" . $this->_charset . PHP_EOL;
        $this->_body['html'] .= "Content-Transfer-Encoding: " . $this->_encoding . PHP_EOL . PHP_EOL;

        $this->_body['html'] .= $body . PHP_EOL;
    }

    /**
     * Make the headers of the the mail()
     */
    private function _makeHeaders() {

        $this->_headers['From'] = $this->_from;
        if ( $this->_replyTo ) $this->_headers['Reply-To'] = $this->_replyTo;
        $this->_headers["Mime-Version"] = "1.0";
        $this->_headers["Content-Type"] = "multipart/alternative; boundary=\"$this->_boundary\"";
        $this->_headers["X-Mailer"] = "Namodg-Mailer [v1.1]";
        $this->_headers["Date"] = self::RFCDate();
        $this->_headers["Message-ID"] = '<' . $this->_id . '@' . $_SERVER['SERVER_NAME'] . '>';

        $this->_headers['all'] = '';

        foreach ($this->_headers as $hdr => $val) {
            $this->_headers['all'] .= $hdr . ': ' . $val . PHP_EOL;
        }
    }

    /**
     * Send the email using the php mail()
     *
     * @return boolean The status of the sending
     */
    public function send() {

        $this->_makeHeaders();

        return @mail($this->_to, $this->_subject, $this->_body['plain'] . PHP_EOL . $this->_body['html'], $this->_headers['all']);
    }

    /**
     * Returns the proper RFC 822 formatted date.
     *
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

}