<?php
require_once("../includes/header.php");
if($user->isLoggedIn()) {
	$user->logout();
	Session::flash('info', 'You were successfully logged out!');
	Redirect::to(BASE_URL . 'index.php');
} else {
	Session::flash('error', 'You can\'t access that without being signed in!');
	Redirect::to(BASE_URL . 'index.php');
}