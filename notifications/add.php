<?php
$pagetitle = 'Add Notification';
require_once("../includes/header.php");
$n = new Notifications;
if($n->getNotification(Input::get('id'), $user->data()->id)) {
	if(isset($_POST['text'])) {
		$add = $n->addComment([
			'notification_id' => Input::get('id'),
			'submitted'		=>	date("Y-m-d H:i:s"),
			'submitted_by'	=> $user->data()->id,
			'text'	=> Input::get('text')
		]);
		Session::flash('success', 'Comment added.');

	} else {
		Session::flash('error', 'No text entered.');
	}

} else {
	Session::flash('error', 'Comment could not be added.');
}

Redirect::to('./view.php?id=' . Input::get('id'));
