<?php
require_once('../includes/header.php');
if(!$user->hasPermission('tdstaff')) {
	Session::flash('error', 'Invalid permissions');
	Redirect::to(BASE_URL . 'training');
}
echo '<h2 class="text-center">Validations <small>';
	if(isset($_GET['e'])) {
		echo 'Extend';
	} else {
		echo 'Approve';
	}
echo '</small></h2>';
if((!isset($_GET['e'])) && (!isset($_POST['e']))) {
	if(Input::exists()) {
		if(isset($_POST['approve'])) {
			foreach(Input::get('validations') as $key => $val) {
				if($val == "on") {
					$update =  $t->updateValidation(array(
							'approved' => 1
						), [['id', '=', $key]]);
				}
			}
			$t->generateValFile();
			Session::flash('success', 'Validations Extended!');
			Redirect::to('./validations.php');
		} elseif(isset($_POST['reject'])) {
			foreach(Input::get('validations') as $key => $val) {
				if($val == "on") {
					$delete =  $t->deleteByValID($key);
				}
			}
			$t->generateValFile();
			Session::flash('success', 'Validations Rejected!');
			Redirect::to('./validations.php');
		}
	} else { //if form not submitted
		try{
			$validations = $t->fetchAllValidations(0, '`v`.`approved`');
		}catch(Exception $e) {
			echo $e->getMessage();
		}


			echo '<div class="col-md-10 col-md-offset-1">
					<div class="pull-right">
						<a href="validations.php?e=1">Expiring Validations <span class="badge"></span></a>
					</div>
					<br>
					<br>
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">Approve Validations</h3>
						</div>
						<div class="panel-body">
							
								';
								if($validations) { 
									echo '<table class="table table-responsive table-striped table-responsive">
									<tr>
										<td><input style="margin-left:5px;" type="checkbox" class="checkbox check" id="checkAll"></td>
										<td><strong>Position</strong></td>
										<td><strong>Controller</strong></td>
										<td><strong>Issued By</strong></td>
										<td><strong>From</strong></td>
										<td><strong>Until</strong></td>
									</tr>
									<form class="form-horizontal" method="post" action="">
									';
									foreach($validations as $validation) {
										echo '

										<tr>
											<td>
												<input style="margin-left:5px;" class="checkbox check" name="validations['.$validation->valid.']" type="checkbox">
											</td>
											<td>' . $validation->callsign . '</td>
											<td><a href="../mentor/view_student.php?cid=' . $validation->cid . '">' . $validation->student_fname . ' ' . $validation->student_lname . '</a></td>
											<td>' . $validation->mentor_fname . ' ' . $validation->mentor_lname . '</td>
											<td>' . date("j-M-Y", strtotime($validation->valid_from)) . '</td>
											<td>' . date("j-M-Y", strtotime($validation->valid_until)) . '</td>
										</tr>

										';
									}
									echo '</table>';
									echo '</div>
											<div class="panel-footer">
												<div class="text-right">

													<button type="submit" name="approve" class="btn btn-primary">Approve</button>
													<button type="submit" name="reject" class="btn btn-danger">Reject</button>
													
												</div>
												</form>
											';
								} else {
									echo '<div class="text-danger text-center" style="font-size:16px">No Validations to approve</div>';
								} //no validations
								echo '
								
							</div>
						</div>
					</div>
					</div>
		';
	
	}
} else {
	if(Input::exists()) {
		if(isset($_POST['extend'])) {
			$duration = 12;
			$from = date('Y-m-d');
			$until = date('Y-m-d', strtotime("+" . $duration . " weeks", strtotime($from)));
			foreach(Input::get('validations') as $key => $val) {
				if($val == "on") {
					$update =  $t->updateValidation(array(
							'valid_until' => $until
						), [['id', '=', $key]]);
				}
			}
			Session::flash('success', 'Validations Approved!');
		} elseif(isset($_POST['delete'])) {
			foreach(Input::get('validations') as $key => $val) {
				if($val == "on") {
					$delete =  $t->deleteByValID($key);
				}
			}
			Session::flash('success', 'Validations Deleted!');
		}
		$t->generateValFile();
		Redirect::to('./validations.php?e=1');
	} else {
		try{
			$validations = $t->oneMonth();
		}catch(Exception $e) {
			echo $e->getMessage();
		}
		//$vals = count($t->fetchAllValidations(0, '`v`.`approved`'));
			echo '<div class="col-md-10 col-md-offset-1">
					<div class="pull-right">
						<a href="validations.php">Approve Validations </a><span class="badge"></span>
					</div>
					<br>
					<br>
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">Validations expiring in next 7 days</h3>
						</div>
						<div class="panel-body">
							
							';
							if($validations) { 
								echo '<table class="table table-responsive table-striped table-responsive">
								<tr>
									<td><input style="margin-left:5px;" type="checkbox" class="checkbox check" id="checkAll"></td>
									<td><strong>Position</strong></td>
									<td><strong>Controller</strong></td>
									<td><strong>Issued By</strong></td>
									<td><strong>From</strong></td>
									<td><strong>Until</strong></td>
								</tr>
								<form class="form-horizontal" method="post" action="">
								';
								foreach($validations as $validation) {
									echo '
									<tr>
										<td>
											<input style="margin-left:5px;" class="checkbox check" name="validations['.$validation->valid.']" type="checkbox">
										</td>
										<td>' . $validation->callsign . '</td>
										<td><a href="../mentor/view_student.php?cid=' . $validation->cid . '">' . $validation->student_fname . ' ' . $validation->student_lname . '</a></td>
										<td>' . $validation->mentor_fname . ' ' . $validation->mentor_lname . '</td>
										<td>' . date("j-M-Y", strtotime($validation->valid_from)) . '</td>
										<td>' . date("j-M-Y", strtotime($validation->valid_until)) . '</td>
									</tr>

									';
								}
								echo '</table>';
							echo '	</div>
						<div class="panel-footer">
							<div class="text-right">
								<input type="hidden" name="e">
								<button type="submit" name="extend" class="btn btn-primary">Extend</button>
								<button type="submit" name="delete" class="btn btn-danger">Delete</button>
								
							</div>
							</form>';
						

							} else { //no validations

								echo '<div class="text-danger text-center" style="font-size:16px">No Validations to extend</div>';
						}
echo '</div></div>
					</div>
				</div>
			';
		
	}
}

require_once('../../includes/footer.php');
?>
<script>
$("#checkAll").click(function () {
    $(".check").prop('checked', $(this).prop('checked'));
});
</script>