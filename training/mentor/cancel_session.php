<?php
require_once('../includes/header.php');
try {
	$session = $s->get([
		'id' => Input::get('id')
	])[0];
	//print_r($session);
	if(!$user->hasPermission($session->program_permissions) || !$user->hasPermission("admin")) {
		Session::flash('error', 'Cannot mentor at that level');
		Redirect::to('./');
	}
	if(!empty($session)) {
			$edit = $s->edit(['deleted' => 1], [['id', '=', Input::get('id')]]);
			Session::flash('success', 'Session cancelled.');
			Redirect::to('./index.php');
		
	} else {
		Session::flash('error', 'Cannot find session with that ID.');
		Redirect::to('./index.php');
	}
}catch(Exception $e) {
	echo $e->getMessage();
}