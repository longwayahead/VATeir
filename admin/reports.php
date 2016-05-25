<?php
require_once('includes/header.php');
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
<h3 class="text-center">Site Overview</h3><br>
<div class="row">
	<div class="col-md-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Sessions and Availabilities</h3>
			</div>
			<div class="panel-body">
				<canvas id="canvas" style="padding-left:-20px; padding-right:20px;"></canvas>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Sessions</h3>
			</div>
			<div class="panel-body">
				<canvas id="radar" style="padding-left:-20px; padding-right:20px;"></canvas>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Sessions</h3>
			</div>
			<div class="panel-body">
				<canvas id="barData" style="padding-left:-20px; padding-right:20px;"></canvas>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">My Mentoring</h3>
			</div>
			<div class="panel-body">
				<canvas id="progSessions" style="padding-left:-20px; padding-right:20px;"></canvas>
			</div>
		</div>
	</div>
</div>

	<script>
	// var white="rgba(255,255,255,1.0)";
	// var fillBlack="rgba(45, 53, 60, 0.6)";
	// var fillBlackLight="rgba(45, 53, 60, 0.2)";
	// var strokeBlack="rgba(45, 53, 60, 0.8)";
	// var highlightFillBlack="rgba(45, 53, 60, 0.8)";
	// var highlightStrokeBlack="rgba(45, 53, 60, 1)";
	// var fillBlue="rgba(52, 143, 226, 0.6)";
	// var fillBlueLight="rgba(52, 143, 226, 0.2)";
	// var strokeBlue="rgba(52, 143, 226, 0.8)";
	// var highlightFillBlue="rgba(52, 143, 226, 0.8)";
	// var highlightStrokeBlue="rgba(52, 143, 226, 1)";
	// var fillGrey="rgba(182, 194, 201, 0.6)";
	// var fillGreyLight="rgba(182, 194, 201, 0.2)";
	// var strokeGrey="rgba(182, 194, 201, 0.8)";
	// var highlightFillGrey="rgba(182, 194, 201, 0.8)";
	// var highlightStrokeGrey="rgba(182, 194, 201, 1)";
	// var fillGreen="rgba(0, 172, 172, 0.6)";
	// var fillGreenLight="rgba(0, 172, 172, 0.2)";
	// var strokeGreen="rgba(0, 172, 172, 0.8)";
	// var highlightFillGreen="rgba(0, 172, 172, 0.8)";
	// var highlightStrokeGreen="rgba(0, 172, 172, 1)";
	// var fillPurple="rgba(114, 124, 182, 0.6)";
	// var fillPurpleLight="rgba(114, 124, 182, 0.2)";
	// var strokePurple="rgba(114, 124, 182, 0.8)";
	// var highlightFillPurple="rgba(114, 124, 182, 0.8)";
	// var highlightStrokePurple="rgba(114, 124, 182, 1)";
		var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
		var lineChartData = {
			<?php
			$g = new Graph;
			$pop = $g->sa();
			?>
			labels : [<?php foreach($pop as $month => $result) { echo '"' . $month . '", ';}?>],
			datasets : [
				{
					label: "Sessions",
					fillColor : "rgba(220,220,220,0.2)",
					strokeColor : "rgba(220,220,220,1)",
					pointColor : "rgba(220,220,220,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(220,220,220,1)",
					data : [<?php foreach($pop as $month => $result) { echo $result['sessions'] . ',';}?>]
				},
				{
					label: "Availabilities",
					fillColor : "rgba(151,187,205,0.2)",
					strokeColor : "rgba(151,187,205,1)",
					pointColor : "rgba(151,187,205,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(151,187,205,1)",
					data : [<?php foreach($pop as $month => $result) { echo $result['availability'] . ',';}?>]
				}
			]

		}
		<?php
		$g = new Graph;
		$graph = $g->sbm();
		?>
		var radarData = {
		    labels: [
			    <?php
			    foreach($graph as $name => $value) {
					echo '"' . $name . '",';
				}
				?>
		    ],
		    datasets: [
		        {
		            label: "Last month",
		            fillColor: "rgba(151,187,205,0.2)",
		            strokeColor: "rgba(151,187,205,1)",
		            pointColor: "rgba(151,187,205,1)",
		            pointStrokeColor: "#fff",
		            pointHighlightFill: "#fff",
		            pointHighlightStroke: "rgba(151,187,205,1)",
		            data: [
		            	<?php
		            	foreach($graph as $name=>$value) {
							echo $value['this'] . ',';
						}
		            	?>
		           	]
		        },
		        {
		            label: "Month before",
		            fillColor: "rgba(220,220,220,0.2)",
		            strokeColor: "rgba(220,220,220,1)",
		            pointColor: "rgba(220,220,220,1)",
		            pointStrokeColor: "#fff",
		            pointHighlightFill: "#fff",
		            pointHighlightStroke: "rgba(220,220,220,1)",
		            data: [
		            	<?php
		            	foreach($graph as $name=>$value) {
							echo $value['last'] . ',';
						}
		            	?>
		           	]
		        },
		    ]
		};
		var barData = {
		    labels: ["OBS-S1", "S1-S2", "S2-S3", "S3-C1"],
		    datasets: [
		        {
		            label: "July",
		            fillColor: "rgba(151,187,205,0.5)",
		            strokeColor: "rgba(151,187,205,0.8)",
		            highlightFill: "rgba(151,187,205,0.75)",
		            highlightStroke: "rgba(151,187,205,1)",
		            data: [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
		        },
		        {
		            label: "June",
		            fillColor: "rgba(220,220,220,0.5)",
		            strokeColor: "rgba(220,220,220,0.8)",
		            highlightFill: "rgba(220,220,220,0.75)",
		            highlightStroke: "rgba(220,220,220,1)",
		            data: [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
		        }
		    ]
		};
		<?php
		$m = $g->mm(931070);
		?>
		var progSessions = [
		    {
		        value: <?php echo $m['S1'];?>,
		        color:"#F7464A",
		        highlight: "#FF5A5E",
		        label: "OBS-S1"
		    },
		    {
		        value: <?php echo $m['S2'];?>,
		        color: "#46BFBD",
		        highlight: "#5AD3D1",
		        label: "S1-S2"
		    },
		    {
		        value: <?php echo $m['S3'];?>,
		        color: "#FDB45C",
		        highlight: "#FFC870",
		        label: "S2-S3"
		    },
		    {
		        value: <?php echo $m['C1'];?>,
		        color: "#949FB1",
		        highlight: "#A8B3C5",
		        label: "S3-C1"
		    }

		];




	window.onload = function(){
		var ctx = document.getElementById("canvas").getContext("2d");
		window.myLine = new Chart(ctx).Line(lineChartData, {
			responsive: true,
			multiTooltipTemplate: "<%= datasetLabel %>: <%= value %>",
			scaleBeginAtZero: true
		});
		var cty = document.getElementById("radar").getContext("2d");
		window.myRadar = new Chart(cty).Radar(radarData, {
			responsive: true,
			multiTooltipTemplate: "<%= datasetLabel %>: <%= value %>"
		});
		var cta = document.getElementById("barData").getContext("2d");
		window.myBar = new Chart(cta).Bar(barData, {
			responsive: true,
			multiTooltipTemplate: "<%= datasetLabel %>: <%= value %>"
		});
		var ctz = document.getElementById("progSessions").getContext("2d");
		window.myProg = new Chart(ctz).PolarArea(progSessions, {
			responsive: true
		});
	}



	</script>
