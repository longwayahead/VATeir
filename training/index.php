<?php
$pagetitle = "Training Home";
require_once("includes/header.php");
?>
<div class="row">
	<h3 class="text-center">My Training Dashboard</h3><br><br>


		<div class="col-md-4">

				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">My next session</h3>
					</div>
					<div class="panel-body">

						<?php
						 $session = $s->nextSession($user->data()->id);
					 if($session !== false) {
					// 	 print_r($session);
						 	?>
							<div style="border-radius:10px; background-color:<?php echo $session->colour;?>; color:white;">
								<p class="text-center"><span style="font-size:30px;"><?php echo $session->callsign;?></span>
									<br>

									<span style="font-size:15px;"><?php echo date('j\<\s\u\p\>S\<\/\s\u\p\> M Y', strtotime($session->start));?></span>
									<br>
									<br>

									<span class="glyphicon glyphicon-plane" aria-hidden="true"></span> <span style="font-size:15px;"><?php echo $session->type;?></span>
									<br>
									<span class="glyphicon glyphicon-user" aria-hidden="true"></span> <span style="font-size:15px;"><?php echo $session->first_name . ' ' . $session->last_name;?></span>
									<br>
									<span class="glyphicon glyphicon-time" aria-hidden="true"></span> <span style="font-size:15px;"><?php echo date('H:i', strtotime($session->start)) . ' to ' . date('H:i', strtotime($session->finish));?> IST</span>
									<br>


									<br>
									<a style="margin-top:-20px;" target="_blank" class="btn btn-primary btn-xs" href="sessions.php#s<?php echo $session->id;?>"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> View</a>
								</p>
								</div>

							<?php
					 	} else {
							  ?><div class="text-danger text-center" style="font-size:16px;"><br>None booked :-(</div><br><?php
						}
						?>
					</div>
				</div>

		</div>
		<div class="col-md-4">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Latest reports</h3>
				</div>
				<div class="panel-body">
					<?php
						$rep = $r->getReport(3, $user->data()->id);
						if($rep !== false) {
							// print_r($rep);
							foreach($rep as $report) {
								?>
								<div style="border-radius:10px; background-color:<?php echo $report->colour;?>; color:white;">
									<p class="text-center"><span style="font-size:30px;"><?php echo $report->callsign;?></span>
										<br>
										<span style="font-size:15px;"><?php echo date('j\<\s\u\p\>S\<\/\s\u\p\> M Y', strtotime($report->session_date));?></span>
										<br>
										<br>
										<a style="margin-top:-20px;" target="_blank" class="btn btn-primary btn-xs" href="history.php#r<?php echo $report->rep_id;?>"><span class="glyphicon glyphicon-book" aria-hidden="true"></span> Read</a>
									</p>
									</div>
								<?php
							}
						} else {
							?><div class="text-danger text-center" style="font-size:16px;"><br>No reports</div><br><?php
						}
					?>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Latest validations</h3>
				</div>
				<div class="panel-body">
					<?php
					$vals = $t->fetchAllValidations($user->data()->id, '`c`.`id`', null, 'LIMIT 2');
					// echo '<pre>';
					// print_r($vals);
					// echo '</pre>';
					if($vals) {

					foreach($vals as $val) { ?>
						<div style="border-radius:10px; background-color:#4caf50; color:white;">
							<p class="text-center"><span style="font-size:30px;"><?php echo $val->callsign;?></span>
								<br>
								Expires
								<br>
								<span style="font-size:15px;"><?php echo date('j\<\s\u\p\>S\<\/\s\u\p\> M Y', strtotime($val->valid_until));?></span>
									</p>
							</div>
							<?php
					}
				} else {
					?><div class="text-danger text-center" style="font-size:16px;"><br>No validations</div><br><?php
				}
					 ?>
				</div>
				<?php if($vals) {
					?>
					<div class="panel-footer text-right">
						<a href="validations.php">View all</a>
					</div>
					<?php
				}
?>
			</div>
		</div>
	</div>

<?php
echo '</div>';
require_once("../includes/footer.php");
?>
