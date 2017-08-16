<?php
require_once('db.php');


$m = $conn->prepare("SELECT date(m.logon_time) as date,
(SELECT SUM(time_to_sec(TIMEDIFF(finish, start))) FROM sessions WHERE date(start) = date(m.logon_time)) as atcseconds,
(

    	SELECT count(a.callsign)FROM movements a
                         RIGHT JOIN airfields c on c.icao = a.dep
       					 where date(a.dep_time) = date(m.logon_time)

) as dep,
(

    	SELECT count(b.callsign) FROM movements b
                         RIGHT JOIN airfields d on d.icao = b.arr
       					 where date(b.arr_time) = date(m.logon_time)

) as arr
from movements m
where date(m.logon_time) between date(now() - interval 7 day) and date(now() - interval 1 day)
group by date
");
$m->execute();
$am =  $m->fetchAll(PDO::FETCH_ASSOC);

$colours = [
  "EICK" => "#2ecc71", //cork
  "EIDW" => "#3498db", //dublin
  "EINN" => "#95a5a6", //shannon
  "Regionals" => "#9b59b6", //regionals
  "Total" =>"#e74c3c",
];
$dates = [];
foreach($am as $a) {
  if(!in_array($a['date'], $dates)) {
    $dates[] = $a['date'];
  }
  $ou[$a['date']]['atc'] = $a['atcseconds'];
  $ou[$a['date']]['tfc'] = $a['dep'] + $a['arr'];
}
?>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-warning">
      <div class="panel-heading">
        <h3 class="panel-title">ATC vs Movements</h3>
      </div>
      <div class="panel-body text-center">
          <canvas id="atcmv"></canvas>
          <br/><br/>

      </div>
      <?php if($user->isLoggedIn() && $user->hasAdmin('operations')) {
        ?>
        <div class="panel-footer text-right">
          <button id="atcmvbtn" class="btn btn-xs btn-link"><span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span> Save</button>
        </div>
        <?php
      }
      ?>

    </div>
  </div>
</div>

<script>

  var ctx = document.getElementById('atcmv').getContext('2d');
  var myChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: [
      <?php
      foreach($dates as $date) {
        echo '"' . date("D, j M", strtotime($date)) . '", ';
      }
      ?>
      ],
    datasets: [
      {
        label:"ATC",
        data: [
          <?php foreach($ou as $o)  {
            echo round($o['atc']/60/60,2) . ',';
          }
          ?>
        ],
        fill:false,
        yAxisID: "y-axis-0",
        "lineTension":0.1,
        borderColor: "#f1c40f"
      },
      {
        label:"Traffic",
        data: [
          <?php foreach($ou as $o)  {
            echo $o['tfc'] . ',';
          }
          ?>
        ],
        fill:false,
        yAxisID: "y-axis-1",
        "lineTension":0.1,
        borderColor: "#9b59b6"
      }
    ]

  },
  "options":{
    title: {
        display: true,
        text: 'ATC Hours vs Movements'
    },
    "responsive": true,
    "scaleBeginAtZero": true,
    tooltips: {
						position: 'average',
						mode: 'index',
						intersect: false,
					},
    scales: {
      yAxes: [{
        position: "left",
        "id": "y-axis-0",
        scaleLabel: {
          display: true,
          labelString: 'Hours'
        }
      }, {
        position: "right",
        "id": "y-axis-1",
        scaleLabel: {
          display: true,
          labelString: 'Movements'
        }
      }]
    }
  }
  });
  $("#atcmvbtn").click(function() {
   	    $("#atcmv").get(0).toBlob(function(blob) {
      		saveAs(blob, "VATeir_atcmovements_7days_<?php echo date("jM");?>");
  		});
  });
</script>
