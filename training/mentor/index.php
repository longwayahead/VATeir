<?php
$pagetitle = 'Mentor Home';
require_once("../includes/header.php");
if(!$user->hasPermission('mentor')) {
	Session::flash('error', 'Insufficient permissions');
	Redirect::to('../');
}
$g = new Graph;
$m = $g->mm($user->data()->id);
$show = false;
foreach($m as $j) {
	if($j > 0) {
		$show = true;
	}
}
 ?>
<h3 class="text-center">Mentor Dashboard</h3><br>
<div class="row">
	<div class="col-md-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">My Forthcoming Sessions</h3>
			</div>
			<div class="panel-body">
				<?php
				try {
					$sessions = $s->get(array(
							'mentor' => $user->data()->id,
							'future' => 1,
							'deleted' => 0
						));
				//	print_r($sessions);
					if(!empty($sessions)) {
					?>
					<table class="table table-condensed table-striped">
						<tr>
							<td>
								<strong>Student</strong>
							</td>
							<td>
								<strong>Position</strong>
							</td>
							<td class="nowrap">
								<strong>Date</strong>
							</td>
							<td>
								<strong>Starts</strong>
							</td>
							<td>
								<strong>Edit</strong>
							</td>
						</tr>

						<?php $i=1; foreach($sessions as $session): ?>
							<?php if($i <= 5) { ?>
								<?php $i++; ?>

								<tr>
									<td>
										<?php echo '<a href="view_student.php?cid=' . $session->student . '">' . $session->sfname . ' ' . $session->slname . '</a>'; ?>
									</td>
									<td>
										<?php echo $session->callsign; ?>
									</td>
									<td class="nowrap">
										<?php echo date("j-M-y", strtotime($session->start)); ?>
									</td>
									<td>
										<?php echo date("H:i", strtotime($session->start)); ?>
									</td>
									<td>
										<?php echo '<a class="btn btn-xs btn-primary" href="edit_session.php?id=' . $session->session_id . '"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></a>' ?>
									</td>
								</tr>
							<?php } ?>

						<?php endforeach; ?>

					</table>
					<?php
					} else {
						echo '<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">No sessions</div><br>';
					}
				} catch(Exception $e) {
					echo $e->getMessage();
				}
				?>
			</div>
			<?php
			if(count($sessions) > 5) {
				echo '<div class="panel-footer text-right"><a href="my_sessions.php">View All</a></div>';
			}
			?>
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Sessions without reports</h3>
			</div>
			<div class="panel-body">
			<?php
				try {
					$noreport = $s->get(array(
							'mentor' => $user->data()->id,
							'noreport' => 1,
							'cancelled' => 1,
							'future' => 2
						));
					if(!empty($noreport)) {
					?>
					<table class="table table-condensed table-striped">
						<tr>
							<td>
								<strong>Student</strong>
							</td>
							<td>
								<strong>Position</strong>
							</td>
							<td class="nowrap">
								<strong>Date</strong>
							</td>
							<td>
								<strong>View</strong>
							</td>
						</tr>

						<?php foreach($noreport as $session): ?>
							<tr>
								<td>
									<?php echo '<a href="view_student.php?cid=' . $session->student . '">' . $session->sfname . ' ' . $session->slname . '</a>'; ?>
								</td>
								<td>
									<?php echo $session->callsign; ?>
								</td>
								<td class="nowrap">
									<?php echo date("j-M-y", strtotime($session->start)); ?>
								</td>
								<td>
									<?php echo '<a class="btn btn-xs btn-primary" href="add_report.php?s=' . $session->session_id . '"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></a>' ?>
								</td>
							</tr>

						<?php endforeach; ?>

					</table>
					<?php
					} else {
						echo '<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">No reports outstanding</div><br>';
					}
				} catch(Exception $e) {
					echo $e->getMessage();
				}
				?>
			</div>
			<?php
			if(count($noreport) > 5) {
				echo '<div class="panel-footer text-right"><a href="my_sessions.php">View All</a></div>';
			}
			?>
		</div>
	</div>
</div>
<br>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Available Students</h3>
			</div>
			<div class="panel-body">
				<?php
      try {
      	$a = new Availability;
        // $availabilities = $a->get(array(
        // 	'limit' => 5
        // 	));
        $availabilities = $a->get(['deleted' => 0]);
        if(!empty($availabilities)) {
           ?>
           <table class="table table-condensed table-striped">
			<tr>
				<td>
					<strong>Name</strong>
				</td>
				<td class="hidden-xs">
					<strong>Programme</strong>
				</td>
				<td class="nowrap">
					<strong>Date</strong>
				</td>
				<td>
					<strong>From</strong>
				</td>
				<td>
					<strong>Until</strong>
				</td>
				<td>
					<strong>Book</strong>
				</td>
            </tr>
            <?php foreach($availabilities as $availability): ?>
              <tr>
              	<td><?php echo '<a href="view_student.php?cid=' . $availability->cid . '">' . $availability->first_name . ' ' . $availability->last_name . '</a>';?></td>
              	<td class="hidden-xs"><?php echo $availability->program_name;?></td>
                <td class="nowrap"><?php echo date("j F Y", strtotime($availability->date));?></td>
                <td><?php echo date("H:i", strtotime($availability->time_from));?></td>
                <td><?php echo date("H:i", strtotime($availability->time_until));?></td>
             	<td><?php echo '<a class="btn btn-xs btn-default" href="schedule_session.php?id=' . $availability->availability_id . '"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></a>' ?></td>

			 </tr>
            <?php endforeach; ?>
          </table>
          <?php
        } else {
          echo '<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">No availabile students</div><br>';
        }
      } catch(Exception $e) {
        echo $e->getMessage();
      }
      ?>
			</div>
			<?php
			if(count($all) > 5) {
				echo '<div class="panel-footer text-right"><a href="view_available.php">View All</a></div>';
			}
			?>
		</div>
	</div>
</div>
<br>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">My Mentoring: Past 6 Months</h3>
			</div>
			<div class="panel-body">
				<?php if($show === true): ?>
					<canvas id="mm" style="padding-left:-20px; padding-right:20px;"></canvas>
				<?php else: ?>
					<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">No sessions</div><br>
				<?php endif; ?>

			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Completed Sessions</h3>
			</div>
			<div class="panel-body">
				<canvas id="overview" style="padding-left:-20px; padding-right:20px;"></canvas>
			</div>
		</div>
	</div>
</div>
<script>
<?php
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
            label: "This Month",
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
            label: "Last Month",
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
<?php

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
	var cty = document.getElementById("overview").getContext("2d");
	window.myRadar = new Chart(cty).Radar(radarData, {
		responsive: true,
		multiTooltipTemplate: "<%= datasetLabel %>: <%= value %>"
	});
	var ctz = document.getElementById("mm").getContext("2d");
	window.myProg = new Chart(ctz).PolarArea(progSessions, {
		responsive: true
	});
}
</script>

<?php
echo '</div>';
require_once("../../includes/footer.php");
?>
