<?php
$file = file_get_contents('datafile.txt');
function getDistance( $latitude1, $longitude1, $latitude2, $longitude2 ) {
    $earth_radius = 6371;

    $dLat = deg2rad( $latitude2 - $latitude1 );
    $dLon = deg2rad( $longitude2 - $longitude1 );

    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
    $c = 2 * asin(sqrt($a));
    $d = $earth_radius * $c;

    return $d;
}
////////////////GET IRISH FLIGHTS//////////////////////
preg_match_all("/([\w-]+):(\d+):.*?:PILOT::.*?(\-?\d+\.\d+):(\-?\d+\.\d+):\d+:(\d+):.*?:\d+:(\w{4}):\d+:(\w{4}).*(\d{14}):\d+:\d+\.\d+:\d+:/",$file , $result);

$eisn = [];
foreach($result[1] as $k => $cs) {
  $cid = $result[2][$k];
  $aclat = $result[3][$k];
  $aclon = $result[4][$k];
  $spd = $result[5][$k];
  $dep = $result[6][$k];
  $arr = $result[7][$k];
  $on = $result[8][$k];
  if(substr($dep, 0, 2) == 'EI' || substr($arr, 0, 2) == 'EI') {
    $eisn[] = [$cs, $cid, $aclat, $aclon, $spd, $dep, $arr, $on];
  }
}
// print_r($eisn);
$coordinates = [
  'EIDW' => [53.421389, -6.27],
  'EICK' => [51.841389, -8.491111],
  'EINN' => [52.701978, -8.924817000000001],
  'EIKY' => [52.180833, -9.523889],
  'EIWT' => [53.352292, -6.488311],
  'EIDL' => [55.044167, -8.341111],
  'EIKN' => [53.910278, -8.818611],
  'EIWF' => [52.187222, -7.086944],
  'EISG' => [54.280278, -8.599167],
  'EICM' => [53.300278, -8.941667],
  'EIBN' => [51.6777, -9.4870],

];

require_once('db.php');
//////////////////////PREPARE THE QUERY//////////////////////
$check = $conn->prepare("SELECT * from movements WHERE callsign = :callsign AND cid = :cid AND dep = :dep AND arr = :arr AND (dep_time > date_sub(now(), interval 30 minute) OR arr_time > date_sub(now(), interval 30 minute)) ORDER BY logon_time DESC LIMIT 1");
$correct = $conn->prepare("UPDATE movements SET logon_time = :logon_time WHERE :callsign = :callsign AND cid = :cid AND dep = :dep AND arr = :arr ORDER BY logon_time DESC LIMIT 1");
$update = $conn->prepare("INSERT INTO movements (callsign, cid, logon_time, dep, arr, dep_time, arr_time)
VALUES (:callsign, :cid, :logon_time, :dep, :arr, :dep_time, :arr_time)
  ON DUPLICATE KEY UPDATE
    arr_time = IF(VALUES(arr_time) IS NOT NULL AND arr_time IS NULL, VALUES(arr_time), arr_time),
    dep_time = IF(VALUES(dep_time) IS NOT NULL, VALUES(dep_time), dep_time);
");
// $departure = $conn->prepare("INSERT INTO departures (callsign, cid, logon_time, dep, arr, stamp)
// VALUES (:callsign, :cid, :logon_time, :dep, :arr, :stamp)
//   ON DUPLICATE KEY UPDATE
//     stamp = VALUES(stamp);
// ");
// $arrival = $conn->prepare("INSERT INTO arrivals (callsign, cid, logon_time, dep, arr, stamp)
// VALUES (:callsign, :cid, :logon_time, :dep, :arr, :stamp)
//   ON DUPLICATE KEY UPDATE
//     stamp = VALUES(stamp);
// ");

foreach($eisn as $d) {
  $time = $d[7];
  $dt = new DateTime($time, new DateTimezone('GMT'));
  $IST = new DateTimeZone('Europe/Dublin');
  $dt->setTimezone($IST);
  $logon = $dt->format("Y-m-d H:i:s");

///

  $check->bindParam(":callsign", $d[0]);
  $check->bindParam(":cid", $d[1]);
  $check->bindParam(":dep", $d[5]);
  $check->bindParam(":arr", $d[6]);
  $check->execute();
  $results = $check->fetchAll(PDO::FETCH_ASSOC);
  // echo '<pre>';
  // print_r($results);
  // echo '</pre>';
  // echo $logon;
  // echo ' ' .$results[0]['logon_time'];
  // echo ' <b> ' . (strtotime($logon) - strtotime($results[0]['logon_time'])) . '</b>';
  //This if statement checks to make sure the aircraft hasn't logged off and back on again in the last 30 minutes
  if(count($results) != 0 && (strtotime($logon) - strtotime($results[0]['logon_time']))>0 && (strtotime($logon) - strtotime($results[0]['logon_time'])) < 1800) { //if record is separated by amount in seconds
    $correct->bindParam(":logon_time", $logon);
    $correct->bindParam(":callsign", $d[0]);
    $correct->bindParam(":cid", $d[1]);
    $correct->bindParam(":dep", $d[5]);
    $correct->bindParam(":arr", $d[6]);
    $correct->execute();
    //echo 'hi';
  }
  $update->bindParam(":callsign", $d[0]);
  $update->bindParam(":cid", $d[1]);
  $update->bindParam(":logon_time", $logon);
  $update->bindParam(":dep", $d[5]);
  $update->bindParam(":arr", $d[6]);
  if($d[4] < 20 && substr($d[5], 0, 2) == 'EI' && getDistance($d[2], $d[3], $coordinates[$d[5]][0], $coordinates[$d[5]][1]) < 2) { //departing
    $update->bindParam(":arr_time", $a=null);
    $update->bindParam(":dep_time", date("Y-m-d H:i:s"));
    $update->execute();


  } elseif($d[4] < 20 && substr($d[6], 0, 2) == 'EI' && getDistance($d[2], $d[3], $coordinates[$d[6]][0], $coordinates[$d[6]][1]) < 2) { //arriving
    $update->bindParam(":dep_time", $a=null);
    $update->bindParam(":arr_time", date("Y-m-d H:i:s"));
    $update->execute();

  }




}
