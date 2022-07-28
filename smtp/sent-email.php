<?php

//require( "libs/PHPMailer/PHPMailer.php");
//require( "libs/PHPMailer/SMTP.php");

use PHPMailer\PHPMailer;
use PHPMailer\Exception;
use PHPMailer\SMTP;

require 'libs/PHPMailer/Exception.php';
require 'libs/PHPMailer/PHPMailer.php';
require 'libs/PHPMailer/SMTP.php';

function get_html_fetch( $template, $data = array() )
{
	ob_start();
	include( $template );
	$email_template = ob_get_contents();
	ob_get_clean();

	return $email_template;
}

function send_email($from_email, $from_name, $subject, $messsage, $option = array() )
{
	try {

		$mail = new PHPMailer();
	    $mail->SMTPDebug 	= true;                      		// Enable verbose debug output
	    $mail->isSMTP();                                            // Send using SMTP
	    $mail->CharSet 		= 'UTF-8';
	    $mail->Host       	= 'smtp.mailtrap.io';                    	// Set the SMTP server to send through
	    $mail->SMTPAuth   	= true;                                 // Enable SMTP authentication
	    $mail->Username   	= 'c483ec65432ef5';         				// SMTP username
	    $mail->Password   	= '0bc0d2ae08a06c';                        // SMTP password
	    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;       // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
	    $mail->Port       	= 2525;                                  // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
	    $mail->SMTPSecure 	= 'tls';                            	// Enable TLS encryption, `ssl` also accepted

	    //Recipients
	    $mail->setFrom($from_email, $from_name);
	    $mail->addAddress($from_email, $from_name);  // receiver's email and name
	    $mail->addReplyTo('no-reply@mailinator.com');

	    // Content
	    $mail->isHTML(true);
	    $mail->Subject = $subject;
	    $mail->Body    = $messsage;

	    $mail->send();

	    return TRUE;

	} catch (Exception $e) {
	    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}
}

if( isset($_REQUEST['submit']))
{
	$data = [
		'name'    => $_REQUEST['name'],
		'email'   => $_REQUEST['email'],
		'subject' => $_REQUEST['subject'],
		'body'    => $_REQUEST['message'],
	];

	$from_email = '';
	$from_email = '';
	$email_template = get_html_fetch('libs/EmailTemplate/contact-template.php', $data);

	//Send Email
	send_email($data['email'], $data['name'], $data['subject'], $email_template);
}
