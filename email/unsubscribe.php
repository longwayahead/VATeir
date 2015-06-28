<?php
require_once('../includes/header.php');

try {
	if($user->checkDupeEmail(Input::get('e'))== false) {
		$unsubscribe = $user->unsubscribeEmail([
			'email' => Input::get('e')
		]);
	}
	
	Session::flash('success', 'You have been unsubscribed.');
	Redirect::to('../index.php');
} catch(Exception $e) {
	echo $e->getMessage();
}