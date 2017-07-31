<?php
$localfilename = "datafile.txt";
$remoteurls = [
  'http://info.vroute.net/vatsim-data.txt',
  'http://data.vattastic.com/vatsim-data.txt',
  'http://vatsim.aircharts.org/vatsim-data.txt',
  'http://vatsim-data.hardern.net/vatsim-data.txt',
  'http://wazzup.flightoperationssystem.com/vatsim/vatsim-data.txt',
];
$randomurl = $remoteurls[array_rand($remoteurls)];
////////////Cache the datafile/////////////////////
if (file_exists($localfilename) && (filemtime($localfilename) > (time() - 60 * 5))) {
   // Cache file is less than five minutes old.
   // Don't bother refreshing, just use the file as-is.
   $file = file_get_contents($localfilename);
} else {
   // Our cache is out-of-date, so load the data from our remote server,
   // and also save it over our cache for next time.
   $file = file_get_contents($randomurl);
   file_put_contents($localfilename, $file, LOCK_EX);
}

////////////////GET IRISH POSITIONS ONLINE//////////////////////
preg_match_all("/(EI\w{2,}_(?:[\w\d]_)?(?:CTR|APP|TWR|GND|DEL)):(\d+):.*?:ATC:(?=.*:([1-9])::([\d+]):)(?=.*:(\d+)::::)/",$file , $result);
////////////////DATABASE STUFF//////////////////////
require_once('dbpw.php');
$conn = new PDO("mysql:host=localhost;dbname=vateir_statistics", 'vateir', $password);
// set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);#
//////////////////////PREPARE THE QUERIES//////////////////////
//Insert query
$insert = $conn->prepare("INSERT INTO sessions (cid, rating, start, finish, position, facility)
VALUES (:cid, :rating, :start, :finish, :position, :facility)");
//check to see whether session already exists in database
$check = $conn->prepare("SELECT 1 FROM sessions WHERE cid = :cid AND start = :start AND position = :position");
//update
$update = $conn->prepare("UPDATE sessions SET finish = :finish WHERE cid = :cid AND start = :start AND position = :position");
//////////////////////TIME TO LOOP THROUGH THE POSITIONS ONLINE//////////////////////
foreach($result[1] as $i => $atc) {
  if(strpos($atc, 'ATIS') == false && strpos($atc, 'OBS') == false) { //Make sure only actual ATC positions are recorded -> backup in case REGEX above fails
    //////////////////////MAKES THINGS EASIER TO WORK WITH//////////////////////
    $position = $atc;
    $cid = $result[2][$i];
    $rating = $result[3][$i];
    $dt = new DateTime($result[5][$i]);
    $start = $dt->format("Y-m-d H:i:s");
    $facility = $result[4][$i];
    $finish = new DateTime();
    $finish = $finish->format("Y-m-d H:i:s");
    /////////////////////////////////////////////////////////////////////////////
    $check->bindParam(":cid",$cid);
    $check->bindParam(":start", $start);
    $check->bindParam(":position", $position);
    $check->execute();
    $fetch = $check->fetch();
    if($fetch) { //if a record already exists update it
      $update->bindParam(":finish", $finish);
      $update->bindParam(":cid", $cid);
      $update->bindParam(":start", $start);
      $update->bindParam(":position", $position);
      $update->execute();
    } else { //if a record doesn't exist make one
      $insert->bindParam(":cid", $cid);
      $insert->bindParam(":rating", $rating);
      $insert->bindParam(":start", $start);
      $insert->bindParam(":finish", $finish);
      $insert->bindParam(":position", $position);
      $insert->bindParam(":facility", $facility);
      $insert->execute();
    }
  }
}
$conn = null;
////////////////LOG WHICH SERVER WAS USED//////////////////////
$log_url = '['. $finish .'] ' . $randomurl . "\r\n";
file_put_contents("server_log.txt", $log_url, FILE_APPEND);
