<?php
$pagetitle = "Delete a T&C";
require_once("includes/header.php");
$user->updateTerm(
		[
			'deleted' => 1
		]
		,
		[
			['id', '=', Input::get('id')]
		]
	);
Session::flash('success', 'T&C deleted successfully.');
Redirect::to('terms.php');