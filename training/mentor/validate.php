<?php
$pagetitle = 'Validate Controller';
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
	if(!$user->hasPermission('mentor') || !$user->hasPermission($data->program_permissions)) {
			Session::flash('error', 'Cannot validate at that level');
			Redirect::to('./');
		}
} catch (Exception $e) {
	echo $e->getMessage();
}

try {
	$duration = 12;
	$from = date('Y-m-d');
	$until = date('Y-m-d', strtotime("+" . $duration . " weeks", strtotime($from)));
	if(isset($_GET['t']) && ((isset($_GET['icao']) && isset($_GET['sector'])) || isset($_GET['pos'])) && isset($_GET['cid'])) {
		if($_GET['t'] == 's') { //validate on an airport/sector 
			$positions = $t->posUpto($_GET['icao'], $_GET['sector']);
			if($positions) {
				foreach($positions as $position) {
					$isValidated = $t->isValidated($position->position_id, $data->cid);
					
					if(!$isValidated) { //if not already validated
						$act = $t->validate(array(
							'position_list_id'	=> $position->position_id,
							'cid'				=> $data->cid,
							'issued_by'			=> $user->data()->id,
							'valid_from'		=> $from,
							'valid_until'		=> $until
						));	
					} else {
						if($user->hasPermission("tdstaff")) {
							$act = $t->updateValidation(array(
							'valid_until' => $until
							),
								[
									['position_list_id', '=', $position->position_id],
									['cid', '=', $data->cid]
								]);
						}
					}
				} //end foreach through positions
				if($above = $t->posAbove($_GET['icao'], $_GET['sector'])) { //if the position is part of a higher sector than that sector that is being validated...
					if($user->hasPermission("tdstaff")) {
						foreach($above as $a) {
							$act = $t->deleteVals($a->position_id, $_GET['cid']);
						}
					}
				}
					
					Session::flash("success", "Validations added!");
					Redirect::to('view_validations.php?cid=' . $_GET["cid"]);
				
			} else { //Checks to make sure the positions are part of an icao that accepts validations.
				Session::flash("error", "Cannot add validations for that ICAO.");
				Redirect::to('view_validations.php?cid=' . $_GET["cid"]);
			}
		
		} elseif($_GET['t'] == 'p') { //validate on a position
			$position = $t->getPositionsID($_GET['pos']);
			if($position) {
				if(!$t->isValidated($position->position_id, $data->cid)) {
					$add = $t->validate(array(
							'position_list_id'	=> $position->position_id,
							'cid'				=> $data->cid,
							'issued_by'			=> $user->data()->id,
							'valid_from'		=> $from,
							'valid_until'		=> $until
						));
				} else {
					if($user->hasPermission("tdstaff")) {
						$renew = $t->updateValidation(array(
								'valid_until' => $until
								),
								[
									['position_list_id', '=', $position->position_id],
									['cid', '=', $data->cid]
								]);
					}
				}
					
					Session::flash("success", "Validation added!");
					Redirect::to('view_validations.php?cid=' . $_GET["cid"]); 

			} else { //not a validatable position
				Session::flash("error", "Cannot add validation for that position.");
				Redirect::to('view_validations.php?cid=' . $_GET["cid"]);
			}
		} elseif($_GET['t'] == 'r') { //renew validation
			if($user->hasPermission("tdstaff")) {
				$position = $t->getPositionsID($_GET['pos']);
				$update = $t->updateValidation(array(
				'valid_until' => $until
				),
					[
						['position_list_id', '=', $position->position_id],
						['cid', '=', $data->cid]
					]);
				$t->generateValFile();
				

				Session::flash("success", "Validation extended!");
				
			} else {
				Session::flash("error", "Insufficient Permissions!");
				
			}
			Redirect::to('view_validations.php?cid=' . $_GET["cid"]);
		} elseif($_GET['t'] == 'd') {
			if($user->hasPermission("tdstaff")) {
				if($t->deleteVals($_GET['pos'], $_GET['cid'])) {
				Session::flash("success", "Validation deleted!");
				}
			} else {
				Session::flash("error", "Insufficient Permissions!");
			}
			Redirect::to('view_validations.php?cid=' . $_GET["cid"]);
		}
	}
} catch(Exception $f) {
	echo $f->getMessage();
}