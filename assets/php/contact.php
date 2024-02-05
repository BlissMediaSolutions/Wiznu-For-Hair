<?php
require_once "recaptcha/autoload.php";
require_once "PHPMailer/class.phpmailer.php"

//ini_set('display_errors', 'On');
//error_reporting(E_ALL | E_STRICT);
session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));
header('Content-type: application/json');

$mail = new PHPMailer;
$mail->SMTPDebug = 2;
$mail->isSMTP();
$mail->Host = '  ' //SMTP server to send thru
$mail->SMTPAuth = true;  //Enable SMTP authentication
$mail->Username = ' ';  //SMTP username
$mail->Password = ' ';  //SMTP password for the username
$mail->SMTPSecure = 'tls';  //Enable TLS Encryption
$mail->Port = ' ';  //SMTP Port number with tls

//$Recipient = 'someone@example.com'; // <-- Set your email here
$mail->From = " ";  //the email address sending the email
$mail->FromName = " "; //the name sending the email
$Recipient = $mail->addAddress(' '); //the address the email is being sent to.
//$resp = "";

// Register API keys at https://www.google.com/recaptcha/admin
$siteKey = ""; //Add Site Key
$secret = ""; //Add Secret Key

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
	//$Email_headers = "";

	//$Email_headers .= 'From: ' . "Wiznu Website" . ' <' . "info@wiznu.com.au" . '>' . "\r\n".
						"Reply-To: " .  $Email . "\r\n";

	//$sent = mail($Recipient, "Website Inquiry", $Email_body, $Email_headers);


	//if ($sent){
	if ($mail->send()) {
		$emailResult = array ('sent'=>'yes');
	} else{
		$emailResult = array ('sent'=>'no');
	}

	echo json_encode($emailResult);

} else {

	$emailResult = array ('sent'=>'no');
	echo json_encode($emailResult);

}
?>