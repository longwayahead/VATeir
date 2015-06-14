<?php
require_once('../includes/header.php');
$a = new Availability;
if(Input::exists('get')) {
	try{
		$delete = $a->edit(['deleted' => 1],
			[
				['id', '=', Input::get('id')],
				['cid', '=', $user->data()->id]
			]
		);
		if($delete === true) {
			Session::flash('success', 'Availability deleted.');
		} else {
			Session::flash('error', 'Unable to delete availability.');
		}
	} catch(Exception $e) {
		Session::flash('error', $e->getMessage());
	}
	Redirect::to('./availability.php');
}