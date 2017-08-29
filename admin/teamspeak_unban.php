<?php
require_once('../includes/header.php');
require_once('../teamspeak/init.php');
if(!$user->isLoggedIn() || !$user->hasAdmin("admin")) {
	Session::flash('error', 'Invalid permissions.');
	Redirect::to('../index.php');
}
$ts = new Teamspeak($tspw);
if(Input::exists('get')) {
  $select = $conn->prepare("SELECT * FROM bans WHERE cid = :cid LIMIT 1");
  $select->bindParam(':cid', Input::get('cid'));
  $select->execute();
  $bans = $select->fetchAll(PDO::FETCH_ASSOC);
  if($select->rowCount() > 0) {
    foreach($bans as $ban) {
      $ts->unban($ban['ban_id']);
    }
    $delete = $conn->prepare("UPDATE bans SET deleted = 1 WHERE cid = :cid");
    $delete->bindParam(':cid', Input::get('cid'));
    $delete->execute();
    Session::flash('success', 'User unbanned!');
    Redirect::to('./teamspeak.php');
  }

}

?>
