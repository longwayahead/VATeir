<?php
require_once('db.php');
$get = $conn->prepare("SELECT vateir_statistics.sessions.cid, vateir.controllers.first_name, vateir.controllers.last_name, vateir_statistics.sessions.position, vateir_statistics.sessions.facility,
(SELECT SEC_TO_TIME(SUM(time_to_sec(TIMEDIFF(x.finish, x.start))))  FROM vateir_statistics.sessions x WHERE x.cid = vateir_statistics.sessions.cid AND x.position = vateir_statistics.sessions.position) as duration
FROM vateir_statistics.sessions
LEFT JOIN vateir.controllers ON vateir.controllers.id = vateir_statistics.sessions.cid
GROUP BY vateir_statistics.sessions.cid, vateir_statistics.sessions.position
ORDER BY vateir_statistics.sessions.facility DESC, duration DESC
");
$get->execute();
$results = $get->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>';
// print_r($results);
// echo '</pre>';
//1-3 DEL GND TWR // 4 APP // 5-6 CTR
$stats = [];
foreach($results as $a) {
  $facility = $a['facility'];
  if($facility <= 4) {
    $stats['Aerodrome'][] = $a;
  } elseif($facility == 5) {
    $stats['Approach'][] = $a;
  } else {
    $stats['Enroute'][] = $a;
  }

}
// echo '<pre>';
// print_r($stats);
// echo '</pre>';
$output = "";
foreach($stats as $name => $s) {
  $i = 1;

  $output .= '<div class="col-md-4">
    <div class="panel panel-warning">
      <div class="panel-heading">
        <h3 class="panel-title">' . $name . '</h3>
      </div>
      <div class="panel-body">
        <table class="table table-condensed table-responsive table-striped">
          <tr>
              <td><strong>Name</strong></td>
              <td><strong>Time</strong></td>
          </tr>';
          foreach($s as $q) {
            $output .= '<tr>
              <td>' . $i . '. ' . $q['first_name'] . ' ' . $q['last_name'] . '
              </td>
              <td>
                ' . $q['duration'] .'
              </td>
            </tr>';
            if($i == 10) {
              break;
            }
            $i++;
          }

$output .= '</table>
          </div>
        </div>
        </div>';

}
file_put_contents("facility.html", $output);
?>
