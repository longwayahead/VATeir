<?php
require_once("../includes/header.php");
unset($user);
$u = new User;
$login_type = $_SESSION['ssologin'];
$user = $_SESSION['ssouser'];

//Reverify that they have accepted all the terms and conditions.

$typ = ($login_type == 'site') ? 0 : 1;

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

	    // 	if($user->user->rating->id > 7) { //get the CID's real rating (instead of SUP/ADM/INS etc)
			// 	$rating = $u->getRealRating($user->user->id);
			// } else {
			// 	$rating = $user->user->rating->id;
			// }
			// //check rating is the same as before
	    // 	$changeProgram = false;
	    // 	if($u->data()->rating != $rating) {
	    // 		$changeProgram = true;
	    // 	}
			//$pilotRating = $t->pilotRating($user->user->pilot_rating->rating);
	    	//change user to alive and update their details
	    //	if($user->user->division->code == "EUD" && $user->user->subdivision->code == "IRL") {
			// 	$u->update([
			// 		'alive' 		=> 1,
			// 		'vateir_status' => 1,
			// 		//'first_name' 	=> $user->user->name_first,
			// 		//'last_name' 	=> $user->user->name_last,
			// 		//'email'			=> $user->user->email,
			// 		//'rating'		=> $rating,
			// 		//'pilot_rating'	=> $user->user->pilot_rating->rating,
			// 		//'pratingstring'	=> $pilotRating
			// 	], [['id', '=', $u->data()->id]]);
			// } else {
			// 	$u->update([
			// 		'alive' 		=> 1,
			// 		'vateir_status' => 2,
			// 		//'first_name' 	=> $user->user->name_first,
			// 		//'last_name' 	=> $user->user->name_last,
			// 		//'email'			=> $user->user->email,
			// 		//'rating'		=> $rating,
			// 		//'pilot_rating'	=> $user->user->pilot_rating->rating,
			// 		//'pratingstring'	=> $pilotRating
			// 	], [['id', '=', $u->data()->id]]);
			//}
			if($user->user->alive == 0) {
				$u->update([
					'alive' => 1
				], [['id', '=', $u->data()->id]]);
			}
			// if($changeProgram === true) {
			// 	$t = new Training;
			// 	$program = $t->program($rating);
			// 	$studentUpdate = $t->updateStudent(array(
			// 		'program'	=> 	$program
			// 	), [['cid', '=', $user->user->id]]);
			// }

	    	Session::flash('success', 'You are now logged in!');
	    	Redirect::to('../index.php');
	    } elseif(!$siteLogin && $user->user->division->code == "EUD" && $user->user->subdivision->code == "IRL") {


	    	//echo '1';
	    	//check to see if they already have an account
// 	    	if($u->find($user->user->id)) { //the status of their account has changed since their account was created
// 	    		echo '2';
// 	    		$u->update([
// 					'alive' 		=> 1,
// 					'vateir_status' => 1,
// 				], [['id', '=', $u->data()->id]]);
// 	    		if($user->user->rating->id > 7) {
// 	    			echo '3';
// 					$rating = $u->getRealRating($user->user->id);
// 				} else {
// 					echo '4';
// 					$rating = $user->user->rating->id;
// 				}
// 				if(!$t->getStudent($user->user->id)) {
// 					echo '5';
// 					$program = $t->program($rating);
//
// 					$studentMake = $t->createStudent(array(
// 						'cid'		=> $user->user->id,
// 						'program'	=> 	$program
// 					));
// 				}
// 	    	} else {
// echo '6';
//
// 				try { //Try making an account if they are a member of VATeir...
// 					echo '7';
// 					if($user->user->rating->id > 7) {
//
// 						$rating = $u->getRealRating($user->user->id);
// 					} else {
// 						$rating = $user->user->rating->id;
// 					}
//
// 						$pilotRating = $t->pilotRating($user->user->pilot_rating->rating);
// 						echo '8';
// 					$make = $u->create(array(
// 						'id' 				=> $user->user->id,
// 						'alive'				=> 1,
// 						'first_name' 		=> $user->user->name_first,
// 						'last_name' 		=> $user->user->name_last,
// 						'email' 			=> $user->user->email,
// 						'rating' 			=> $rating,
// 						'pilot_rating' 		=> $user->user->pilot_rating->rating,
// 						'pratingstring'		=> $pilotRating,
// 						'regdate_vatsim' 	=> date("Y-m-d H:i:s", strtotime($user->user->reg_date)),
// 						'regdate_vateir' 	=> date('Y-m-d H:i:s'),
// 						'grou'				=> 10
// 					));
//
// 					if(!$t->findStudent($user->user->id)) {
// 						$program = $t->program($rating);
// 						$studentMake = $t->createStudent(array(
// 							'cid'		=> $controller->cid,
// 							'program'	=> 	$program
// 						));
// 					}
//
//
//
//
// 					$u->login($user->user->id);
//
//
//
					// Session::flash('info', 'Your account records haven\'t been made available by VATSIM yet. Please allow 5 days for this to happen.');
					// Redirect::to('../index.php');
				// } catch (Exception $x) {
				// 	echo $x->getMessage();
				// }
			// }
			echo "<h4>Hey " . $user->user->name_first . ",</h4>";

		?>
			<div style="font-size:16px">
				<p>Welcome to VATeir! We can see that you are a registered member of VATeir, but that your account details haven't reached our database yet. Account details have to be passed from VATSIM to VATEUD and then on to us. Please allow up to five days for this to happen.<br></p>
				<p>In the mean time, please feel free to stop by the <a target="_blank" href="<?php echo BASE_URL . 'forum';?>">forum</a>, or to pop on to the <a href="ts3server://ts.vateud.net?nickname=<?php echo $user->user->name_first . ' ' . $user->user->name_last;?>&channel=%5Bcspacer0%5D%20vACC%20ROOMS%2FIreland%20vACC">Teamspeak Server</a>.</p>
			</div>
			<?php
	    } else {

				echo "<h4>Hey " . $user->user->name_first . ",</h4>";

	    ?>
	    	<div style="font-size:16px">
				<p>Unfortunately, it looks as though your account has not been set as belonging to VATeir.<br></p>
				<p>Do you think it should be? Have you just registered? <a href="<?php echo BASE_URL . 'check/?cid=' . $user->user->id;?>">Check your ID status</a> to see what the hold up is.</p>
				<p>In the mean time, please feel free to stop by the <a target="_blank" href="<?php echo BASE_URL . 'forum';?>">forum</a>, or to pop on to the <a href="ts3server://ts.vateud.net?nickname=<?php echo $user->user->name_first . ' ' . $user->user->name_last;?>&channel=%5Bcspacer0%5D%20vACC%20ROOMS%2FIreland%20vACC">Teamspeak Server</a>.</p>

				<p><div class="text-danger">For those looking for to control in Ireland as a visiting controller, we are not accepting any visiting controller requests at present due to a backlog in training our own students.</div></p>
				<?php
					// $notAllowed = ($user->user->rating->id > 2) ? false : true; //Set the rating to be S2 and above for visiting controller applications
					// if ($notAllowed === true) {
					// 	echo '<p><div class="text-danger">One thing though: you must be at least an S2 to become a visiting controller in VATeir.</div></p>';
					// }
			?>
			</div>
				<!-- <form method="post" action="apply.php">
				    <div class="wrapper">
				    <span class="group-btn">
				    	<br>
				    	<div class="row">
				    		<div class="text-center">
								<button disabled type="submit" name="visiting" class="<?php //echo ($notAllowed === true) ? 'disabled ' : '' ;?>btn btn-success btn-lg">
									<span class="glyphicon glyphicon-plane" aria-hidden="true"></span> Become a visiting <?php //echo $user->user->rating->short; ?>
								</button>
								<br>
								<br>
								<button disabled type="submit" name="transfer" class="btn btn-warning btn-lg">
									<span class="glyphicon glyphicon-road" aria-hidden="true"></span> Transfer to VATeir
				        		</button>
				        	</div>
				        <br>
				        <br>
				    </span>
				    <input type="hidden" name="data" value="<?php //echo htmlspecialchars(serialize($user->user), ENT_QUOTES); ?>">
				</form> -->

		<?php

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
			echo '<form id="form" action="' . BASE_URL . 'forum/register.php" method="post">
					<input type="hidden" name="token" value="' . Token::generate() . '">
					<input type="hidden" name="data" value="' . htmlentities(serialize($forum_data)) . '">
					</form>
				<script type="text/javascript">
				document.getElementById("form").submit();
				</script>';
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
			echo '<form id="form" action="' . BASE_URL . 'forum/login.php" method="post">
				<input type="hidden" name="token" value="' . Token::generate() . '">
				<input type="hidden" name="forum_id" value="' . $forum_id . '">
			</form>
			<script type="text/javascript">
				document.getElementById("form").submit();
			</script>';
		}
	}
