<?php
require_once('../includes/header.php');
require_once('init.php');

if(!isset($_SESSION['ts']) ) {
  header('Location: index.php');
}
$vatsimData = $_SESSION['ts'];
if(isset($_GET['id'])) {
  try{
    //check to make sure they own a key with that row id
    $check= $conn->prepare("SELECT * FROM priv_keys WHERE id = :id AND cid = :cid LIMIT 1");
    $check->bindParam(':id', $_GET['id']);
    $check->bindParam(':cid', $vatsimData->id);
    $check->execute();
    $results = $check->fetchAll(PDO::FETCH_ASSOC)[0];
    if(count($results) > 0) { //if that user owns the privilege key...
      $ts = new Teamspeak($tspw);
      $key = $ts->deleteKey($results['token']);
      if($key == true) {
        $delete = $conn->prepare("DELETE FROM priv_keys WHERE cid = :cid AND token = :token");
        $delete->bindParam(':cid', $vatsimData->id);
        $delete->bindParam(':token', $results['token']);
        $delete->execute();
      }

    }

  } catch(Exception $e) {
    echo $e->getMessage();
    die();
  }
}
header('Location: index.php');
