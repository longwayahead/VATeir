<?php
session_start();
require_once('../includes/header.php');
$f = new Forum;
if($user->isLoggedIn()) {
	$get = $f->getID($user->data()->id);
	if($get == true) {
		echo 'Logging you in. Please wait.';
			echo '<form id="form" action="../forum/login.php" method="post">
				<input type="hidden" name="token" value="' . Token::generate() . '">
				<input type="hidden" name="id" value="' . $get . '">
			</form>
			<script type="text/javascript">
				document.getElementById("form").submit();
			</script>';
	} else {
		echo '1';
		//Redirect::to('../login/index.php?forum');
	}
} else {
	echo '2';
	//Redirect::to('../login/index.php?forum');
}


?>