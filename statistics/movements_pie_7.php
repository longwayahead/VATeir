<?php
require_once('db.php');


$dep = $conn->prepare("SELECT x.dep as icao, (select count(callsign) from movements where dep = x.dep and date(dep_time) between date(NOW() - INTERVAL 7 DAY) AND date(NOW() - interval 1 day)) as num
from movements x
right join airfields on icao = x.dep
where date(x.dep_time) between date(NOW() - INTERVAL 7 DAY) AND date(NOW() - interval 1 day)
group by x.dep
ORDER BY x.dep asc");
$dep->execute();
$departures = $dep->fetchAll(PDO::FETCH_ASSOC);

$arr = $conn->prepare("SELECT x.arr as icao, (select count(callsign) from movements where arr = x.arr and date(arr_time) between date(NOW() - INTERVAL 7 DAY) AND date(NOW() - interval 1 day)) as num
from movements x
right join airfields on icao = x.arr
where date(x.arr_time) between date(NOW() - INTERVAL 7 DAY) AND date(NOW() - interval 1 day)
group by x.arr
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
<div class="row">
  <div class="col-md-6">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title">Departures: Last 7 days</h3>
      </div>
      <div class="panel-body text-center">
          <canvas id="dep"></canvas>
      </div>
      <?php if($user->isLoggedIn() && $user->hasAdmin('operations')) {
        ?>
        <div class="panel-footer text-right">
          <button id="btndep" class="btn btn-xs btn-link"><span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span> Save</button>
        </div>
        <?php
      }
      ?>
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
      <?php if($user->isLoggedIn() && $user->hasAdmin('operations')) {
        ?>
        <div class="panel-footer text-right">
          <button id="btnarr" class="btn btn-xs btn-link"><span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span> Save</button>
        </div>
        <?php
      }
      ?>
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
  },
  options: {
    title: {
        display: true,
        text: 'Departures last week'
    }
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
  },
  options: {
    title: {
        display: true,
        text: 'Arrivals last week'
    }
  }
});
$("#btndep").click(function() {
      $("#arr").get(0).toBlob(function(blob) {
        saveAs(blob, "VATeir_7days_departures_<?php echo date("jM", strtotime("-1 day"));?>");
    });
});
$("#btnarr").click(function() {
      $("#dep").get(0).toBlob(function(blob) {
        saveAs(blob, "VATeir_7days_arrivals_<?php echo date("jM", strtotime("-1 day"));?>");
    });
});
</script>
<?php
unset($departures);
unset($arrivals);
unset($out);
?>
