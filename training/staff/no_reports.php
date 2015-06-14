<?php
require_once("../includes/header.php");
try {
$noreport = $s->get(array(
		'all' => 1,
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
								<strong>Mentor</strong>
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
						</tr>
					
						<?php foreach($noreport as $session): ?>
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
								<td>
									<?php echo date("H:i", strtotime($session->start)); ?> 
								</td>
								<td>
									<?php echo date("H:i", strtotime($session->finish)); ?> 
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

