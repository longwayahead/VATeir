<?php
require_once('../includes/header.php');


$to = "undisclosed-recipients;";
$subject = 'VATeir Fly-in 2016 - August 27';


$c = $user->getEmailable();
foreach($c as $a) {
	$name = $a->first_name;
	$email = $a->email;
	unset($headers);
	$headers = "From: VATeir <noreply@vateir.org>\r\n";
	$headers .= "Reply-To: VATeir <noreply@vateir.org>\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$headers .= 'Bcc: ' . $email . "\r\n";
	$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>VATeir Fly-in 2016 - August 27</title>
  </head>
  <body bgcolor="#FFFFFF" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; margin: 0; padding: 0;">&#13;
&#13;
<!-- HEADER -->&#13;
<table class="head-wrap" bgcolor="#999999" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;"><tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"><td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"></td>&#13;
		<td class="header container" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 0;">&#13;
				&#13;
				<div class="content" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; max-width: 600px; display: block; margin: 0 auto; padding: 15px;">&#13;
				<table bgcolor="#999999" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;"><tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"><td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"><img style="max-width: 200px; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" src="https://i.img.ie/sjq.png" /></td>&#13;
					</tr></table></div>&#13;
				&#13;
		</td>&#13;
		<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"></td>&#13;
	</tr></table><!-- /HEADER --><!-- BODY --><table class="body-wrap" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;"><tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"><td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"></td>&#13;
		<td class="container" bgcolor="#FFFFFF" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 0;">&#13;
&#13;
			<div class="content" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; max-width: 600px; display: block; margin: 0 auto; padding: 15px;">&#13;
			<table style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;"><tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"><td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">&#13;
						<h3 style="font-family: \'HelveticaNeue-Light\', \'Helvetica Neue Light\', \'Helvetica Neue\', Helvetica, Arial, \'Lucida Grande\', sans-serif; line-height: 1.1; color: #000; font-weight: 500; font-size: 27px; margin: 0 0 15px; padding: 0;">Hey ' . $name . ',</h3>&#13;
						<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;"><b>Clarification: the VATeir Fly-in will take place on August 27 2016.</b></p>&#13;
						<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">After an absence of several years VATeir&mdash;the Irish vACC of the VATSIM network&mdash;is glad to announce its 2016 Fly-in. Taking place in the Cloud Suite of the Carlton Hotel at Dublin airport, you will be able to watch VATeir\'s finest controllers in action as they control virtual pilots live on the VATSIM network, while outside you will be able to take in an unobstructed view of Dublin airport\'s ramp from the outdoor balcony.</p>&#13;
						<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">For VATeir members there will be a €20 charge to cover the cost of the room and can be purchased below. Entry to members of the public is free. You are more than welcome to call in to watch VATeir controllers push some virtual tin!.</p>&#13;
						<!-- Callout Panel -->&#13;
						<p class="callout" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; background-color: #ECF8FF; margin: 0 0 15px; padding: 15px;">&#13;
							Our website can be found here: <a href="https://www.eventbrite.ie/e/vateir-fly-in-2016-tickets-25389646115" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; color: #2BA6CB; font-weight: bold; margin: 0; padding: 0;">Purchase Tickets &raquo;</a>&#13;
						</p><!-- /Callout Panel -->	&#13;
						<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">Best regards,<br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" />VATeir
					</td>&#13;
				</tr></table></div><!-- /content -->&#13;
									&#13;
		</td>&#13;
		<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"></td>&#13;
	</tr></table><!-- /BODY --><!-- FOOTER --><table class="footer-wrap" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; clear: both !important; margin: 0; padding: 0;"><tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"><td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"></td>&#13;
		<td class="container" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 0;">&#13;
			&#13;
				<!-- content -->&#13;
				<div class="content" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; max-width: 600px; display: block; margin: 0 auto; padding: 15px;">&#13;
				<table style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;"><tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"><td align="center" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">&#13;
						<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">&#13;
							<a href="http://vateir.org/privacy.php" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; color: #2BA6CB; margin: 0; padding: 0;">Privacy</a> | &#13;
							<a href="http://vateir.org/email/unsubscribe.php?e=' . $email . '" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; color: #2BA6CB; margin: 0; padding: 0;"><unsubscribe style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">Unsubscribe</unsubscribe></a>&#13;
&#13;
						</p>&#13;
					</td>&#13;
				</tr></table></div><!-- /content -->&#13;
				&#13;
		</td>&#13;
		<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"></td>&#13;
	</tr></table><!-- /FOOTER --></body>
</html>
';

	if(mail($to, $subject, $message, $headers)) {
		echo $a->first_name . ' ' . $a->last_name . ': ' . $a->email . ' ... Sent!<br>';
	} else {
		echo $a->first_name . ' ' . $a->last_name . ': ' . $a->email . ' ,,, Failed.<br>';
	}
}
