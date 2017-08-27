<?php
require_once('../includes/header.php');
require_once('init.php');

if(!isset($_SESSION['ts']) ) {
  Session::flash('error', 'You have to be authenticated to view that page.');
  Redirect::to('./index.php');
}
$vatsimData = $_SESSION['ts'];
if(isset($_GET['id'])) {
  try{
    //check to make sure they own a key with that row id
    $check= $conn->prepare("SELECT * FROM clients WHERE id = :id AND cid = :cid LIMIT 1");
    $check->bindParam(':id', $_GET['id']);
    $check->bindParam(':cid', $vatsimData->id);
    $check->execute();
    $results = $check->fetchAll(PDO::FETCH_ASSOC)[0];
    if(count($results) > 0) { //if that user owns the privilege key...
      $ts = new Teamspeak;
      $delete = $conn->prepare("DELETE FROM clients WHERE cid = :cid AND id = :id");
      $delete->bindParam(':cid', $vatsimData->id);
      $delete->bindParam(':id', $results['id']);
      $delete->execute();
    }

  } catch(Exception $e) {
    echo $e->getMessage();
    die();
  }
}
Session::flash('success', 'Client deleted.');
Redirect::to('./index.php');
