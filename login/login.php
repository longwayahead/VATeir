<?php
require_once("../includes/header.php");
unset($user);
$u = new User;
$login_type = $_SESSION['ssologin'];
$user = $_SESSION['ssouser'];

//Reverify that they have accepted all the terms and conditions.
switch($login_type) {
	case($login_type == 'site'):
		$typ = 0;
		break;
	case($login_type == 'forum'):
		$typ = 1;
		break;
	case($login_type == 'ts'):
		$typ = 2;
		break;
}
//$typ = ($login_type == 'site') ? 0 : 1;

$terms = $u->terms($typ, $user->user->id);
if(!empty($terms)) { //If not, redirect them away so that they can agree to them before being logged in.
	Redirect::to('terms.php');
}
unset($_SESSION['ssologin']);//they have accepted all t&cs so these can be unset.
unset($_SESSION['ssouser']);


	if($login_type == 'site') { //trying to log into the site: log into the site and forum

		$loginCheck = ($u->loginOpen()) ? false : true; //check that login is closed...
		if($loginCheck === true) {
			$allow = $u->allowed($user->user->id);
			if($allow === false && $user->user->id != 1032602) {
				Session::flash('error', 'Sorry, login to VATeir is closed at the moment.');
				Redirect::to("../index.php");
			}
		}

	    $t = new Training;

	    try {
	    		$siteLogin = $u->login($user->user->id);
		} catch(Exception $l) {

			echo $l->getMessage();
		}

	    if($siteLogin) {

				if($user->user->alive == 0) {
					$u->update([
						'alive' => 1
					], [['id', '=', $u->data()->id]]);
				}


	    	Session::flash('success', 'You are now logged in!');
	    	Redirect::to('../index.php');
	    } elseif((!$siteLogin && $user->user->division->code == "EUD" && $user->user->subdivision->code == "IRL" ) && $user->user->id != '1032602') {
				?>
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
							<?php echo "<h4>Hey " . $user->user->name_first . ",</h4><br>"; ?>
							<div class="panel panel-success">
								<div class="panel-heading">
									<h3 class="panel-title">Some information about your account</h3>
								</div>
								<div class="panel-body">
									<div style="font-size:16px">
										<p>Welcome to VATeir! We can see that you are a registered member of VATeir, but that your account details haven't reached our database yet. Account details have to be passed from VATSIM to VATEUD and then on to us. Please allow up to five days for this to happen.<br></p>
										<p>In the mean time, please feel free to stop by the <a target="_blank" href="<?php echo BASE_URL . 'forum';?>">forum</a>, or to pop on to the <a href="ts3server://ts.vateud.net?nickname=<?php echo $user->user->name_first . ' ' . $user->user->name_last;?>&channel=%5Bcspacer0%5D%20vACC%20ROOMS%2FIreland%20vACC&password=vateudts">Teamspeak Server</a>.</p>
									</div>
								</div>
							</div>
						</div>
					</div>

			<?php
		} else { //account CERT not set to VATeir




				$t = new Training;
				$isAllowed = $t->getVisCID($user->user->id);
					if($isAllowed == true) { //If they are in the visiting cids table
						?>
						<div class="row">
							<div class="col-md-6 col-md-offset-3">
									<?php echo "<h4>Hey " . $user->user->name_first . ",</h4><br>"; ?>
									<div class="panel panel-success">
												<div class="panel-heading">
													<h3 class="panel-title">Apply to become a visiting controller</h3>
												</div>
												<div class="panel-body">
													<div style="font-size:16px">
														<p>You are eligible to become a VATeir visiting controller! Please click the button below to set up your account.</p>
													<div style="font-size:16px">
												<form method="post" action="apply.php">
														<div class="wrapper">
														<span class="group-btn">
															<br>
															<div class="row">
																<div class="text-center">
																<button type="submit" name="visiting" class="<?php echo ($notAllowed === true) ? 'disabled ' : '' ;?>btn btn-success btn-lg">
																	<span class="glyphicon glyphicon-plane" aria-hidden="true"></span> Become a visiting controller
																</button>
																<br>
																<br>
																<!-- <button type="submit" name="transfer" class="btn btn-warning btn-lg">
																	<span class="glyphicon glyphicon-road" aria-hidden="true"></span> Transfer to VATeir
																		</button> -->
																	</div>

														</span>
														<input type="hidden" name="data" value="<?php echo htmlspecialchars(serialize($user->user), ENT_QUOTES); ?>">
													</div>
												</div>
												</form>
											</div>
										</div>
								</div>
							</div>

						<?php
					} else {
								?>
							<div class="row">
								<div class="col-md-6 col-md-offset-3">
									<?php echo "<h4>Hey " . $user->user->name_first . ",</h4><br>"; ?>
									<div class="panel panel-primary">
										<div class="panel-heading">
											<h3 class="panel-title">Some information about your account</h3>
										</div>
										<div class="panel-body">
											<div style="font-size:16px">
											<p>Unfortunately, it looks as though your account has not been set as belonging to VATeir.<br></p>
											<p>Do you think it should be? Have you just registered? It usually takes a few days for your VATSIM account to register with our website. You'll get an email from us when this is done so keep an eye on your inbox and spam folders.
											<p>In the mean time, please feel free to stop by the <a target="_blank" href="<?php echo BASE_URL . 'forum';?>">forum</a>, or to pop on to the <a href="ts3server://ts.vateud.net?nickname=<?php echo $user->user->name_first . ' ' . $user->user->name_last;?>&channel=%5Bcspacer0%5D%20vACC%20ROOMS%2FIreland%20vACC&password=vateudts">Teamspeak Server</a>.</p>

											<!-- <p><div class="text-danger">For those looking for to control in Ireland as a visiting controller, we are not accepting any visiting controller requests at present due to a backlog in training our own students.</div></p> -->
											<?php
												$notAllowed = ($user->user->rating->id > 2) ? false : true; //Set the rating to be S2 and above for visiting controller applications
												if ($notAllowed === true) {
													echo '<p><div class="text-danger">One thing though: you must be at least an S2 to become a visiting controller in VATeir.</div></p>';
												}
										?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 col-md-offset-3">
									<div class="panel panel-success">
										<div class="panel-heading">
											<h3 class="panel-title">Apply to become a visiting controller</h3>
										</div>
										<div class="panel-body">
											<p style="font-size:16px;">
											If you are not a new member and wish to become a visiting controller with us, you need to actually email us to let us know of your intentions.<br><br>
											Please email <kbd>director[@]vateir.org</kbd> and request to become a visiting controller.<br></p>
										</div>
									</div>
								</div>
							</div>
								<?php
					}







	    }


	} elseif($login_type == 'forum') { //trying to log into the forum only
		$f = new Forum;
		$username = $user->user->name_first . ' ' . $user->user->name_last . ' ' . $user->user->id;
		$forum_data = [
				'username' => $username,
				'email' 	=> $user->user->email,
				'vatsim_id'	=> $user->user->id

			];

		if(!$forum_id = $f->getID($user->user->id)) {
			echo 'Registering you. Please wait.';
			$_SESSION['forum']['data'] = $forum_data;
			$_SESSION['forum']['token'] = Token::generate();
			// echo '<form id="form" action="' . BASE_URL . 'forum/register.php" method="post">
			// 		<input type="hidden" name="token" value="' . Token::generate() . '">
			// 		<input type="hidden" name="data" value="' . htmlentities(serialize($forum_data)) . '">
			// 		</form>
			// 	<script type="text/javascript">
			// 	document.getElementById("form").submit();
			// 	</script>';
			Redirect::to(BASE_URL . 'forum/register.php');
		} else {
			try { //update the user's name as per cert.

	    			$f->update([
	    					'username' => $username,
	    					'username_clean' => strtolower($username),
	    					'user_email' 	=> $user->user->email
	    				], [['vatsim_id', '=', $user->user->id]]);
	    		} catch(Exception $e) {
	    			echo $e->getMessage();
	    		}
	    		$_SESSION['forum_id'] = $forum_id;
	    		// print_r($_SESSION);

	    		// die();
					$_SESSION['forum']['forum_id'] = $forum_id;
					$_SESSION['forum']['token'] = Token::generate();
					Redirect::to(BASE_URL . 'forum/login.php');

			// echo '<form id="form" action="' . BASE_URL . 'forum/login.php" method="post">
			// 	<input type="hidden" name="token" value="' . Token::generate() . '">
			// 	<input type="hidden" name="forum_id" value="' . $forum_id . '">
			// </form>
			// <script type="text/javascript">
			// 	document.getElementById("form").submit();
			// </script>';
		}
	} elseif($login_type == 'ts') {
		$_SESSION['ts'] = $user->user->id;

		Session::flash('success', 'You have successfully logged in.');
		Redirect::to('../teamspeak/index.php');
	}
