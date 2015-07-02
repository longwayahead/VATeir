<?php
require_once("../includes/header.php");
$user = new User;
if($user->isLoggedIn() && !isset($_GET['forum'])) {
	//add a splash message
	Redirect::to("../index.php");
}


?>

<div class="container">
    <div class="row">
        <div class="col-md-offset-4 col-md-4">
        	<div class="form-login well">

    <?php $loginCheck = ($user->loginOpen()) ? false : true; //check that login is open...
		if($loginCheck == true && (!isset($_GET['or']) || !isset($_GET['return']))) {
			echo '<div class="panel panel-danger">
					<div class="panel-heading">
						<h3 class="panel-title">Login status</h3>
					</div>
					<div class="panel-body text-center">
						
							Login is closed at the moment.<br>Please check back later.
						
					</div>
				</div>

			<br>';
			die();
		}
		
		    		
		    		

	unset($user);

	ini_set('error_reporting', E_ALL);
	ini_set("display_errors", 1);

	require('OAuth.php');
	require('SSO.class.php');
	require('config.php');

	// initiate the SSO class with consumer details and encryption details
	$SSO = new SSO($sso['base'], $sso['key'], $sso['secret'], $sso['method'], $sso['cert']);

	// return variable is needed later in this script
	$sso_return = $sso['return'];
	if(isset($_GET['forum'])) {
		$sso_return .= '&forum';
	}
	// remove other config variables
	unset($sso);

	// if VATSIM has redirected the member back
	if (isset($_GET['return']) && isset($_GET['oauth_verifier']) && !isset($_GET['oauth_cancel'])){
	    // check to make sure there is a saved token for this user
	    if (isset($_SESSION[SSO_SESSION]) && isset($_SESSION[SSO_SESSION]['key']) && isset($_SESSION[SSO_SESSION]['secret'])){
	        
	        /*
	         * NOTE: Always request the user data as soon as the member is sent back and then redirect the user away
	         */
	        
	        //echo '<a href="index.php">Return</a><br />';
	        
	        if (@$_GET['oauth_token']!=$_SESSION[SSO_SESSION]['key']){
	            echo '<p>Returned token does not match</p>';
	            die();
	        }
	        
	        if (@!isset($_GET['oauth_verifier'])){
	            echo '<p>No verification code provided</p>';
	            die();
	        }
	        
	        // obtain the details of this user from VATSIM
	        $user = $SSO->checkLogin($_SESSION[SSO_SESSION]['key'], $_SESSION[SSO_SESSION]['secret'], @$_GET['oauth_verifier']);
	        
	        if ($user){
	       		$u = new User;
	        	if($loginCheck == true && $user->user->id != 1032602) {
	        		Session::flash('error', 'Sorry, login to VATeir is closed at the moment.');
	        		Redirect::to("../index.php");
	        	}
	            // One-time use of tokens, token no longer valid
	            unset($_SESSION[SSO_SESSION]);

	            if(!isset($_GET['forum'])) { //trying to log into the site: log into the site and forum
	            
			            // Output this user's details
			           // echo '<p>Login Success</p>';
			            // echo '<pre style="font-size: 11px;">';
			           
			                 
			            //   print_r($user->user);
			            // echo '</pre>';
			            // die();
			            //See if the user is a vateir member and try to log them in
			            
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
			            

			    } elseif(isset($_GET['forum'])) { //trying to log into the forum only
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
	    		// do not proceed to send the user back to VATSIM
	            die();
	        } else {
	            // OAuth or cURL errors have occurred, output here
	            echo '<p>An error occurred</p>';
	            $error = $SSO->error();

	            if ($error['code']){
	                echo '<p>Error code: '.$error['code'].'</p>';
	            }

	            echo '<p>Error message: '.$error['message'].'</p>';
	            require_once('../includes/footer.php');
	            // do not proceed to send the user back to VATSIM
	            die();
	        }
	    } 
	// the user cancelled their login and were sent back
	} else if (isset($_GET['return']) && isset($_GET['oauth_cancel'])){
	    echo '<a href="index.php">Start Again</a><br />';
	    
	    echo '<p>You cancelled your login.</p>';
	    require_once('../includes/footer.php');
	    die();
	}

	// create a request token for this login. Provides return URL and suspended/inactive settings
	$token = $SSO->requestToken($sso_return, false, false);

	if ($token){
	    
	    // store the token information in the session so that we can retrieve it when the user returns
	    $_SESSION[SSO_SESSION] = array(
	        'key' => (string)$token->token->oauth_token, // identifying string for this token
	        'secret' => (string)$token->token->oauth_token_secret // secret (password) for this token. Keep server-side, do not make visible to the user
	    );
	    
	    // redirect the member to VATSIM
	    $SSO->sendToVatsim();
	    
	} else {
	    
	    echo '<p>An error occurred</p>';
	    $error = $SSO->error();
	    
	    if ($error['code']){
	        echo '<p>Error code: '.$error['code'].'</p>';
	    }
	    
	    echo '<p>Error message: '.$error['message'].'</p>';
	    
	}
//}
?>
			</div>
		</div>
	</div>
</div>
<?php
require_once('../includes/footer.php');