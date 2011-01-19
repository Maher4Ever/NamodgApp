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
 * The view class of namodg
 *
 * @package Namodg
 * @since Namodg 1.3
 */
class namodg_factory extends namodg {

    /**
     * @var array The 2 numbers used as captcha question
     */
    private $_captcha = array();
	
    /**
     * @var string The answer to the captcha question mixed with the salt and then hashed using md5
     */
    private $_token = '';

	/**
	 * @var string The title of the script
	 */
	public $title = '';
	
    /**
     * Fill some important vars at the runtime
     */
    public function __construct() {
        parent::__construct();
        $this->_captcha['1'] = mt_rand(1, 9);
        $this->_captcha['2'] = mt_rand(1, 9);
        $this->_token = hash('md5', $this->_config['salt'] . ($this->_captcha['1'] + $this->_captcha['2']));
		$this->title = $this->_config['homeTitle'];
    }

    /**
     * Make the form used in namodg
     *
     * @return string
     */
    public function generateForm() {
        $captcha = $this->_captcha['1'] . " + " . $this->_captcha['2'];
        $token = $this->_token;
        include 'template/form-' . $this->_lang . '.php';
    }

    /**
     * Make the validation's errors view
     *
     * @param array $errors
     */
    public function validationErrors($errors) {
    ?>

        <?php include 'template/header.php'; ?>

        <div id="validation-errors">
            <h1><?php echo $this->_phrase['validation']['header'] ?></h1>
            <ul>
             <?php foreach ($errors as $error) : ?>
                    <li><?php echo $error ?></li>
            <?php endforeach; ?>
            </ul>
            <a href="<?php echo $_SERVER['HTTP_REFERER'] ?>" class="button"><?php echo $this->_phrase['validation']['button'] ?></a>
        </div>

        <?php include 'template/footer.php'; ?>

    <?php
    }

    /**
     * Helper function to display the respond view
     *
     * @param string $type Message type (success or error)
     * @param string $name The name of the sender
     * @param boolean $ajax The status of the ajax
     * @return function The right view to display
     */
    public function getMsg($type, $name, $ajax = true) {

        if (!$type || ($type != 'success' && $type != 'error')) {
            die($this->_phrase['scriptErrors']['responseTypeNotSet']);
        }

        if ($ajax) {
            return $this->_generateAjaxMsg($name, $type);
        } else {
            return $this->_generateNoAjaxMsg($name, $type);
        }
    }

    /**
     * Make the success view for ajax requests
     *
     * @param string $name The name of the sender
     * @param string $type Message type (success or error)
     * @return json/text The message encoded into json
     */
    private function _generateAjaxMsg($name, $type) {

        $response['pageTitle'] = $this->_phrase[$type]['pageTitle'];

        // stop php from echoing the code to be able to save it in a var
        ob_start();
        ?>

        <div id="response" class="<?php echo $type ?>">
            <img src="images/response/<?php echo $type ?>-icon.png" alt="" class="<?php echo $type ?>-icon">
            <h2><?php echo ($name) ? str_replace('{senderName}', ", $name", $this->_phrase[$type]['headline']) : str_replace('{senderName}', '...', $this->_phrase[$type]['headline']); ?></h2>
            <p><?php echo $this->_phrase[$type]['body'], ' ', $this->_phrase[$type]['previous']; ?></p>
            <a href="javascript:history.back()" class="button"><?php echo $this->_phrase[$type]['button'] ?></a>
        </div>

        <?php
        $response['html'] = ob_get_contents();

        ob_end_clean();

        return json_encode($response);
    }

    /**
     * Make the success view for normal requests (no ajax)
     *
     * @param string $name The name of the sender
     * @param string $type Message type (success or error)
     */
    private function _generateNoAjaxMsg($name, $type) {
    ?>

    <?php include 'template/header.php'; ?>

        <div id="header">
            <h1><img src="images/header/message-icon.png" alt="message">تواصل معنا</h1>
            <div id="header-right"></div>
            <div id="header-left"></div>
        </div>
        <div id="content">
            <div id="response" class="<?php echo $type ?>">
                <img src="images/response/<?php echo $type ?>-icon.png" alt="" class="<?php echo $type ?>-icon">
                <h2><?php echo ($name) ? str_replace('{senderName}', ", $name", $this->_phrase[$type]['headline']) : str_replace('{senderName}', '...', $this->_phrase[$type]['headline']); ?></h2>
                <p><?php echo $this->_phrase[$type]['body'], '.'; ?></p>
                <a href="<?php echo $_SERVER['HTTP_REFERER'] ?>" class="button"><?php echo $this->_phrase[$type]['button'] ?></a>
            </div>
        </div>

    <?php include 'template/footer.php'; ?>

    <?php
    }

}