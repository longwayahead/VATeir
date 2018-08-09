<?php
$filename1  = date("Y_m", strtotime("- 1 month")) . "_controller_stats.csv";
$filename2  = date("Y_m", strtotime("- 1 month")) ."_movements_stats.csv";
$path1      = "/var/www/html/VATeir/uploads/ops/facility/";
$path2      = "/var/www/html/VATeir/uploads/ops/movements/";
$file1      = $path1 . $filename1;
$file_size1 = filesize($file1);
$handle1    = fopen($file1, "r");
$content1   = fread($handle1, $file_size1);
fclose($handle1);
$file2     = $path2 . $filename2;
$file_size2 = filesize($file2);
$handle2    = fopen($file2, "r");
$content2   = fread($handle2, $file_size2);
fclose($handle2);

$content1 = chunk_split(base64_encode($content1));
$content2 = chunk_split(base64_encode($content2));
$uid     = md5(uniqid(time()));
$name1    = basename($file1);
$name2    = basename($file2);

$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;">
<head>
<!-- If you delete this meta tag, Half Life 3 will never be released. -->
<meta name="viewport" content="width=device-width">

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Operations report for ' . date("F Y", strtotime("- 1 month")) . '</title>

<style>
@media only screen and (max-width: 600px) {
  a[class="btn"] {
    display: block!important;
    margin-bottom: 10px!important;
    background-image: none!important;
    margin-right: 0!important;
  }

  div[class="column"] {
    width: auto!important;
    float: none!important;
  }

  table.social div[class="column"] {
    width: auto!important;
  }
}
</style>
</head>

<body bgcolor="#FFFFFF" style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; height: 100%; width: 100%;">

<!-- HEADER -->
<table class="head-wrap" bgcolor="#999999" style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \Helvetica\', Helvetica, Arial, sans-serif; width: 100%;" width="100%">
	<tr style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;">
		<td style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;"></td>
		<td class="header container" style="padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; display: block; max-width: 600px; margin: 0 auto; clear: both;">

				<div class="content" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; padding: 15px; max-width: 600px; margin: 0 auto; display: block;">
				<table bgcolor="#999999" style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%;" width="100%">
					<tr style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;">
						<td style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;"><img style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; max-width: 200px;" src="https://i.img.ie/suw.png"></td>
					</tr>
				</table>
				</div>

		</td>
		<td style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;"></td>
	</tr>
</table><!-- /HEADER -->


<!-- BODY -->
<table class="body-wrap" style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%;" width="100%">
	<tr style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;">
		<td style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;"></td>
		<td class="container" bgcolor="#FFFFFF" style="padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; display: block; max-width: 600px; margin: 0 auto; clear: both;">

			<div class="content" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; padding: 15px; max-width: 600px; margin: 0 auto; display: block;">
			<table style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%;" width="100%">
				<tr style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;">
					<td style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;">
						<h3 style="margin: 0; padding: 0; font-family: \'HelveticaNeue-Light\', \'Helvetica Neue Light\', \'Helvetica Neue\', Helvetica, Arial, \'Lucida Grande\', sans-serif; line-height: 1.1; margin-bottom: 15px; color: #000; font-weight: 500; font-size: 27px;">Hello,</h3>
						<p class="lead" style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin-bottom: 10px; font-weight: normal; line-height: 1.6; font-size: 17px;">This is an automated email generated by the website.</p>
						<p style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin-bottom: 10px; font-weight: normal; font-size: 14px; line-height: 1.6;">Please find attached herewith controllers\' hours and flight movements within the EISN FIR for ' . date('F Y', strtotime("- 1 month")) . '</p>
						<p style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin-bottom: 10px; font-weight: normal; font-size: 14px; line-height: 1.6;">If there are any problems with the enclosed report please email the technical director.</p>
						<br style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;"><br style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;">
						The VATeir Website
					</td>
				</tr>
			</table>
			</div><!-- /content -->

		</td>
		<td style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;"></td>
	</tr>
</table><!-- /BODY -->

<!-- FOOTER -->
<table class="footer-wrap" style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; clear: both;" width="100%">
	<tr style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;">
		<td style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;"></td>
		<td class="container" style="padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; display: block; max-width: 600px; margin: 0 auto; clear: both;">

				<!-- content -->
				<div class="content" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; padding: 15px; max-width: 600px; margin: 0 auto; display: block;">
				<table style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%;" width="100%">
				<tr style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;">
					<td align="center" style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;">
						<p style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin-bottom: 10px; font-weight: normal; font-size: 14px; line-height: 1.6;">
							<a href="http://vateir.org/privacy.php" style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; color: #2BA6CB;">Privacy</a>
						</p>
					</td>
				</tr>
			</table>
				</div><!-- /content -->

		</td>
		<td style="margin: 0; padding: 0; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;"></td>
	</tr>
</table><!-- /FOOTER -->

</body>
</html>';

$eol     = PHP_EOL;
$subject = "Operations data for " . date('F Y', strtotime("-1 month"));

$from_name = "VATeir Website";
$from_mail = "noreply@vateir.org";
$replyto   = "noreply@vateir.org";
$mailto    = "tech@vateir.org";
$header    = "From: " . $from_name . " <" . $from_mail . ">\n";
$header .= "Reply-To: " . $replyto . "\n";
$header .= "MIME-Version: 1.0\n";
$header .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"\n\n";
$emessage = "--" . $uid . "\n";
$emessage .= "Content-type:text/html; charset=iso-8859-1\n";
$emessage .= "Content-Transfer-Encoding: 7bit\n\n";
$emessage .= $message . "\n\n";
$emessage .= "--" . $uid . "\n";
$emessage .= "Content-Type: application/octet-stream; name=\"" . $filename1 . "\"\n"; // use different content types here
$emessage .= "Content-Transfer-Encoding: base64\n";
$emessage .= "Content-Disposition: attachment; filename=\"" . $filename1 . "\"\n\n";
$emessage .= $content1 . "\n\n";
$emessage .= "--" . $uid . "\n";
$emessage .= "Content-Type: application/octet-stream; name=\"" . $filename2 . "\"\n"; // use different content types here
$emessage .= "Content-Transfer-Encoding: base64\n";
$emessage .= "Content-Disposition: attachment; filename=\"" . $filename2 . "\"\n\n";
$emessage .= $content2 . "\n\n";
$emessage .= "--" . $uid . "--";
mail($mailto, $subject, $emessage, $header);
