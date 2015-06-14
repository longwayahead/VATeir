<?php
$pagetitle = 'Close Notification';
require_once("../includes/header.php");

$n = new Notifications;

if(Input::exists('get')) {
	try{
		if($n->getNotification(Input::get('id'))) { //checks to see if they can access notification
			if(Input::get('close')) {
				$n->edit(array(
					'status' => 1
					), 
					[
						['id', '=', Input::get('id')]
					]);
				Session::flash('success', 'Task closed.');
			}elseif(Input::get('open')) {
				$n->edit(array(
					'status' => 0
					), 
					[
						['id', '=', Input::get('id')]
					]);
				Session::flash('success', 'Task reopened.');
			}
		}
		
		
		Redirect::to('./view.php?id='.Input::get('id'));
	} catch (Exception $e) {
		echo $e->getMessage();
	}
}