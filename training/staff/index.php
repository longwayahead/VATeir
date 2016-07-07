<?php
$pagetitle = "TD Staff Home";
require_once("../includes/header.php");
if(!$user->hasPermission('tdstaff')) {
	Session::flash('error', 'Invalid permissions');
	Redirect::to(BASE_URL . 'training');
}
?>
<h3 class="text-center">Staff Dashboard</h3><br>
<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Staff Tasks</h3>
			</div>
			<div class="panel-body">
				<table class="table table-responsive table-striped table-condensed">
					<?php
					$notifications = $n->getList(1, 3);
					if($notifications) {
						echo '<tr>
								<td><strong>Type</strong></td>
								<td><strong>Controller</strong></td>
								<td class="nowrap"><strong>Submitted On</strong></td>
								<td><strong>View</strong></td>
							</tr>';
						foreach($notifications as $notification) {
							echo '<tr>
									<td>' . $notification->type_name . '</td>
									<td><a href="../mentor/view_student.php?cid=' . $notification->from . '">' . $notification->first_name . ' ' . $notification->last_name . '</a></td>
									<td class="nowrap">' . date("j-M-Y", strtotime($notification->submitted)) . '</td>
									<td><a href="../../notifications/view.php?id=' . $notification->notification_id . '" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></a></td>
								</tr>';
						}
					} else {
						echo '<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">No tasks</div>';
					}
					?>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Sessions without reports</h3>
			</div>
			<div class="panel-body">
			<?php
				try {
					$noreport = $s->get(array(
							'all' => 1,
							'noreport' => 1,
							'cancelled' => 1,
							'future' => 2
						));
					if(!empty($noreport)) {
					?>
					<table style="margin-bottom:0px;"  class="table table-condensed table-striped">
						<tr>
							<td>
								<strong>Student</strong>
							</td>
							<td>
								<strong>Mentor</strong>
							</td>
							<td>
								<strong>Position</strong>
							</td>
							<td>
								<strong>Date</strong>
							</td>
						</tr>

						<?php $i=1; foreach($noreport as $session): ?>
						<?php if($i < 6) { $i++; ?>
							<tr>
								<td>
									<?php echo '<a href="../mentor/view_student.php?cid=' . $session->student . '">' . $session->sfname . ' ' . $session->slname . '</a>'; ?>
								</td>
								<td>
									<?php echo $session->mfname . ' ' . $session->mlname; ?>
								</td>
								<td>
									<?php echo $session->callsign; ?>
								</td>
								<td>
									<?php echo date("j-M-y", strtotime($session->start)); ?>
								</td>
							</tr>
							<?php } ?>
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
				echo '<div class="panel-footer text-right"><a href="no_reports.php">View All</a></div>';
			}
			?>
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Future Sessions</h3>
			</div>
			<div class="panel-body">
			<?php
				try {
					$future = $s->get(array(
							'all' => 1,
							'noreport' => 1,
							'deleted' => 0,
							'future' => 1
						));
					if(!empty($future)) {
					?>
					<table style="margin-bottom:0px;" class="table table-condensed table-striped">
						<tr>
							<td>
								<strong>Student</strong>
							</td>
							<td>
								<strong>Mentor</strong>
							</td>
							<td>
								<strong>Position</strong>
							</td>
							<td>
								<strong>Date</strong>
							</td>
						</tr>

						<?php $i=1; foreach($future as $session): ?>
						<?php if($i < 6) { $i++; ?>
							<tr>
								<td>
									<?php echo '<a href="../mentor/view_student.php?cid=' . $session->student . '">' . $session->sfname . ' ' . $session->slname . '</a>'; ?>
								</td>
								<td>
									<?php echo $session->mfname . ' ' . $session->mlname; ?>
								</td>
								<td>
									<?php echo $session->callsign; ?>
								</td>
								<td>
									<?php echo date("j-M-y", strtotime($session->start)); ?>
								</td>
							</tr>
							<?php } ?>
						<?php endforeach; ?>

					</table>
					<?php
					} else {
						echo '<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">No future sessions</div><br>';
					}
				} catch(Exception $e) {
					echo $e->getMessage();
				}
				?>
			</div>
			<?php
			if(count($future) > 5) {
				echo '<div class="panel-footer text-right"><a href="all_sessions.php">View All</a></div>';
			}
			?>
		</div>
	</div>
</div>

<div class="row">

		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title	">Last 5 Reports</h3>
				</div>
				<div class="panel-body">
					<table class="table table-responsive table-striped table-condensed">
					<?php

					$reports = $r->getReport(2, 0);
					if($reports) {
						echo '<tr>
								<td><strong>Student</strong></td>
								<td><strong>Mentor</strong></td>
								<td><strong>Submitted On</strong></td>
							</tr>';
						foreach($reports as $report) {
							echo '<tr>
									<td><a href="../mentor/view_student.php?cid=' . $report->student_cid . '">' . $report->sfname . ' ' . $report->slname . '</a></td>
									<td>' . $report->mfname . ' ' . $report->mlname . '</td>
									<td>' . date("j-M-Y", strtotime($report->submitted_date)) . '</td>
								</tr>';
						}
					} else {
						echo '<h5 class="text-center">No Reports</h5>';
					}

					?>


					</table>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Last 5 Validations</h3>
				</div>
				<div class="panel-body">
					<table class="table table-responsive table-striped table-condensed">


					<?php

					$validations = $t->fetchAllValidations();
					if($validations) {
						echo '<tr>
								<td><strong>Student</strong></td>
								<td><strong>Position</strong></td>
								<td><strong>Valid Until</strong></td>
								<td><strong>View</strong></td>
							</tr>';
						foreach($validations as $validation) {
							echo '<tr>
									<td><a href="../mentor/view_student.php?cid=' . $validation->cid . '">' . $validation->student_fname . ' ' . $validation->student_lname . '</a></td>
									<td>' . $validation->callsign . '</td>
									<td>' . date("j-M-Y", strtotime($validation->valid_until)) . '</td>
									<td><a href="../mentor/view_validations.php?cid=' . $validation->cid . '" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></a></td>
								</tr>';
						}
					} else {
						echo '<h5 class="text-center">No Validations</h5>';
					}

					?>


					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Sessions and Availabilities</h3>
				</div>
				<div class="panel-body">
					<canvas id="canvas" style="padding-left:-20px; padding-right:20px;"></canvas>

				</div>
			</div>
		</div>
	</div>
	</div>




<script>
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
					data : [<?php foreach($pop as $month => $result) { echo ($result['sessions'] == false) ? '0' : $result['sessions']; echo ',';}?>]
				},
				{
					label: "Availabilities",
					fillColor : "rgba(151,187,205,0.2)",
					strokeColor : "rgba(151,187,205,1)",
					pointColor : "rgba(151,187,205,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(151,187,205,1)",
					data : [<?php foreach($pop as $month => $result) { echo ($result['availability'] == false) ? '0' : $result['availability']; echo ',';}?>]
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
	}

</script>


<?php



echo '</div>';
require_once("../../includes/footer.php");
?>
