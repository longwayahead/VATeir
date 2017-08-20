<?php
if(isset($_GET['id'])) {
  require_once('db.php');
  require_once('functions/functions.php');
  $get = $conn->prepare("SELECT SUM(time_to_sec(TIMEDIFF(finish, start))) as total_time FROM sessions
  WHERE cid = :cid
  AND date(finish) between date(now() - interval 1 month) and date(now())
  ");
  $get->bindParam(':cid', $_GET['id']);
  $get->execute();
  $results = $get->fetchAll(PDO::FETCH_ASSOC);
  echo timeLength($results[0]['total_time']);
}
