<?php
require_once('../includes/header.php');
		if(!$user->hasPermission('superadmin')) {
			Session::flash('error', 'Insufficient permissions.');
			Redirect::to('./view_student.php?cid=' . Input::get("cid"));
		}
if(Input::exists('get')) {
	try{
		$delete = $r->updateCard(['deleted' => 1],
			[
				['id', '=', Input::get('id')],
				['cid', '=', $user->data()->id]
			]
		);
		if($delete === true) {
			Session::flash('success', 'Card deleted!');
		} else {
			Session::flash('error', 'Unable to delete card!');
		}
	} catch(Exception $e) {
		Session::flash('error', $e->getMessage());
	}
	Redirect::to('./view_student.php?cid=' . Input::get("cid"));
}
