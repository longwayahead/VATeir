<?php
require_once("includes/header.php");

$t = new Training;
$user = new User;


////CONNECTING TO VATEUD API////
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, Config::get('vateud/vaccmembers')); //Set the URL here
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLINFO_HEADER_OUT, true); //Set a custom header for auth token


curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	'Authorization: Token token="' . Config::get('vateud/apitoken') . '"'
));


$get = curl_exec($curl); //Get the data from vateud
curl_close($curl);

////CONNECTION CLOSED////

$controllers = json_decode($get);

$register = array();
// $listCID = array();
//echo '<pre>', print_r($controllers), '</pre>';
foreach($controllers as $controller) {	//Register users if they aren't already in the VATeir database.
	// $listCID[] = $controller->cid;
	try {
		if(!$user->find($controller->cid)) { //CID not already in database. New member - register them!

			if($controller->rating > 7) {
				$rating = $user->getRealRating($controller->cid);
			} else {
				$rating = $controller->rating;
			}

			$make = $user->create(array(
				'id' 				=> $controller->cid,
				'first_name' 		=> $controller->firstname,
				'last_name' 		=> $controller->lastname,
				'email' 			=> $controller->email,
				'rating' 			=> $rating,
				'pilot_rating' 		=> $controller->pilot_rating,
				'pratingstring'		=> $controller->humanized_pilot_rating,
				'regdate_vatsim' 	=> date("Y-m-d H:i:s", strtotime($controller->reg_date)),
				'regdate_vateir' 	=> date('Y-m-d H:i:s'),
				'vateir_status'		=> 1
			));
			if(!$t->findStudent($controller->cid)) {
				$program = $t->program($rating);
				$studentMake = $t->createStudent(array(
					'cid'		=> $controller->cid,
					'program'	=> 	$program
				));
			}
			$email = $controller->email;
			$name = $controller->firstname;
			$from = 'noreply';
			$subject = 'Welcome to VATeir!';
			$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">&#13;
			<head>&#13;
			<!-- If you delete this meta tag, Half Life 3 will never be released. -->&#13;
			<meta name="viewport" content="width=device-width" />&#13;
			&#13;
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />&#13;
			<title>Welcome to VATeir!</title>&#13;
			&#13;
			&#13;
			</head>&#13;
			&#13;
			<body bgcolor="#FFFFFF" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; margin: 0; padding: 0;">&#13;
			&#13;
			<!-- HEADER -->&#13;
			<table class="head-wrap" bgcolor="#999999" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;">&#13;
				<tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">&#13;
					<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"></td>&#13;
					<td class="header container" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 0;">&#13;
			&#13;
							<div class="content" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; max-width: 600px; display: block; margin: 0 auto; padding: 15px;">&#13;
							<table bgcolor="#999999" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;">&#13;
								<tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">&#13;
									<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"><img style="max-width: 200px; font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" src="https://i.img.ie/suw.png" /></td>&#13;
								</tr>&#13;
							</table>&#13;
							</div>&#13;
			&#13;
					</td>&#13;
					<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"></td>&#13;
				</tr>&#13;
			</table><!-- /HEADER -->&#13;
			&#13;
			&#13;
			<!-- BODY -->&#13;
			<table class="body-wrap" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;">&#13;
				<tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">&#13;
					<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"></td>&#13;
					<td class="container" bgcolor="#FFFFFF" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 0;">&#13;
			&#13;
						<div class="content" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; max-width: 600px; display: block; margin: 0 auto; padding: 15px;">&#13;
						<table style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;">&#13;
							<tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">&#13;
								<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">&#13;
									<h3 style="font-family: \'HelveticaNeue-Light\', \'Helvetica Neue Light\', \'Helvetica Neue\', Helvetica, Arial, \'Lucida Grande\', sans-serif; line-height: 1.1; color: #000; font-weight: 500; font-size: 27px; margin: 0 0 15px; padding: 0;">Hi ' . $name .',</h3>&#13;
									<p class="lead" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 17px; line-height: 1.6; margin: 0 0 10px; padding: 0;">Welcome to VATeir!</p>&#13;
									<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">If you are receiving this email, it is because your account has been marked as belonging to VATeir, the Irish virtual Area Control Centre (vACC) of VATSIM.</p>&#13;
									<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">At the moment, there are two courses of action open to you, the choosing of one or both of which depends on what you would like to get out of your time on the network, flying or controlling in Ireland.</p>&#13;
									<hr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" />&#13;
									<br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" />&#13;
									<h5 style="font-family: \'HelveticaNeue-Light\', \'Helvetica Neue Light\', \'Helvetica Neue\', Helvetica, Arial, \'Lucida Grande\', sans-serif; line-height: 1.1; color: #000; font-weight: 900; font-size: 17px; margin: 0 0 15px; padding: 0;">Becoming a pilot...</h5>&#13;
									<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">Good news; you don\'t have to do anything! By default, your account is already all set up for you to log on to the network and to fly. Below is a list of resources which will help you to get going:&#13;
										</p><ul style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">&#13;
											<li style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; list-style-position: inside; margin: 0 0 0 5px; padding: 0;"><a href="https://www.vatsim.net/pilot-resource-centre" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; color: #2BA6CB; margin: 0; padding: 0;">VATSIM Pilots\' Resource Centre</a>&mdash;<i style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">Use the links in the sidebar on the left in order to navigate the PRC. Good information can be found here about how to connect and how to conduct a flight on the network.</i></li>&#13;
											<li style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; list-style-position: inside; margin: 0 0 0 5px; padding: 0;"><a href="http://vateir.org/pilots/" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; color: #2BA6CB; margin: 0; padding: 0;">VATeir Pilots\' Section</a>&mdash;<i style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">Irish charts, controller bookings, AIRAC information, worldwide weather, Irish NOTAMs, VATSIM [flight plan] pre-file, fuel planning, and North Atlantic Tracks can be found here.</i></li>&#13;
										</ul>&#13;
									&#13;
									<hr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" />&#13;
									<br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" />&#13;
									<h5 style="font-family: \'HelveticaNeue-Light\', \'Helvetica Neue Light\', \'Helvetica Neue\', Helvetica, Arial, \'Lucida Grande\', sans-serif; line-height: 1.1; color: #000; font-weight: 900; font-size: 17px; margin: 0 0 15px; padding: 0;">Becoming a controller...</h5>&#13;
									<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">Good news; you\'re almost there! The first thing to do is to request training through the <i style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">Training</i> section of the VATeir website. Follow the steps below to request mentoring.</p>&#13;
									<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">&#13;
										</p><ul style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">&#13;
											<li style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; list-style-position: inside; margin: 0 0 0 5px; padding: 0;">&#13;
													Navigate to the VATeir website and log in using the link at the top right of the page: <a href="http://www.vateir.org/" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; color: #2BA6CB; margin: 0; padding: 0;">VATeir Website</a>&#13;
													<br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" /><br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" /><img src="https://i.img.ie/sVC.jpg" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; max-width: 100%; margin: 0; padding: 0;" /><br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" /><br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" />&#13;
											</li>&#13;
											<li style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; list-style-position: inside; margin: 0 0 0 5px; padding: 0;">Select <i style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">Training</i> from the menu at the top which will have appeared after you have logged in.</li>&#13;
											<li style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; list-style-position: inside; margin: 0 0 0 5px; padding: 0;">Select <i style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">Availability</i> from the menu on the left.&#13;
													<br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" /><br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" /><img src="https://i.img.ie/sVZ.jpg" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; max-width: 100%; margin: 0; padding: 0;" /><br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" /><br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" />&#13;
											</li>&#13;
											<li style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; list-style-position: inside; margin: 0 0 0 5px; padding: 0;">Click on the calendar icon to the right of the text box in order to select a date when you will be available.</li>&#13;
											<li style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; list-style-position: inside; margin: 0 0 0 5px; padding: 0;">Do the same with the clock icons in order to select a time range during which you will be available. <i style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">All times are in local Irish Standard Time</i></li>&#13;
											<li style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; list-style-position: inside; margin: 0 0 0 5px; padding: 0;">Click on <i style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">Submit</i> to submit your avilability.&#13;
													<br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" /><br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" /><img src="https://i.img.ie/sVg.jpg" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; max-width: 100%; margin: 0; padding: 0;" /></li><br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" /><br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" />&#13;
										</ul>&#13;
									&#13;
									<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">And that\'s it! A mentor may schedule a session on the date and during the range which you have submitted. Keep an eye on this email address as when this happens, an email will be sent to it with details including your mentor, session time (in IST), and any materials which the mentor requires that you read before the session.</p>&#13;
									<p class="callout" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; background-color: #ECF8FF; margin: 0 0 15px; padding: 15px;">&#13;
										<strong style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">TOP TIPS!</strong>&#13;
										You can submit as many availabilities as you like. Doing so might give you an advantage as a mentor tries to fit you into their schedule.&#13;
									</p><!-- /Callout Panel -->&#13;
									<hr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" />&#13;
									<br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" />&#13;
									<h5 style="font-family: \'HelveticaNeue-Light\', \'Helvetica Neue Light\', \'Helvetica Neue\', Helvetica, Arial, \'Lucida Grande\', sans-serif; line-height: 1.1; color: #000; font-weight: 900; font-size: 17px; margin: 0 0 15px; padding: 0;">Teamspeak</h5>&#13;
									<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">We use Teamspeak 3 for controller&ndash;controller and pilot&ndash;pilot communications within the vACC. Even if you only plan to fly, teamspeak is a very useful way to get to know members of the vACC, both pilots and controllers alike. To get started, follow the steps below.</p>&#13;
									<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">&#13;
										</p><ul style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">&#13;
											<li style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; list-style-position: inside; margin: 0 0 0 5px; padding: 0;">Download the latest build of Teamspeak 3 <a href="https://www.teamspeak.com/downloads" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; color: #2BA6CB; margin: 0; padding: 0;">from here</a>.</li>&#13;
												<br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" /><br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" />
											<li style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; list-style-position: inside; margin: 0 0 0 5px; padding: 0;">Connect to the VATEUD teamspeak server using the VATEUD teamspeak URI thus:&#13;
												<br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" /><br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" />
												<img src="https://i.img.ie/sVj.jpg" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; max-width: 100%; margin: 0; padding: 0;" /><br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" /><br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" />&#13;
											</li>&#13;
											<li style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; list-style-position: inside; margin: 0 0 0 5px; padding: 0;">The VATeir channels can be found under <i style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">Ireland vACC</i>.&#13;
												<br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" /><br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" /><img src="https://i.img.ie/sVs.jpg" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; max-width: 100%; margin: 0; padding: 0;" /><br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" /><br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" />&#13;
											</li>&#13;
										</ul>&#13;
									&#13;
									<p class="callout" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; background-color: #ECF8FF; margin: 0 0 15px; padding: 15px;">&#13;
										<strong style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">NB!</strong>&#13;
										You will need to connect to the VATEUD teamspeak and be in the VATeir channel at least five minutes before your mentoring session is scheduled to begin. Failure to do so may mean that your session will be cancelled. If you are going to be late, please email your mentor or inform them in advance by logging on to teamspeak.&#13;
									</p><!-- /Callout Panel -->&#13;
			&#13;
									<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">Tá súil againn go mbaineann tú sult as an líonra agus go mbaineann tú taitneamh as bheith mar bhall den roinn Éireannach, <br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" /><br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" /><br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" />Foirean Oiliúna VATeir<br style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;" />VATeir Training Team</p>&#13;
								</td>&#13;
							</tr>&#13;
						</table>&#13;
						</div><!-- /content -->&#13;
			&#13;
					</td>&#13;
					<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"></td>&#13;
				</tr>&#13;
			</table><!-- /BODY -->&#13;
			&#13;
			<!-- FOOTER -->&#13;
			<table class="footer-wrap" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; clear: both !important; margin: 0; padding: 0;">&#13;
				<tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">&#13;
					<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"></td>&#13;
					<td class="container" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 0;">&#13;
			&#13;
							<!-- content -->&#13;
							<div class="content" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; max-width: 600px; display: block; margin: 0 auto; padding: 15px;">&#13;
							<table style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; width: 100%; margin: 0; padding: 0;">&#13;
							<tr style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">&#13;
								<td align="center" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">&#13;
									<p style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; font-weight: normal; font-size: 14px; line-height: 1.6; margin: 0 0 10px; padding: 0;">&#13;
										<a href="http://vateir.org/privacy.php" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; color: #2BA6CB; margin: 0; padding: 0;">Privacy</a> |&#13;
										<a href="http://vateir.org/email/unsubscribe.php?e=' . $email . '	" style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; color: #2BA6CB; margin: 0; padding: 0;"><unsubscribe style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;">Unsubscribe</unsubscribe></a>&#13;
			&#13;
									</p>&#13;
								</td>&#13;
							</tr>&#13;
						</table>&#13;
							</div><!-- /content -->&#13;
			&#13;
					</td>&#13;
					<td style="font-family: \'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif; margin: 0; padding: 0;"></td>&#13;
				</tr>&#13;
			</table><!-- /FOOTER -->&#13;
			&#13;
			</body>&#13;
			</html>';
		require_once('email/send.php');

			$register["registered"][] = $controller->cid;
		}
	} catch (Exception $e) {
		echo $e->getMessage();
		$register["regfail"][$controller->cid] = $e->getMessage();
	}


/////UPDATING DATA FOR USERS THAT ARE ACTIVE//////


	if(empty($register["registered"]) || !in_array($controller->cid, $register["registered"])) { //CID hasn't just registered above...or no one has registered at all
		try { //update active user's data
			if($data = $user->isActive($controller->cid)) { //check if the user is active
				$change = [];

				if($data->first_name != $controller->firstname) {
					$change['first_name'] = $controller->firstname;
				}
				if($data->last_name != $controller->lastname && $data->last_name != '&#211; L&#250;ing') {
					$change['last_name'] = $controller->lastname;
				}
				if($data->email != $controller->email) {
					$change['email'] = $controller->email;
				}

				//get real rating
				if($controller->rating > 7) {
					$rating = $user->getRealRating($controller->cid);
				} else {
					$rating = $controller->rating;
				}
				//end get real rating


				if($data->rating != $rating) {
					$change['rating'] = $rating;
				}

				$programChange = false;
				if($data->rating != $rating) {
					$programChange = true;
				}
				if($data->pilot_rating != $controller->pilot_rating) {
					$change['pilot_rating'] = $controller->pilot_rating;
					$change['pratingstring'] = $controller->humanized_pilot_rating;
				}
				if($data->vateir_status != 1) {
					$change['vateir_status'] = 1;
				}
				if(!empty($change)) {
					print_r($change);
				}

				if(!empty($change)) {
					$update = $user->update(
						$change
					, [['id', '=', $controller->cid]]);
					if($programChange === true) { //only change the student's programme if their rating has changed. new rating = new training programme.
						$program = $t->program($rating);

						$studentUpdate = $t->updateStudent(array(
							'program'	=> 	$program
						), [['cid', '=', $controller->cid]]);
					}
					$register["updated"][$controller->cid] = $change;
				}

			} elseif($user->isAlive($controller->cid) && !$user->isActive($controller->cid)) { //Gets all "active" users, checks to make sure they're active and for those that aren't, changes their status to 0
				$update = $user->update(array(
					'alive' => 0
				), [['id', '=', $controller->cid]]);
				$register["setasinactive"][] = $controller->cid;
			}

		} catch (Exception $f) {
			echo $f->getMessage();
			$register["updatefail"][$controller->cid] = $f->getMessage();
		}

	}

unset($change);

}
//see whether the visiting controllers are still active
$dead = $user->getVisitingDead();
if(count($dead)) {
	foreach($dead as $d) {
		$update = $user->update(array(
			'alive' => 0
		), [['id', '=', $d->id]]);
		$register["setasinactive"][] = $d->id;
	}

}


//////make sure all student accounts are present and connected to a controller account
$fix = $t->fixStudents();
if ($fix != false) {
	$register['studentfix'] = $fix;
}

/////////////////////////////////////////////////////

$crons = new Crons;//
try{
	// $select = $crons->deleteNonVATeir($listCID);
	// echo '<pre>';
	// print_r($select);
	// echo '</pre>';


	 $json = (!empty($register)) ? json_encode($register) : '';

	$crons->add([	'date' => date("Y-m-d H:i:s"),
				'data' => $json//
	 			]);
				echo $json;
}catch(Exception $q) {
	$register["deletefail"] = $q->getMessage();
}
