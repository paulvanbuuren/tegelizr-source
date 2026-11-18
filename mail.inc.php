<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

$configFile = dirname( __DIR__ ) . '/mail.config.php';

if ( ! is_file( $configFile ) || ! is_readable( $configFile ) ) {
	die( 'Mail config not found or not readable: ' . $configFile );
}


function send_mail( string $to, string $subject, string $html, ?string $plain = null, ?string $replyTo = null ): bool {

	$configFile = dirname( __DIR__ ) . '/mail.config.php';
	$config     = require $configFile;


	$mail = new PHPMailer( true ); // true = throw exceptions

	try {
		// SMTP
		$mail->isSMTP();
		$mail->Host       = $config['host'];
		$mail->SMTPAuth   = true;
		$mail->Username   = $config['username'];
		$mail->Password   = $config['password'];
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // port 587
		$mail->Port       = $config['port'];                // use ENCRYPTION_SMTPS + 465 for implicit TLS

		// Encoding
		$mail->CharSet  = 'UTF-8';
		$mail->Encoding = 'base64';

		// Sender / recipients
		$mail->setFrom( $config['from'], $config['from_name'] );
		$mail->addAddress( $to );
		if ( $replyTo ) {
			$mail->addReplyTo( $replyTo );
		}

		// Content
		$mail->isHTML( true );
		$mail->Subject = $subject;
		$mail->Body    = $html;
		$mail->AltBody = $plain ?? strip_tags( $html );

		return $mail->send();
	} catch ( Exception $e ) {
		error_log( 'Mail error: ' . $mail->ErrorInfo );

		return false;
	}

}