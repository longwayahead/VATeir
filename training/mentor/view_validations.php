<?php
$pagetitle = 'View Validations';
require_once('../includes/header.php');
if(!isset($_GET['cid'])) {
	Session::flash('error', 'You haven\'t supplied a VATSIM CID');
	Redirect::to('../mentor/');
}
try {
	$data = $t->getStudent($_GET['cid']);
	if(!$data) {
		Session::flash('error', 'No student found for that CID');
		Redirect::to('../mentor/');
	}
	if(!$user->hasPermission($data->program_permissions)) {
		Session::flash('error', 'Insufficient permissions');
		Redirect::to('./');
	}
} catch (Exception $e) {
	echo $e->getMessage();
}

try {
	echo '<h3 class="text-center">' . $data->first_name . '\'s Validations</h3><br>';
	$validations = $t->fetchAllValidations($data->cid);
	// echo '<pre>';
	// print_r($validations);
	// echo '</pre>';
	// exit();
	echo '<div class="col-md-8">';
		if($validations) {
		echo '<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Validation List</h3>
				</div>
				<div class="panel-body">
				<table class="table">
					<tr>
						<td><strong>Position</strong></td>
						<td><strong>Issued By</strong></td>
						<td><strong>From</strong></td>
						<td><strong>Expires</strong></td>';
						if($user->hasPermission("tdstaff")) {
							echo '<td><strong></strong></td>
									<td><strong></strong></td>';
						}
						
						foreach($validations as $validation) {
						
							echo '<tr';
								if($validation->approved == 0) {
									echo ' class="warning"';
								}
							echo '>
								<td>' . $validation->callsign . '</td>
								<td>' . $validation->mentor_fname . ' ' . $validation->mentor_lname . '</td>
								<td>' . date("j-M-Y", strtotime($validation->valid_from)) . '</td>
								<td>' . date("j-M-Y", strtotime($validation->valid_until)) . '</td>';
								if($user->hasPermission("tdstaff")) {
									echo '<td><a href="./validate.php?t=r&cid=' . $validation->cid . '&pos=' . $validation->position_id . '" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></a></td>
											<td><a href="./validate.php?t=d&cid=' . $validation->cid . '&pos=' . $validation->position_id . '" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></td>';
								}
								
								echo '</tr>';
						}
				echo '
					</table>
				</div>
			</div>';

			
		} else {
			echo 'you have no validations';
		}
	echo '</div>
		<div class="col-md-4">';
		echo '	<div class="panel panel-default">
				  <div class="panel-heading">
				    <h3 class="panel-title">Validate up to...</h3>
				  </div>
				  <div class="panel-body">';
				  	$airports = $t->getAirports();
				  	if(count($airports)) {
				  		foreach($airports as $airport) {
				  			echo $airport->name . '<br>';
				  			$sectorTypes = $t->getSectorTypes($airport->icao); //at the major
				  			foreach($sectorTypes as $sectorType => $call) {
				  				echo '<a class="btn btn-xs btn-default" href="validate.php?t=s&cid=' . $data->cid . '&icao=' . $airport->icao . '&sector=' . $sectorType . '">' .  $call . '</a>  ';
				  			}
				  			echo '<br>';
				  		}
				  	}
				  	
				  echo '
				  </div>
				</div>';

		// echo '	<div class="panel panel-primary">
		// 		  <div class="panel-heading">
		// 		    <h3 class="panel-title">Validate on...</h3>
		// 		  </div>
		// 		  <div class="panel-body">';
		// 		  	if(count($airports)) {
		// 		  		foreach($airports as $airport) {
		// 		  			echo $airport->name . '<br>';
		// 		  			$positions = $t->getPositionsICAO($airport->icao);
		// 		  			foreach($positions as $position) {
		// 		  				echo '<a class="btn btn-xs btn-default" href="validate.php?t=p&cid=' . $data->cid . '&pos=' . $position->position_id . '">' . $position->callsign . '</a><br>';
		// 		  			}
		// 		  		}
		// 		  	}
				  	
		// 		  echo '
		// 		  </div>
		// 		</div>';



	echo '</div>'; //col-md-4	
	echo '</div>';
} catch(Exception $e) {
	echo $e->getMessage();
}
require_once("../../includes/footer.php");
?>