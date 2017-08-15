<?php
require_once('db.php');


$dep = $conn->prepare("SELECT * FROM (SELECT date(x.dep_time) as day , x.dep as icao, (SELECT count(callsign) FROM movements where dep = x.dep and date(dep_time) = date(x.dep_time)) as num
from movements x
right join airfields on icao = x.dep
where x.dep_time between DATE(NOW() - INTERVAL 7 DAY) AND DATE(NOW() - interval 1 day)
group by day, x.dep
order by x.dep_time asc) as a
UNION ALL
SELECT * FROM (SELECT date(x.arr_time) as day , x.arr as icao, (SELECT count(callsign) FROM movements where arr = x.arr and date(arr_time) = date(x.arr_time)) as num
from movements x
right join airfields on icao = x.arr
where x.arr_time between DATE(NOW() - INTERVAL 7 DAY) AND now() AND date(x.arr_time) <> current_date()
group by day, x.arr
order by x.arr_time asc) as b
");
$dep->execute();
$mvts =  $dep->fetchAll(PDO::FETCH_ASSOC);

$days = [];
foreach($mvts as $a) {
  if(!in_array($a['day'], $days)) {
    $days[] = $a['day'];
  }

  // total movements for an day
  if(!isset($out['Total'][$a['day']])) {
    $out['Total'][$a['day']] = 0;
  }
  $out['Total'][$a['day']] += $a['num'];

  //
  if($a['icao'] == 'EICK' || $a['icao'] == 'EIDW' || $a['icao'] == 'EINN') {
    if(!isset($out[$a['icao']][$a['day']])) {
      $out[$a['icao']][$a['day']] = 0;
    }

    $out[$a['icao']][$a['day']] += $a['num'];

  } else {
    if(!isset($out['Regionals'][$a['day']])) {
      $out['Regionals'][$a['day']] = 0;
    }
    $out['Regionals'][$a['day']] += $a['num'];
  }
}
// echo '<pre>';
// print_r($out);
// echo '</pre>';


$colours = [
  "EICK" => "#2ecc71", //cork
  "EIDW" => "#3498db", //dublin
  "EINN" => "#95a5a6", //shannon
  "Regionals" => "#9b59b6", //regionals
  "Total" =>"#f1c40f",
];
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title">Network Movements: Last 7 days</h3>
      </div>
      <div class="panel-body text-center">

          <canvas id="line"></canvas>
      </div>
    </div>
  </div>
</div>

<script>
  var ctx = document.getElementById('line').getContext('2d');
  var myChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: [
      <?php
      foreach($days as $day) {
        echo '"' . date("D,j M", strtotime($day)) . '", ';
      }
      ?>
      ],
    datasets: [
      <?php
      foreach($out as $icao => $d) {
        echo '{label:"'.$icao.'",
          data: [';
        foreach($days as $day) {
          if(isset($d[$day])) {
            echo $d[$day] . ',';
          } else {
            echo 0 . ',';
          }
        }
          echo '],
          fill:false,
          "lineTension":0.1,
          borderColor: "'. $colours[$icao] .'"';
      echo '},';
      }
      ?>
      ]
  },
  "options":{
    "responsive": true,
    "scaleBeginAtZero": true
  }
  });
</script>
