<?php
$pagetitle = 'Mentor Home';
require_once("../includes/header.php");
if(!$user->hasPermission('mentor')) {
	Session::flash('error', 'Insufficient permissions');
	Redirect::to('../');
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
								<strong>Position</strong>
							</td>
							<td>
								<strong>Date</strong>
							</td>
							<td>
								<strong>Starts</strong>
							</td><!-- 
							<td>
								View
							</td> -->
						</tr>
					
						<?php foreach($sessions as $session): ?>
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
								</td><!-- 
								<td>
									<?php //echo '<a class="btn btn-xs btn-primary" href="view_session.php?id=' . $session->session_id . '"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></a>' ?> 
								</td> -->
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
							<td>
								<strong>Date</strong>
							</td>
							<td>
								<strong>Started</strong>
							</td>
							<td>
								<strong>Finished</strong>
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
<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Available Students</h3>
			</div>
			<div class="panel-body">
				<?php
      try {
      	$a = new Availability;
        $availabilities = $a->get(array(
        	'limit' => 5
        	));
        $all = $a->get(['deleted' => 0]);
        if(!empty($availabilities)) {
           ?>
           <table class="table table-condensed table-striped">
			<tr>
				<td>
					<strong>Name</strong>
				</td>
				<td>
					<strong>Programme</strong>
				</td>
				<td>
					<strong>Date</strong>
				</td>
				<td>
					<strong>Time From</strong>
				</td>
				<td>
					<strong>Time Until</strong>
				</td>
				<td>
					<strong>Book</strong>
				</td>
            </tr>
            <?php foreach($availabilities as $availability): ?>
              <tr>
              	<td><?php echo '<a href="view_student.php?cid=' . $availability->cid . '">' . $availability->first_name . ' ' . $availability->last_name . '</a>';?></td>
              	<td><?php echo $availability->program_name;?></td>
                <td><?php echo date("j-M-y", strtotime($availability->date));?></td>
                <td><?php echo date("H:i", strtotime($availability->time_from));?></td>
                <td><?php echo date("H:i", strtotime($availability->time_until));?></td>
             	<td><?php echo '<a class="btn btn-xs btn-default" href="schedule_session.php?id=' . $availability->availability_id . '"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></a>' ?></td>
              </tr>
            <?php endforeach; ?>
          </table>
          <?php
        } else {
          echo '<div class="text-danger text-center" style="font-size:16px; margin-top:8px;">No availability</div><br>';
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

<?php
echo '</div>';
require_once("../../includes/footer.php");
?>