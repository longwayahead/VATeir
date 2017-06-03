<?php
require_once("../includes/header.php");
if(isset($_POST["data"])) {
	$data = unserialize($_POST["data"]);
	$t = new Training;
	$isAllowed = $t->getVisCID($data->id);
		if($isAllowed == false) { //If they are not in the CIDs table
			Session::flash("error", "Cannot access resource!");
			Redirect::to("../index.php");
		} else {
			if(isset($_POST["visiting"])) { //visiting request
				if($data->rating->id > 7) { //get the CID's real rating (instead of SUP/ADM/INS etc)
					$rating = $user->getRealRating($data->id);
				} else {
					$rating = $data->rating->id;
				}
				$pilotRating = $t->pilotRating($data->pilot_rating->rating);
			try{
				$try = $user->create([
					'id' 				=> $data->id,
					'first_name' 		=> $data->name_first,
					'last_name' 		=> $data->name_last,
					'email' 			=> $data->email,
					'rating' 			=> $rating,
					'pilot_rating' 		=> $data->pilot_rating->rating,
					'pratingstring'		=> $pilotRating,
					'regdate_vatsim' 	=> date("Y-m-d H:i:s", strtotime($data->reg_date)),
					'regdate_vateir' 	=> date('Y-m-d H:i:s'),
					'vateir_status'		=> 2
				]);
				if(!$t->findStudent(Input::get('id'))) {
					$program = $t->program($rating);
					$studentMake = $t->createStudent(array(
						'cid'		=> $data->id,
						'program'	=> 	$program
					));
				}
				$t->deleteVisitingCID($data->id);
				Session::flash("success", "Your account has been created! Please log in.");
				Redirect::to("../index.php");
			} catch(Exception $f) {
				echo $f->getMessage();
			}
		}
	}
}
