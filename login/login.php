<?php
require_once("../includes/header.php");
unset($user);
$u = new User;
//Reverify that they have accepted all the terms and conditions.
$typ = ($login_type == 'site') ? 0 : 1;
$terms = $u->terms($typ, $user->user->id);

if(!empty($terms)) { //If not, redirect them away so that they can agree to them before being logged in.
	Redirect::to('terms.php');
}

$login_type = $_SESSION['ssologin'];
unset($_SESSION['ssologin']);
$user = $_SESSION['ssouser'];
unset($_SESSION['ssouser']);

	if($login_type == 'site') { //trying to log into the site: log into the site and forum
		            		
		$allowed = [
						1032602,
						931070, // Martin Bergin
						1031024, // Adam Arkley
						907674, // Paul Williams
						1304314, // Paul Mc Dyer
						1231276, // Mark Foister
						1046871 // Neil Ryans
					];
		if($loginCheck == true && !in_array($user->user->id, $allowed)) {
			Session::flash('error', 'Sorry, login to VATeir is closed at the moment.');
			Redirect::to("../index.php");
		}
	    
	    $t = new Training;
	    try {
	    	$siteLogin = $u->login($user->user->id);
		} catch(Exception $l) {
			echo $l->getMessage();
		}
	    if($siteLogin) {
	    	if($user->user->rating->id > 7) { //get the CID's real rating (instead of SUP/ADM/INS etc)
				$rating = $u->getRealRating($user->user->id);
			} else {
				$rating = $user->user->rating->id;
			}
			$pilotRating = $t->pilotRating($user->user->pilot_rating->rating);
	    	//change user to alive and update their details
	    	if($user->user->division->code == "EUD" && $user->user->subdivision->code == "IRL") {
				$u->update([
					'alive' 		=> 1,
					'vateir_status' => 1,
					'first_name' 	=> $user->user->name_first,
					'last_name' 	=> $user->user->name_last,
					'email'			=> $user->user->email,
					'rating'		=> $rating,
					'pilot_rating'	=> $user->user->pilot_rating->rating,
					'pratingstring'	=> $pilotRating
				], [['id', '=', $u->data()->id]]);
			} else {
				$u->update([
					'alive' 		=> 1,
					'vateir_status' => 2,
					'first_name' 	=> $user->user->name_first,
					'last_name' 	=> $user->user->name_last,
					'email'			=> $user->user->email,
					'rating'		=> $rating,
					'pilot_rating'	=> $user->user->pilot_rating->rating,
					'pratingstring'	=> $pilotRating
				], [['id', '=', $u->data()->id]]);
			}

			$t = new Training;
			$program = $t->program($rating);
			$studentUpdate = $t->updateStudent(array(
				'program'	=> 	$program
			), [['cid', '=', $user->user->id]]);
			



	    	Session::flash('success', 'You are now logged in!');
	    	Redirect::to('../index.php');
	    } elseif(!$siteLogin && $user->user->division->code == "EUD" && $user->user->subdivision->code == "IRL") { //Possible future feature - create an account apart from CRON here.
			try { //Try making an account if they are a member of VATeir...
				if($user->user->rating->id > 7) {
						$rating = $u->getRealRating($user->user->id);
					} else {
						$rating = $user->user->rating->id;
					}
					
					$pilotRating = $t->pilotRating($user->user->pilot_rating->rating);
				
				$make = $u->create(array(
					'id' 				=> $user->user->id,
					'alive'				=> 1,
					'first_name' 		=> $user->user->name_first,
					'last_name' 		=> $user->user->name_last,
					'email' 			=> $user->user->email,
					'rating' 			=> $rating,
					'pilot_rating' 		=> $user->user->pilot_rating->rating,
					'pratingstring'		=> $pilotRating,
					'regdate_vatsim' 	=> date("Y-m-d H:i:s", strtotime($user->user->reg_date)),
					'regdate_vateir' 	=> date('Y-m-d H:i:s'),
					'grou'				=> 10
				));

				
				if(!$t->getStudent($user->user->id)) {
					$program = $t->program($rating);

					$studentMake = $t->createStudent(array(
						'cid'		=> $user->user->id,
						'program'	=> 	$program
					));
				}
				
			

				$u->login($user->user->id);

			

				Session::flash('success', 'You are now logged in!');
				Redirect::to('../index.php');
			} catch (Exception $x) {
				echo $x->getMessage();
			}
	    } else {
	    	$notAllowed = ($user->user->rating->id > 2) ? false : true; //Set the rating to be S2 and above for visiting controller applications
				echo "<h4>Hey " . $user->user->name_first . ",</h4>";
	    
	    ?>
	    	<div style="font-size:16px">
				<p>It looks like you're not a member of VATeir.<br>
					Don't fret though, it's super simple to become a visiting controller or to transfer!</p>
				<p>Select from one of the options below to get started on your application!</p>
				<?php
					if ($notAllowed === true) {
						echo '<p><div class="text-danger">One thing though: you must be at least an S2 to become a visiting controller in VATeir</div></p>';
					}
			?>
			</div>
				<form method="post" action="apply.php">
				    <div class="wrapper">
				    <span class="group-btn">
				    	<br>
				    	<div class="row">
				    		<div class="text-center">
								<button type="submit" name="visiting" class="<?php echo ($notAllowed === true) ? 'disabled ' : '' ;?>btn btn-success btn-lg">
									<span class="glyphicon glyphicon-plane" aria-hidden="true"></span> Become a visiting <?php echo $user->user->rating->short; ?>
								</button>
								<br>
								<br>
								<button type="submit" name="transfer" class="btn btn-warning btn-lg">
									<span class="glyphicon glyphicon-road" aria-hidden="true"></span> Transfer to VATeir
				        		</button>
				        	</div>
				        <br>
				        <br>
				    </span>
				    <input type="hidden" name="data" value="<?php echo htmlspecialchars(serialize($user->user), ENT_QUOTES); ?>">
				</form>
		
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