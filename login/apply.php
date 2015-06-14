<?php
require_once("../includes/header.php");
if(isset($_POST["data"])) {
	$data = unserialize($_POST["data"]);
	if(isset($_POST["transfer"])) {
		if($user->isStatus($data->id, 3) || $user->isStatus($data->id, 4)) {
	?>
			
					<div class="col-md-12 text-center well">
						<h3>Your application is being processed</h3>
						<br>
						<p style="font-size:16px;">
							Hi <?php echo $data->name_first; ?>, your application is currently being processed.
							A member of staff will be with you soon.
							<br>
							<br>
						</p>
					</div>
				</div>
	<?php
			

		} else {

	?>
					<div class="col-md-8 col-md-offset-2">
						<div class="panel panel-warning">
							<div class="panel-heading">
								<h3 class="panel-title">Transfer to VATeir</h3>
							</div>
							<div class="panel-body">
								<table class="table table-striped">
									<tr>
										<td><strong>Name:</strong></td>
										<td><?php echo $data->name_first . " " . $data->name_last; ?></td>
										<td><strong>Registered On:</strong></td>
										<td><?php echo $data->reg_date; ?></td>
									</tr>
									<tr>
										<td><strong>VATSIM ID:</strong></td>
										<td><?php echo $data->id; ?></td>
										<td><strong>Current Region:</strong></td>
										<td><?php echo $data->region->name; ?></td>
										
									</tr>
									<tr>
										<td><strong>ATC Rating:</td>
										<td><?php echo $data->rating->long . " (" . $data->rating->short . ")"; ?></td>
										<td><strong>Current Division:</strong></td>
										<td><?php echo $data->division->name; ?></td>
										
									</tr>
									<tr>
										<td><strong>Email:</strong></td>
										<td><?php echo $data->email; ?></td>
										<td><strong>Current Sub-division:</strong></td>
										<td><?php echo $data->subdivision->name; ?></td>
									</tr>
								</table>
							</div>
							<div class="text-center">
								<form method="post" action="sendapplication.php">
									<button type="submit" name="transfer" class="btn btn-lg btn-warning">
										<span class="glyphicon glyphicon-road" aria-hidden="true"></span>
										 Submit Application
									</button>
									<input type="hidden" name="data" value="<?php echo htmlspecialchars(serialize($data), ENT_QUOTES); ?>">
								</form>
								<br>
							</div>
						</div>
					</div>
	<?php

		}
	} elseif(isset($_POST["visiting"])) {
		if($user->isStatus($data->id, 3) || $user->isStatus($data->id, 4)) {
?>
					<div class="col-md-12 text-center well">
						<h3>Your application is being processed</h3>
						<br>
						<p style="font-size:16px;">
							Hi <?php echo $data->name_first; ?>, your application is currently being processed.
							A member of staff will be with you soon.
							<br>
							<br>
						</p>
					</div>
	<?php
		} else {

	?>
					<div class="col-md-8 col-md-offset-2">
						<div class="panel panel-success">
							<div class="panel-heading">
								<h3 class="panel-title">Visiting Controller Application</h3>
							</div>
							<div class="panel-body">
								<table class="table table-striped">
									<tr>
										<td><strong>Name:</strong></td>
										<td><?php echo $data->name_first . " " . $data->name_last; ?></td>
										<td><strong>Registered On:</strong></td>
										<td><?php echo $data->reg_date; ?></td>
									</tr>
									<tr>
										<td><strong>VATSIM ID:</strong></td>
										<td><?php echo $data->id; ?></td>
										<td><strong>Current Region:</strong></td>
										<td><?php echo $data->region->name; ?></td>
										
									</tr>
									<tr>
										<td><strong>ATC Rating:</td>
										<td><?php echo $data->rating->long . " (" . $data->rating->short . ")"; ?></td>
										<td><strong>Current Division:</strong></td>
										<td><?php echo $data->division->name; ?></td>
										
									</tr>
									<tr>
										<td><strong>Email:</strong></td>
										<td><?php echo $data->email; ?></td>
										<td><strong>Current Sub-division:</strong></td>
										<td><?php echo $data->subdivision->name; ?></td>
									</tr>
								</table>
							</div>
							<div class="text-center">
								<form method="post" action="sendapplication.php">
									<button type="submit" name="visiting" class="btn btn-lg btn-success">
										<span class="glyphicon glyphicon-plane" aria-hidden="true"></span>
										 Submit Application
									</button>
									<input type="hidden" name="data" value="<?php echo htmlspecialchars(serialize($data), ENT_QUOTES); ?>">
								</form>
								<br>
							</div>
						</div>
					</div>
	<?php
		}
	}
} else {
	Session::flash("error", "Cannnot access resource!");
	Redirect::to("../index.php");
}

require_once('../includes/footer.php');