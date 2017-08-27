<?php
require_once('../includes/header.php');
require_once('init.php');

if(!isset($_SESSION['ts']) ) {
  header('Location: index.php');
}
$vatsimData = $_SESSION['ts'];

try{
  $ts = new Teamspeak;
  $key = $ts->makeKey(1032602);
  echo '<pre>';
  print_r($key);
  echo '</pre>';

  $insert = $conn->prepare("INSERT INTO priv_keys (cid, token, registered) VALUES (:cid, :token, NOW())");
  $insert->bindParam(':cid', $vatsimData->id);
  $insert->bindParam(':token', $key);
  $insert->execute();
} catch(Exception $e) {
  echo $e->getMessage();
  die();
}
header('Location: index.php');
