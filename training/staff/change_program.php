<?php
require_once('../includes/header.php');
$data = Input::get('data');
$cid = key($data);
$program = $data[$cid];
if(!$user->hasPermission("admin")) {
	Session::flash('error', 'Invalid permissions.');
	Redirect::to('../mentor/view_student.php?cid=' . $cid);
}

if(Input::exists('post')) {
	$update = $t->updateStudent(
			[
				'program' => $program
			],
			[
				['cid', '=', $cid]
			]
		);
	Session::flash('success', 'Program changed.');
	Redirect::to('../mentor/view_student.php?cid=' . $cid);
} else {
	Session::flash('error', 'No data passed to the form.');
	Redirect::to('../mentor/view_student.php?cid=' . $cid);
}

