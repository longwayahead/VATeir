<?php
require_once('db.php');
$get = $conn->prepare("SELECT s.cid, s.position, v.first_name, v.last_name,
(SELECT SUM(time_to_sec(TIMEDIFF(finish, start))) FROM sessions WHERE cid = s.cid AND position = s.position) as duration
FROM sessions s
LEFT JOIN vateir.controllers v ON v.id = s.cid
WHERE YEAR(CURRENT_DATE()) = YEAR(s.finish)
	AND MONTH(CURRENT_DATE()) = MONTH(CURRENT_DATE)
GROUP BY s.position, s.cid
ORDER BY  s.facility DESC, v.last_name DESC
");
$get->execute();
$results = $get->fetchAll(PDO::FETCH_ASSOC);
$stats = [];
foreach($results as $r) {
  $name = $r['first_name'] . ' ' . $r['last_name'];
  $stats['cid'][$name][$r['position']] = $r['duration'];
  if(isset($stats['total'][$r['position']]) == false) {
  $stats['total'][$r['position']] = '0';
  }
  $stats['total'][$r['position']] .= $r['duration'];
}

$out = [];
foreach($stats['total'] as $pos => $time) {
  $out[0][] = $pos;
  $out[1][] = $time;
}
foreach($stats['cid'] as $cid => $q) {
  foreach($q as $p => $t) {
    $out['cid'][$cid][array_search($p, $out[0])] = $t;
  }
}
function timeLength($sec)
{
    $s=$sec % 60;
    $m=(($sec-$s) / 60) % 60;
    $h=floor($sec / 3600);
    return $h.":".substr("0".$m,-2).":".substr("0".$s,-2);
}


$output = '<div class="panel panel-warning">
  <div class="panel-heading">
    <h3 class="panel-title">' . date("F") . '\'s Controlling Hours</h3>
  </div>
<div class="panel-body">
    <table class="table table-responsive table-condensed">
      <tr>
    ';
    //headers
$output .= '<tr>';
$output .= '<td></td>';
foreach($out[0] as $key => $v) {

  $output .= '<td>' . $v . '</td>';
}
$output .= '</tr>';
end($out[0]);
$total = key($out[0]);

//cids
foreach($out['cid'] as $cid => $z) {
  $output .= '<tr>';
  $output .= '<td style="white-space: nowrap;">' . $cid . '</td>';
  for($i = 0; $i <= $total; $i++) {
    $output .= '<td>';
      if(array_key_exists($i, $z)) {
        $output .= timeLength($z[$i]);
      } else {
        $output .= '';
      }
    $output .= '</td>';
  }
  $output .= '</tr>';
}

$output .= '
    </table>
  </div>
</div>';
file_put_contents("total.html", $output);
?>
