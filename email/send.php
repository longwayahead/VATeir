<?php
$to = "noreply@vateir.org";
switch($from) {
	case($from == 'training'):
		$from = 'VATeir Training <training@vateir.org>';
	break;
	case($from == 'noreply'):
		$from = 'No Reply <noreply@vateir.org>';
	break;
}
$headers = "From: $from\r\n";
$headers .= "Reply-To: noreply@vateir.com\r\n";
$headers .= "Bcc: $email" . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";

$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";


if(mail($to, $subject, $message, $headers));
