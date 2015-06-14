<?php
$pagetitle = "Validation List";
require_once("../includes/header.php");

echo '<div class="col-md-10 col-md-offset-1">';
	echo '<h3 class="text-center">Validation List';
	echo ((!isset($_GET['v'])) || ($_GET['v']) == '0') ? ' <small>Sector View</small>' : ' <small>Positions View</small>';
	echo '</h3>';

	echo '<div class="text-right">';
	echo ((!isset($_GET['v'])) || ($_GET['v']) == '0') ? '<a href="validation_list.php?v=1">Position View</a>' : '<a href="validation_list.php?v=0">Sectors View</a>';
	echo '</div>';


	if((!isset($_GET['v'])) || ($_GET['v'] == '0')) {

		try {
			$majors = $t->getAirports();
			if(!$majors) {
				echo 'No major airports available';
			} else {


				foreach($majors as $major) {
					echo '<h4>' . $major->name . '</h4>';
					$sectors = $t->getSectorTypes($major->icao);
					if($sectors) {
						foreach($sectors as $sector => $call) {
							$students = array();
							$not = array();
							$check = array();
							$vals = $t->getValidatedSector($major->icao, $sector); //get list of validations by sector
							// echo '<pre>';
							// print_r($vals);
							// echo '</pre>';
							if($vals) {
								foreach($vals as $val) {
									if(($t->maxValidatedSectorType($major->icao, $val->cid)->position_type_id == $val->position_type_id)) { //Makes sure the sector type is the highest for this cid and icao
										$not[] = $val->cid;
										if(!in_array($val->cid, $students)) {
											$students[] = $val->cid;
										}//if!inarray							
									}//if$t					
								}//vals loop 34
								if((count($not)) && (count($students))) {
								
									echo '<div class="panel panel-primary">
											<div class="panel-heading">
												<h3 class="panel-title">' . $major->icao . '_' . $call;
												if($call !== "DEL") {
													echo ' (and below)';
												}
												echo '</h3>
											</div>
										<div class="panel-body" style="padding:0px;">
											<table class="table table-responsive table-condensed table-hover table-striped">
												<tr>
													<td><strong>Controller</strong></td>
													<td><strong>Issued By</strong></td>
													<td><strong>Validated Since</strong></td>
													<td><strong>Validated Until</strong></td>
												</tr>';
								
									foreach($vals as $val) {
										if(($t->maxValidatedSectorType($major->icao, $val->cid)->position_type_id == $val->position_type_id)) { //Makes sure the sector type is the highest for this cid and icao
											if(in_array($val->cid, $students)){
												if(!in_array($val->cid, $check)) {
													$check[] = $val->cid;
													echo '<tr>
														<td><a href="view_validations.php?cid=' . $val->cid . '">' . $val->student_fname . ' ' . $val->student_lname . '</a></td>
														<td>' . $val->mentor_fname . ' ' . $val->mentor_lname . '</td>
														<td>' . date("j-M-Y", strtotime($val->valid_from)) . '</td>
														<td>' . date("j-M-Y", strtotime($val->valid_until)) . '</td>
													</tr>';
												}
												

												 
											}
										}
									}
									echo '</table>';
									echo '</div>';	
									echo'</div>';
								} //if !counts

							} //no one validated
						}//sector loop
				
					}//if!sectors

					

				}//end major loop
	echo'</div>';
			}
			
		} catch (Exception $e) {
			echo $e->getMessage();
		}


	} else {

		try {
			$majors = $t->getAirports();
			if(!$majors) {
				echo 'No major airports available';
			} else {
				foreach($majors as $major) {
					echo '<h4>' . $major->name . '</h4>';
					$positions = $t->getPositionsICAO($major->icao);

					if(!$positions) {
						echo 'No positions available for that ICAO';
					} else {
						foreach($positions as $position) {
							$vals = $t->getValidatedPosition($position->position_id);
							if($vals) {
								echo '<div class="panel panel-primary">
											<div class="panel-heading">
												<h3 class="panel-title">' . $position->callsign . '</h3>
											</div>
											<div class="panel-body" style="padding:0px;">
												<table class="table table-responsive table-condensed table-hover table-striped">
													<tr>
														<td><strong>Controller</strong></td>
														<td><strong>Issued By</strong></td>
														<td><strong>Validated From</strong></td>
														<td><strong>Validated Until</strong></td>
													</tr>';
											foreach($vals as $val) {
												echo '<tr>
														<td><a href="view_validations.php?cid=' . $val->cid . '">' . $val->student_fname . ' ' . $val->student_lname . '</a></td>
														<td>' . $val->mentor_fname . ' ' . $val->mentor_lname . '</td>
														<td>' . date("j-M-Y", strtotime($val->valid_from)) . '</td>
														<td>' . date("j-M-Y", strtotime($val->valid_until)) . '</td>
													</tr>';
											}
											echo '</table>
												</div>
												
											</div>';
								
							}
						}						
					}
				}
			}
			
	} catch (Exception $e) {
		echo $e->getMessage();
	}
}
echo '</div>';

require_once("../../includes/footer.php");
?>