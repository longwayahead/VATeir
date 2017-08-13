<?php
require_once('db.php');
//////////////////////PREPARE THE QUERY//////////////////////
$sql = "SELECT date(logon_time) as days,";
$airfields = $conn->prepare("SELECT * FROM airfields ORDER BY icao ASC");
$airfields->execute();
$af = $airfields->fetchAll(PDO::FETCH_ASSOC);
foreach($af as $k => $a) {
  $sql .= "
  (
      SELECT count(*)
        from movements
        where date(dep_time) = days
          and dep_time is not null
          and dep = '" . $a['icao'] . "'
  ) as " . $a['icao'] . "_Out,
  (
      SELECT count(*)
        from movements
        where date(arr_time) = days
          and arr_time is not null
          and arr = '" . $a['icao'] . "'
  ) as " . $a['icao'] . "_In, ";
}
$sql .= "(
			SELECT count(*)
				from movements
				where date(dep_time) = days
					AND dep_time is not null
	) as Total_Out,
	(
			SELECT count(*)
				from movements
				where date(arr_time) = days
					and arr_time is not null
	) as Total_In";
    $sql .= "
from movements
group by date(days)
order by date(days) desc
limit 1, 7";


$mvts = $conn->prepare($sql);
$mvts->execute();
$results = $mvts->fetchAll(PDO::FETCH_ASSOC);
// echo '<pre>';
// print_r($results);
// echo '</pre>';
$mvts = [];
$head = [];
foreach($results as $day) {
	foreach($day as $k => $mvt) {
		if($k != 'days') {
			$head[$day['days']] = $day['days'];
			$cat = explode("_", $k);
			$mvts[$cat[0]][$cat[1]][] = $mvt;
		}
	}
}
// echo '<pre>';
// print_r($mvts);
// echo '</pre>';
$table = '<table class="table-striped table-responsive table condensed text-center">';
$table .= '<tr>
	<td></td>
	<td></td>';
	foreach($head as $h) {
		$table .= '<td><strong>' . date("D j\<\s\u\p\>S\<\/\s\u\p\> M", strtotime($h)) . '</strong></td>';
	}
	$table .= '</tr>';
	foreach($mvts as $icao => $cat) {
		$table .= '<tr>
		<td style="vertical-align: middle !important;" rowspan="2"><strong>' . $icao . '</strong></td>';

		foreach($cat as $inout => $num) { //print rows of results
			$table .= '<td ';
			if($inout == 'In') {
				$table .= 'class="active"';
			}
			$table .='><strong>' . $inout . '</strong></td>';
			foreach($num as $k => $n) {
				$table .= '<td ';
				if($inout == 'In') {
					$table .= 'class="active"';
				}
				$table .='>' . $n . '</td>';
				$keys = array_keys($num);
				if(end($keys) == $k) {
					$table .= '</tr>';
				}
			}
			if($inout == 'dep') {
				$table .= '<tr>';
			}
		}
	}
$table .= '</table>';
// echo $table;
file_put_contents("movements.html", $table);
