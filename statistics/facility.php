<?php
require_once('db.php');
$get = $conn->prepare("SELECT vateir_statistics.sessions.cid, vateir.controllers.first_name, vateir.controllers.last_name, vateir_statistics.sessions.position, vateir_statistics.sessions.facility,
(SELECT SEC_TO_TIME(SUM(time_to_sec(TIMEDIFF(x.finish, x.start))))
FROM vateir_statistics.sessions x
  WHERE x.cid = vateir_statistics.sessions.cid
  AND x.facility = vateir_statistics.sessions.facility
  AND YEAR(CURRENT_DATE) = YEAR(x.finish)
  AND MONTH(CURRENT_DATE) = MONTH(x.finish)) as duration
FROM vateir_statistics.sessions
RIGHT JOIN vateir.controllers ON vateir.controllers.id = vateir_statistics.sessions.cid
WHERE YEAR(CURRENT_DATE) = YEAR(vateir_statistics.sessions.finish)
  AND MONTH(CURRENT_DATE) = MONTH(vateir_statistics.sessions.finish)
GROUP BY vateir_statistics.sessions.cid, vateir_statistics.sessions.facility
ORDER BY vateir_statistics.sessions.facility DESC, duration DESC
");
$get->execute();
$results = $get->fetchAll(PDO::FETCH_ASSOC);
$tot = $conn->prepare("SELECT x.cid, v.first_name, v.last_name,
         SEC_TO_TIME(SUM(TIME_TO_SEC(timediff(x.finish, x.start)))) as duration
    FROM vateir_statistics.sessions x
    RIGHT JOIN vateir.controllers v ON v.id = x.cid
    WHERE YEAR(x.finish) = YEAR(CURRENT_DATE) AND MONTH(x.finish) = MONTH(CURRENT_DATE)
GROUP BY x.cid
ORDER BY duration DESC
LIMIT 10");
$tot->execute();
$tott= $tot->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>';
// print_r($results);
// echo '</pre>';
//1-3 DEL GND TWR // 4 APP // 5-6 CTR
$stats = [];
$del = 0;
$gnd = 0;
$twr = 0;
$app = 0;
$twr = 0;
$ctr = 0;
$stats['Top of the tops'] = $tott;
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

$output = "";
$boxes = 0;
foreach($stats as $name => $s) {
  $i = 1;
  $boxes++;
  $trip = 0;
  if($boxes == 1 || $boxes == 4) {
    $output .= '<div class="row">';
    $trip = 1;
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
               if($q['cid'] == 	861497 && $boxes == 1 && $i == 1) {
                 $output .= '<a href="profile.php?id=' . $q['cid'] . '" style="cursor: crosshair;" data-toggle="tooltip" title="Winner winner chicken dinner">' . $q['first_name'] . ' ' . $q['last_name'] . '</a>';
               } else {
                 $output .= '<a href="profile.php?id=' . $q['cid'] . '" style="cursor: pointer;">' . $q['first_name'] . ' ' . $q['last_name'] . '</a>';
               }
               $output .= '
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
        if($boxes == 3  || $boxes == 6) {
          $output .= '</div>';
        }
}
//echo $output;
file_put_contents("facility.html", $output);
?>
