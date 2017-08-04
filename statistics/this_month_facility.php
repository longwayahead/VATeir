<?php
require_once('db.php');
$get = $conn->prepare("SELECT vateir_statistics.sessions.cid, vateir.controllers.first_name, vateir.controllers.last_name, vateir_statistics.sessions.position, vateir_statistics.sessions.facility,
(SELECT SEC_TO_TIME(SUM(time_to_sec(TIMEDIFF(x.finish, x.start))))
FROM vateir_statistics.sessions x
  WHERE x.cid = vateir_statistics.sessions.cid
  AND x.position = vateir_statistics.sessions.position
  AND YEAR(CURRENT_DATE) = YEAR(x.finish)
  AND MONTH(CURRENT_DATE) = MONTH(x.finish)) as duration
FROM vateir_statistics.sessions
RIGHT JOIN vateir.controllers ON vateir.controllers.id = vateir_statistics.sessions.cid
WHERE YEAR(CURRENT_DATE) = YEAR(vateir_statistics.sessions.finish)
  AND MONTH(CURRENT_DATE) = MONTH(vateir_statistics.sessions.finish)
GROUP BY vateir_statistics.sessions.cid, vateir_statistics.sessions.position
ORDER BY vateir_statistics.sessions.facility DESC, duration DESC
");
$get->execute();
$results = $get->fetchAll(PDO::FETCH_ASSOC);
$tot = $conn->prepare("CALL thismonth_tott()");
$tot->execute();
$tott= $tot->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>';
// print_r($tott);
// echo '</pre>';
//1-3 DEL GND TWR // 4 APP // 5-6 CTR
$stats = [];
$del = 0;
$gnd = 0;
$twr = 0;
$app = 0;
$twr = 0;
$ctr = 0;
foreach($results as $a) {
  $facility = $a['facility'];
    if($facility == 2 && $del <= 10) {
      $stats['Delivery'][] = $a;
      $del++;
    } elseif($facility == 3 && $gnd <= 10) {
      $stats['Ground'][] = $a;
      $gnd++;
    } elseif($facility == 4 && $twr <= 10) {
      $stats['Tower'][] = $a;
      $twr++;
    } elseif($facility == 5 && $app <= 10) {
      $stats['Approach'][] = $a;
      $app++;
    } elseif($facility == 6 && $ctr <= 10) {
      $stats['Enroute'][] = $a;
      $ctr++;
    }
}
$stats['Top of the top'] = $tott;
$output = "";
$boxes = 0;
foreach($stats as $name => $s) {
  $i = 1;
  $boxes++;
  if($boxes == 1 || $boxes == 4) {
    $output .= '<div class="row">';
  }
  $output .= '<div class="col-md-4">
    <div class="panel panel-warning">
      <div class="panel-heading">
        <h3 class="panel-title">' . $name . '</h3>
      </div>
      <div class="panel-body">
        <table class="table table-condensed table-responsive table-striped" style="margin-bottom:0px;">
          <tr>
              <td><strong>Name</strong></td>
              <td><strong>Time</strong></td>
          </tr>';
          foreach($s as $q) {
            $output .= '<tr>
              <td>';
               $output .= ($i == 1) ? '<span class="glyphicon glyphicon-star" aria-hidden="true"></span> ' : $i . '. ';
               $output .= '<a href="#' . $q['cid'] . '">' . $q['first_name'] . ' ' . $q['last_name'] . '</a>
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
        if($boxes == 3 || $boxes == 6) {
          $output .= '</div>';
        }
}
// $output;
file_put_contents("facility.html", $output);
?>
