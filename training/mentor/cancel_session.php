<?php
require_once('../includes/header.php');
try {
	$session = $s->get([
		'id' => Input::get('id')
	])[0];
	if(!$user->hasPermission("mentor")) { //this bit needs looking at...
		Session::flash('error', 'Insufficient permissions.');
		Redirect::to('./');
	}
	//print_r($session);
	// if(!$user->hasPermission($session->program_permissions) || !$user->hasPermission("admin")) { //this bit needs looking at...
	// 	Session::flash('error', 'Cannot mentor at that level');
	// 	Redirect::to('./');
	// }
	if(!empty($session)) {
		$r->addCard(array(
			'cid'		=> $session->student,
			'card_type'	=> 2,
			'link_id'	=> Input::get('id'),
			'submitted'	=> date('Y-m-d H:i:s')
		));
		$r->addInfo(array(
			'session_id'		=> Input::get('id'),
			'card_id'	=> 8
		));
			$edit = $s->edit(['deleted' => 1], [['id', '=', Input::get('id')]]);

			if(!isset($_GET['e'])) { //if email supression hasn't been turned on...
				$name = $session->sfname;
				$email = $session->email . ',' . $session->memail;
				$mentorname = $session->mfname . ' ' . $session->mlname;
				$from = 'training';
				$subject = 'Session Cancelled: ' . $session->callsign . ' on ' . date("j F Y", strtotime($session->start));
				$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
	<head>

	<meta name="viewport" content="width=device-width" />

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Session Booked</title>


	</head>

	<body bgcolor="#FFFFFF" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; margin: 0; padding: 0;">


	<table class="head-wrap" bgcolor="#999999" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;">
	<tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
	<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"></td>
	<td class="header container" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 0;">

		<div class="content" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; max-width: 600px; display: block; margin: 0 auto; padding: 15px;">
		<table bgcolor="#999999" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;">
			<tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
				<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"><img style="max-width: 200px; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" src="https://i.img.ie/suw.png" /></td>
			</tr>
		</table>
		</div>

	</td>
	<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"></td>
	</tr>
	</table>



	<table class="body-wrap" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;">
		<tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
			<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"></td>
			<td class="container" bgcolor="#FFFFFF" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 0;">

				<div class="content" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; max-width: 600px; display: block; margin: 0 auto; padding: 15px;">
					<table style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;">
						<tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
							<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
								<h3 style="font-family: \'HelveticaNeue-Light\', \'Helvetica Neue Light\', \'Helvetica Neue\', Helvetica, Arial, \'Lucida Grande\', sans-serif; line-height: 1.1; color: #000; font-weight: 500; font-size: 27px; margin: 0 0 15px; padding: 0;">Hi ' . $name . ',</h3>
								<p class="lead" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 17px; line-height: 1.6; margin: 0 0 10px; padding: 0;">This is an automatically-generated email from VATeir.</p>
								<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">Unfortunately, ' . $mentorname . ' has had to cancel the session they had with you on ' . date("j F Y", strtotime($session->start)) .' between ' . date("H:i", strtotime($session->start)) . ' and ' . date("H:i", strtotime($session->finish)) .'.</p>
								<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">We apologise for any inconvenience which this cancellation may cause.</p>
								<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">Student and mentor are both recipients of this email.</p>
								<br>
								<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">With best wishes,</p>
								<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;"><i style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">VATeir Training Department</i></p>
							</td>
						</tr>
					</table>
				</div>

			</td>
		</tr>
	</table>

	<table class="footer-wrap" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; clear: both !important; margin: 0; padding: 0;">
		<tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
			<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"></td>
			<td class="container" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 0;">

				<div class="content" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; max-width: 600px; display: block; margin: 0 auto; padding: 15px;">
					<table style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;">
						<tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
							<td align="center" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">
								<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">
									<a href="http://www.vateir.org/privacy.php" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; color: #2BA6CB; margin: 0; padding: 0;">Privacy</a>
								</p>
							</td>
						</tr>
					</table>
				</div>

			</td>
			<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"></td>
		</tr>
	</table>


	<style type="text/css">
	img { max-width: 100% !important; }
	body { -webkit-font-smoothing: antialiased !important; -webkit-text-size-adjust: none !important; width: 100% !important; height: 100% !important; }
	</style>
	</body>
	</html>';
		require_once('../../email/send.php');

	}



			Session::flash('success', 'Session cancelled!');
			Redirect::to('./index.php');

	} else {
		Session::flash('error', 'Cannot find session with that ID.');
		Redirect::to('./index.php');
	}
}catch(Exception $o) {
	echo $o->getMessage();
}
