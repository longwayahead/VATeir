<?php
require_once('db.php');


$dep = $conn->prepare("SELECT x.dep as icao, (select count(callsign) from movements where dep = x.dep) as num
from movements x
right join airfields on icao = x.dep
where x.logon_time between NOW() - INTERVAL 7 DAY AND NOW() - interval 1 day
group by dep
ORDER BY x.dep asc");
$dep->execute();
$departures = $dep->fetchAll(PDO::FETCH_ASSOC);

$arr = $conn->prepare("SELECT x.arr as icao, (select count(callsign) from movements where arr = x.arr) as num
from movements x
right join airfields on icao = x.arr
where x.logon_time between NOW() - INTERVAL 7 DAY AND NOW() - interval 1 day
group by arr
ORDER BY x.arr asc");
$arr->execute();
$arrivals = $arr->fetchAll(PDO::FETCH_ASSOC);


function parse($array) {
  foreach($array as $a) {
    if($a['icao'] == 'EICK' || $a['icao'] == 'EIDW' || $a['icao'] == 'EINN') {
      $out[$a['icao']] = $a['num'];
    } else {
      if(!isset($out['Regionals'])) {
        $out['Regionals'] = 0;
      }
      $out['Regionals'] += $a['num'];
    }
  }
  ksort($out);
  return $out;
}
$departures = parse($departures);
$arrivals = parse($arrivals);
// echo '<pre>';
// print_r($arrivals);
// echo '</pre>';
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<div class="row">
  <div class="col-md-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title">Departures: Last 7 days</h3>
      </div>
      <div class="panel-body text-center">

          <canvas id="dep"></canvas>


      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title">Arrivals: Last 7 days</h3>
      </div>
      <div class="panel-body text-center">

          <canvas id="arr"></canvas>


      </div>
    </div>
  </div>
</div>
<script>
var ctx = document.getElementById("dep").getContext('2d');
var myChart = new Chart(ctx, {
  type: 'pie',
  data: {

    labels: [
      <?php
        foreach($departures as $d => $n) {
          echo '"'.$d . '", ';
        }
       ?>
    ],
    datasets: [{
      backgroundColor: [
        "#2ecc71",
        "#3498db",
        "#95a5a6",
        "#9b59b6",
        "#f1c40f",
        "#e74c3c",
        "#34495e"
      ],
      data: [
        <?php
          foreach($departures as $d => $n) {
            echo $n . ', ';
          }
         ?>
      ]
    }]
  }
});
var ctx = document.getElementById("arr").getContext('2d');
var myChart = new Chart(ctx, {
  type: 'pie',
  data: {

    labels: [
      <?php
        foreach($arrivals as $d => $n) {
          echo '"'.$d . '", ';
        }
       ?>
    ],
    datasets: [{
      backgroundColor: [
        "#2ecc71",
        "#3498db",
        "#95a5a6",
        "#9b59b6",
        "#f1c40f",
        "#e74c3c",
        "#34495e"
      ],
      data: [
        <?php
          foreach($arrivals as $d => $n) {
            echo $n . ', ';
          }
         ?>
      ]
    }]
  }
});
</script>
