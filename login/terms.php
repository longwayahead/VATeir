<?php
$pagetitle = "Terms and Conditions";
require_once("../includes/header.php");

$cid = $_SESSION['ssouser']->user->id;

if(isset($_POST['term_id']) && isset($_POST['submit'])) {
	echo 'test';
	$agree = $user->term_agree([
			'term_id'	=> $_POST['term_id'],
			'cid'		=> $cid,
			'date'		=> date("Y-m-d H:i:s")
		]);
}

//Get all terms and conditions
$login_type = $_SESSION['ssologin'];
$typ = ($login_type == 'site') ? 0 : 1;
$terms = $user->terms($typ, $cid);

if(!empty($terms)) {
	echo '<h3 class="text-center">Accept T&Cs</h3><br>';
	echo '<div class="row"><div class="col-md-6 col-md-offset-3">';
	foreach($terms as $term) {
		echo '<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">' . $term->name .'</h3>
				</div>
				<div class="panel-body">
					<div class="text-center">' . $term->text . '</div>
				</div>
				<div class="text-right" style="padding-right:5px; padding-bottom:5px;">
					<form class="form" action="" method="post">
						<input type="hidden" name="term_id" value="' . $term->id . '">
						<input type="submit" name="submit" value="accept" class="btn btn-primary">
					</form>
				</div>
			</div>

			<br>';
	}
	echo '</div>';
} else {
	Redirect::to('./login.php');
}