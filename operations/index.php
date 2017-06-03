<?php
$pagetitle = "Operations";
require_once('includes/header.php');
$token = md5(time());
$_SESSION['atkn'] = $token;
?>

<h3 class="text-center">Operations Home</h3> <br>
<div class="row">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Availabilities Data</h3>
      </div>
      <div class="panel-body">
        <canvas id="availabilities" style="padding-left:-20px; padding-right:20px;"></canvas>

      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Session totals</h3>
      </div>
      <div class="panel-body">
        <canvas id="canvas" style="padding-left:-20px; padding-right:20px;"></canvas>

      </div>
      <div class="panel-footer text-right">
        <form method="post" action="stats.php">
        <input type="hidden" name="auth_token" value="<?php echo $token; ?>">
        <button type="submit" class="btn btn-xs btn-link"><i class="glyphicon glyphicon-arrow-down" aria-hidden="true"></i> Download</button>
      </form>
    </div>
  </div>

</div>




</div>
<?php
	$g = new Graph;
	$pop = $g->sa();
?>

<script>
var availabilityData = {
  labels : [
    <?php foreach($pop as $month => $result) { echo '"' . $month . '", ';}?>
  ],
  datasets : [
    {
      label: "Availabilities",
      fillColor: "rgba(151,205,187,0.2)",
      strokeColor: "rgba(151,205,187,1)",
      pointColor: "rgba(151,205,187,1)",
      pointStrokeColor: "#fff",
      pointHighlightFill: "#fff",
      pointHighlightStroke: "rgba(151,205,187,1)",
      data : [<?php foreach($pop as $month => $result) {echo ($result['availability'] == false) ? '0' : $result['availability']; echo ',';}?>]
    }
  ]
}
	var lineChartData = {
			labels : [<?php foreach($pop as $month => $result) { echo '"' . $month . '", ';}?>],
			datasets : [
				{
					label: "Sessions",
					fillColor : "rgba(151,187,205,0.2)",
					strokeColor : "rgba(151,187,205,1)",
					pointColor : "rgba(151,187,205,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(151,187,205,1)",

					data : [<?php foreach($pop as $month => $result) { echo ($result['sessions'] == false) ? '0' : $result['sessions']; echo ',';}?>]
				},
				{
					label: "No Show",
					fillColor: "rgba(247,70,74,0.2)",
	        strokeColor: "rgba(247,70,74,1)",
	        pointColor: "rgba(247,70,74,1)",
	        pointStrokeColor: "#fff",
	        pointHighlightFill: "#fff",
	        pointHighlightStroke: "rgba(247,70,74,0.8)",
					data : [<?php foreach($pop as $month => $result) { echo ($result['noshow'] == false) ? '0' : $result['noshow']; echo ',';}?>]
				},
				{
					label: "Cancelled",
					fillColor: "rgba(253,180,92,0.2)",
					strokeColor: "rgba(253,180,92,1)",
					pointColor: "rgba(253,180,92,1)",
					pointStrokeColor: "#fff",
					pointHighlightFill: "#fff",
					pointHighlightStroke: "rgba(253,180,92,0.8)",
					data : [<?php foreach($pop as $month => $result) { echo ($result['cancelled'] == false) ? '0' : $result['cancelled']; echo ',';}?>]
				}
			]

		}
	window.onload = function(){
		var ctx = document.getElementById("canvas").getContext("2d");
		window.myLine = new Chart(ctx).Line(lineChartData, {
			responsive: true,
			multiTooltipTemplate: "<%= datasetLabel %>: <%= value %>",
			scaleBeginAtZero: true
		});
    var ctx = document.getElementById("availabilities").getContext("2d");
    window.myLine = new Chart(ctx).Line(availabilityData, {
      responsive: true,
      multiTooltipTemplate: "<%= datasetLabel %>: <%= value %>",
      scaleBeginAtZero: true
    });
	}


</script>


<?php
require_once('../includes/footer.php');
?>
