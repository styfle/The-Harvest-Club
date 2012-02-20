<?php 
// this class requires a config file with defined constants
$config = 'include/config.inc.php';
if (file_exists($config)) {
	require_once('include/config.inc.php');
} else {
	header("HTTP/1.1 500 Internal Server Error");
	die('File missing: ' . $config);
} // degrade gracefully when config is missing

class Mail
{
	private $headers;
	
	function __construct() {
		$this->headers = ''; // empty headers
	}

	/**
	 * Send email to single recipient
	 */
	function send($subject, $message, $to=MAIL_TO, $from=MAIL_FROM, $replyto=MAIL_FROM) {
		//$this->headers .= 'To: ' . $to . "\r\n";
		$this->headers .= 'From: ' . $from . "\r\n";
		$this->headers .= 'Reply-To: ' . $replyto . "\r\n";
		return mail($to, $subject, $message, $this->headers);
	}
	
	/**
	 * Send bulk email to multiple recipients
	 * $bcc is comma delimited list of email addresses
	 */
	function sendBulk($subject, $message, $bcc) {
		//$bcc = $implode(',', $addresses);
		//$this->headers .= 'To: ' . MAIL_TO . "\r\n";
		$this->headers .= 'From: ' . MAIL_FROM . "\r\n";
		$this->headers .= 'Reply-To: ' . MAIL_REPLYTO . "\r\n";
		$this->headers .= 'Bcc: ' . $bcc . "\r\n";
		//$this->headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		//$this->headers .= 'X-Mailer: Microsoft Office Outlook' . "\r\n";
		return mail(MAIL_TO, $subject, $message, $this->headers);
	}
}

$mail = new Mail(); // global object

?>
