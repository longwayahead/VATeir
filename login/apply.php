<?php
require_once("../includes/header.php");
if(isset($_POST["data"])) {
	$data = unserialize($_POST["data"]);
	$t = new Training;
	$isAllowed = $t->getVisCID($user->user->id);
		if($isAllowed == false) { //If they are not in the CIDs table
			Session::flash("error", "Cannnot access resource!");
			Redirect::to("../index.php");
		} else { //if they are in the visiting cids table
			?>
			<div class="col-md-8 col-md-offset-2">
				 <h4>Become a visiting controller</h4><br>
				<div class="panel panel-success">
					<div class="panel-heading">
						<h3 class="panel-title">Visiting controller details</h3>
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
								 Create account
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


require_once('../includes/footer.php');
