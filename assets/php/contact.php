<?php
require_once "recaptcha/autoload.php";
require "PHPMailer/PHPMailer.php";
require "PHPMailer/SMTP.php";
require "PHPMailer/Exception.php";

//ini_set('display_errors', 'On');
//error_reporting(E_ALL | E_STRICT);
//session_cache_limiter('nocache');
//header('Expires: ' . gmdate('r', 0));
//header('Content-type: application/json');

$mail = new PHPMailer\PHPMailer\PHPMailer;
$mail->SMTPDebug = 0; //Use 2 when debugging/testing, but for PROD use 0.
$mail->isSMTP();
$mail->Host = '  ' ; //SMTP server to send thru
$mail->SMTPAuth = true;  //Enable SMTP authentication
$mail->Username = ' ';  //SMTP username
$mail->Password = ' ';  //SMTP password for the username
$mail->SMTPSecure = 'tls';  //Enable TLS Encryption
$mail->Port = '587';  //SMTP Port number with tls

$Recipient = ' '; // Address email is being sent to
$RcpName = ' ';

$mail->setFrom(' ', ' ');  //the email address sending the email
$mail->addAddress($Recipient, $RcpName); //the address the email is being sent to.
$mail->addReplyTo(' ', ' ');  //The Reply to address for the sender

// Register API keys at https://www.google.com/recaptcha/admin
$siteKey = " "; //Add Site Key
$secret = " "; //Add Secret Key

// reCAPTCHA supported 40+ languages listed here: https://developers.google.com/recaptcha/docs/language
$lang = "en";

// Was there a reCAPTCHA response?
if (isset($_POST['g-recaptcha-response'])) {
	$recaptcha = new \ReCaptcha\ReCaptcha($secret);
	$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
}

if ($Recipient && $resp->isSuccess() ) {
//if (isSuccess($Recipient && $resp)) {

	$Name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
	$Email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
	$Phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
	//$Subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
	$Message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

	$Email_body = "";
	$Email_body .=  "Name: " . $Name . "\n".
				    "Email: " . $Email . "\n".
				    "Phone: " . $Phone . "\n".
					//"Subject: " . $Subject . "\n".
				    "Message: " . $Message . "\n";

	$mail->Subject = 'Website Enquiry';
	$mail->Body = $Email_body;

	if (!$mail->send()) {
		$emailResult = array ('sent'=>'no', 'error'=>$mail->ErrorInfo );
	} else {
		$emailResult = array ('sent'=>'yes');
	}

	echo json_encode($emailResult);

} else {

	$emailResult = array ('sent'=>'no');
	echo json_encode($emailResult);

}
?>