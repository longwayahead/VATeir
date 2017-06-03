<?php
require_once('../includes/header.php');
$data = Input::get('data');
$cid = key($data);
$newPerm = $data[$cid];
if(!$user->hasAdmin("admin")) {
	Session::flash('error', 'Invalid permissions.');
	Redirect::to('../controllers/profile.php?id=' . $cid);
}

if(Input::exists('post')) {
  try{
  	$update = $user->update(
  			[
  				'adminPerm' => $newPerm
  			],
  			[
  				['id', '=', $cid]
  			]
  		);
    }catch(Exception $e) {
      echo $e->getMessage();
    }
    if($update) {
      Session::flash('success', 'Permissions changed.');
      Redirect::to('../controllers/profile.php?id=' . $cid);
    } else {
      Session::flash('error', 'There was a problem updating the permissions.');
    	Redirect::to('../controllers/profile.php?id=' . $cid);
    }
} else {

}
