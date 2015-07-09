<?php
$pagetitle = 'Login';
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

    <?php $loginCheck = ($user->loginOpen()) ? false : true; //check that login is closed...
		if($loginCheck == true && (!isset($_GET['or']) && !isset($_GET['return']) && !isset($_GET['forum']))) { //
			echo '<div class="panel panel-danger">
					<div class="panel-heading">
						<h3 class="panel-title">Login status</h3>
					</div>
					<div class="panel-body text-center">
						
						Login is closed at the moment to add a feature.<br>Please check back soon.
						
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
	$end = null;
	if(isset($_GET['forum'])) {
		$sso_return .= '&forum';
		$end = '?forum';
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
	            // One-time use of tokens, token no longer valid
	            unset($_SESSION[SSO_SESSION]);
	            $_SESSION['ssouser'] = $user;

	            //set login type
	           	if(!isset($_GET['forum'])) {
	            	$_SESSION['ssologin'] = 'site';
	            } else {
	            	$_SESSION['ssologin'] = 'forum';
	            }

	            //Verify agreement to all T&Cs.
	            $typ = (!isset($_GET['forum'])) ? 0 : 1;
	            $terms = $u->terms($typ, $user->user->id);
	            if(!empty($terms)) { //If not, redirect them away so that they can agree to them before being logged in.
	            	Redirect::to('terms.php');
	            }
				//Otherwise they have agreed so we can go ahead and log them in!
				Redirect::to('login.php');
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
	    echo '<a href="index.php' . $end . '">Start Again</a><br />';
	    
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