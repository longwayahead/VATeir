<?php
require_once("../includes/header.php");
if(!$user->hasPermission('tdstaff')) {
	Session::flash('error', 'Invalid permissions');
	Redirect::to(BASE_URL . 'training');
}
try{
	$id = Input::get('id');
	$r = new Reports;
	$r->updateS([
		'deleted' => 1
	],
	[['id', '=', $id]]);
}catch(Exception $e) {
	echo $e->getMessage();
}
Session::flash('success', 'Slider deleted');
Redirect::to('./sliders.php');
?>