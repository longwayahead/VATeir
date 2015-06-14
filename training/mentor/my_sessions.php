<?php
$pagetitle = "My Sessions";
require_once('../includes/header.php');
echo '<h3 class="text-center">My Sessions</h3><br>';

$s = new Sessions;

?>

<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Forthcoming Sessions</h3>
			</div>
			<div class="panel-body">
				<?php
				try {
					$sessions = $s->get(array(
							'mentor' => $user->data()->id,
							'future' => 1
						));
					if(!empty($sessions)) {
					?>
					<table class="table table-condensed table-striped">
						<tr>
							<td>
								<strong>Student</strong>
							</td>
							<td>
								<strong>Program</strong>
							</td>
							<td>
								<strong>Position</strong>
							</td>
							<td>
								<strong>Type</strong>
							</td>
							<td>
								<strong>Date</strong>
							</td>
							<td>
								<strong>Start</strong>
							</td>
							<td>
								<strong>Finish</strong>
							</td>
						</tr>
					
						<?php foreach($sessions as $session): ?>
							<tr>
								<td>
									<?php echo $session->sfname . ' ' . $session->slname; ?> 
								</td>
								<td>
									<?php echo $session->program_name; ?> 
								</td>
								<td>
									<?php echo $session->position_name . ' (' . $session->callsign . ')'; ?> 
								</td>
								<td>
									<?php echo $session->session_name; ?> 
								</td>
								<td>
									<?php echo date("j-M-y", strtotime($session->start)); ?> 
								</td>
								<td>
									<?php echo date("H:i", strtotime($session->start)); ?> 
								</td>
								<td>
									<?php echo date("H:i", strtotime($session->finish)); ?> 
								</td>
							</tr>

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
		</div>
	</div>

</div>

	
		<?php
				try {
					$noreport = $s->get(array(
							'mentor' => $user->data()->id,
							'noreport' => 1,
							'future' => 2
						));
				}catch(Exception $e) {
					echo $e->getMessage();
				}
					if(!empty($noreport)) {
					?>
					<div class="row">
					<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-danger">
			<div class="panel-heading">
				<h3 class="panel-title">Sessions without reports</h3>
			</div>
			<div class="panel-body" style="padding:0px;">
			
					<table class="table table-condensed table-striped">
						<tr>
							<td>
								<strong>Student</strong>
							</td>
							<td>
								<strong>Position</strong>
							</td>
							<td>
								<strong>Date</strong>
							</td>
							<td>
								<strong>Start</strong>
							</td>
							<td>
								<strong>Finish</strong>
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
								<td>
									<?php echo date("j-M-y", strtotime($session->start)); ?> 
								</td>
								<td>
									<?php echo date("H:i", strtotime($session->start)); ?> 
								</td>
								<td>
									<?php echo date("H:i", strtotime($session->finish)); ?> 
								</td>
								<td>
									<?php echo '<a class="btn btn-xs btn-danger" href="add_report.php?s=' . $session->session_id . '"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></a>' ?> 
								</td>
							</tr>

						<?php endforeach; ?>

					</table>
			</div>
				</div>
	</div>
	</div>

			<?php
			}
			?>



<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Past Sessions</h3>
			</div>
			<div class="panel-body" style="padding:0px;">
				<?php
				try {
					$sessions = $s->get(array(
							'mentor' => $user->data()->id,
							'future' => 2
						));
					if(!empty($sessions)) {
					?>
					<table class="table table-condensed table-striped">
						<tr>
							<td>
								<strong>Student</strong>
							</td>
							<td>
								<strong>Program</strong>
							</td>
							<td>
								<strong>Position</strong>
							</td>
							<td>
								<strong>Type</strong>
							</td>
							<td>
								<strong>Date</strong>
							</td>
							<td>
								<strong>Start</strong>
							</td>
							<td>
								<strong>Finish</strong>
							</td>
						</tr>
					
						<?php foreach($sessions as $session): ?>
							<tr>
								<td>
									<?php echo $session->sfname . ' ' . $session->slname; ?> 
								</td>
								<td>
									<?php echo $session->program_name; ?> 
								</td>
								<td>
									<?php echo $session->position_name . ' (' . $session->callsign . ')'; ?> 
								</td>
								<td>
									<?php echo $session->session_name; ?> 
								</td>
								<td>
									<?php echo date("j-M-y", strtotime($session->start)); ?> 
								</td>
								<td>
									<?php echo date("H:i", strtotime($session->start)); ?> 
								</td>
								<td>
									<?php echo date("H:i", strtotime($session->finish)); ?> 
								</td>
							</tr>

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
		</div>
	</div>
</div>
</div>



<?php
require_once('../../includes/footer.php');