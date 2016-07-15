<?php
require_once('../includes/header.php');
if(!$user->hasPermission("mentor")) { //this bit needs looking at...
  Session::flash('error', 'Insufficient permissions.');
  Redirect::to('./');
}
try {
	$session = $s->get([
		'id' => Input::get('id')
	])[0];

  if(!empty($session)) {
    $r->addCard(array(
      'cid'		=> $session->student,
      'card_type'	=> 2,
      'link_id'	=> Input::get('id'),
      'submitted'	=> date('Y-m-d H:i:s')
    ));
    $r->addInfo(array(
      'session_id'		=> Input::get('id'),
      'card_id'	=> 7
    ));
    $s->edit(['deleted' => 1], [['id', '=', Input::get('id')]]);
    Session::flash('success', 'No-Show Report Added!');
    Redirect::to('./index.php');
    die();
  } else {
		Session::flash('error', 'Cannot find session with that ID.');
		Redirect::to('./index.php');
	}
}catch(Exception $e) {
	echo $e->getMessage();
}
