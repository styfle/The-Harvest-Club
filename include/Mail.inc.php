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
	private $to;
	private $from;
	private $replyto;
	
	function __construct($to, $from, $replyto) {
		$this->headers = ''; // empty headers
		$this->to = $to;
		$this->from = $from;
		$this->replyto = $replyto;
	}

	/**
	 * Send email to single recipient
	 */
	function send($subject, $message, $to, $from=NULL, $replyto=NULL) {
		if (is_NULL($from)) $from= $this->from;
		if (is_NULL($replyto)) $replyto= $this->from;

		//$this->headers .= 'To: ' . $to . "\r\n";
		$this->headers .= 'From: ' . $from . "\r\n";
		$this->headers .= 'Reply-To: ' . $replyto . "\r\n";
		return mail($to, $subject, $message, $this->headers);
	}
	
	/**
	 * Send bulk email to multiple recipients
	 * $bcc is comma delimited list of email addresses
	 */
	function sendBulk($subject, $message, $bcc, $replyto=NULL) {
		if (is_NULL($replyto)) {
			$replyto = $this->replyto;
			$to = $this->to; // use defaults provided in config
		} else {
			$to = $replyto; // send email to self, bcc everyone else
		}
		//$this->headers .= 'To: ' . $this->to . "\r\n"; // not needed
		$this->headers .= 'From: ' . $this->from . "\r\n";
		$this->headers .= 'Reply-To: ' . $replyto . "\r\n";
		$this->headers .= 'Bcc: ' . $bcc . "\r\n";
		//$this->headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		//$this->headers .= 'X-Mailer: Microsoft Office Outlook' . "\r\n";
		return mail($to, $subject, $message, $this->headers);
	}
}

$mail = new Mail(MAIL_TO, MAIL_FROM, MAIL_REPLYTO); // global object

?>
