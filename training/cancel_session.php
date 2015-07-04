<?php
require_once('includes/header.php');
try {
	$session = $s->get(array(
			'id'		=> Input::get('id'),
			'future' 	=> 1
		))[0];
	if(!empty($session)) {
		if($session->student == $user->data()->id) {
			// echo '<pre>';
			// 	print_r($session);
			// echo '</pre>';
			$edit = $s->edit(['deleted' => 1], [['id', '=', Input::get('id')]]);
			Session::flash('success', 'Session cancelled.');
			Redirect::to('./index.php');
		} else {
			Session::flash('error', 'Invalid CID.');
			Redirect::to('./index.php');
		}
	} else {
		Session::flash('error', 'Cannot find session with that ID.');
		Redirect::to('./index.php');
	}
}catch(Exception $e) {
	echo $e->getMessage();
}