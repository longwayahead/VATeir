<?php
require_once('../includes/header.php');
require_once('../teamspeak/init.php');
if(!$user->isLoggedIn() || !$user->hasAdmin("admin")) {
	Session::flash('error', 'Invalid permissions.');
	Redirect::to('../index.php');
}
if(Input::exists('get')) {
  $update = $conn->prepare("DELETE FROM aliases WHERE id = :id LIMIT 1");
  $update->bindParam(':id', Input::get('cid'));
  $update->execute();
  Session::flash('success', 'Alias rejected!');
  Redirect::to('./teamspeak.php');
}

?>
