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
//if (file_exists($localfilename) && (filemtime($localfilename) > (time() - 60 * 3))) {
   // Cache file is less than five minutes old.
   // Don't bother refreshing, just use the file as-is.
   //$file = file_get_contents($localfilename);
//} else {
   // Our cache is out-of-date, so load the data from our remote server,
   // and also save it over our cache for next time.
   $file = file_get_contents($randomurl);
   file_put_contents($localfilename, $file, LOCK_EX);
//}
////////////////GET IRISH POSITIONS ONLINE//////////////////////
preg_match_all("/(EI(?:\w{2})_(?:[\w\d]{1,2})?(?:CTR|APP|TWR|GND|DEL)):(\d+):.*?:ATC:(?!199\.998)(?=.*:([1-9])::([\d+]):)(?=.*:(\d+)::::)/",$file , $result);
require_once('db.php');
//////////////////////PREPARE THE QUERY//////////////////////
$insert = $conn->prepare("INSERT INTO sessions (cid, rating, start, finish, position, facility)
VALUES (:cid, :rating, :start, TIMESTAMP(NOW()), :position, :facility)
  ON DUPLICATE KEY UPDATE
    finish = VALUES(finish);
");
//////////////////////TIME TO LOOP THROUGH THE POSITIONS ONLINE//////////////////////
foreach($result[1] as $i => $atc) {
    $time = $result[5][$i];
    $dt = new DateTime($time, new DateTimezone('GMT'));
    $IST = new DateTimeZone('Europe/Dublin');
    $dt->setTimezone($IST);
    $start = $dt->format("Y-m-d H:i:s");
    /////////////////////////////////////////////////////////////////////////////
    $insert->bindParam(":cid", $result[2][$i]);
    $insert->bindParam(":rating", $result[3][$i]);
    $insert->bindParam(":start", $start);
    $insert->bindParam(":position", $atc);
    $insert->bindParam(":facility", $result[4][$i]);
    $insert->execute();
}
