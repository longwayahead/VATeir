<?php
require_once("../includes/header.php");
// if(!$user->isLoggedIn() && !$user->hasPermission("admin")) {
// 	Session::flash("error", "Invalid permissions");
// 	Redirect::to("../index.php");
// }

$t = new Training;


////CONNECTING TO VATEUD API////
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, Config::get('vateud/vaccmembers')); //Set the URL here
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLINFO_HEADER_OUT, true); //Set a custom header for auth token


curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	'Authorization: Token token="' . Config::get('vateud/apitoken') . '"'
));


$get = curl_exec($curl); //Get the data from vateud
curl_close($curl);

////CONNECTION CLOSED////

$controllers = json_decode($get);
$register = array();
// echo '<pre>', print_r($controllers), '</pre>';
foreach($controllers as $controller) {	//Register users if they aren't already in the VATeir database.
	try {
		if(!$user->find($controller->cid)) { //CID not already in database. New member - register them!

			if($controller->rating > 7) {
				$rating = $user->getRealRating($controller->cid);
			} else {
				$rating = $controller->rating;
			}

			$pilotRating = $t->pilotRating($controller->pilot_rating);
			$make = $user->create(array(
				'id' 				=> $controller->cid,
				'first_name' 		=> $controller->firstname,
				'last_name' 		=> $controller->lastname,
				'email' 			=> $controller->email,
				'rating' 			=> $rating,
				'pilot_rating' 		=> $controller->pilot_rating,
				'pratingstring'		=> $pilotRating,
				'regdate_vatsim' 	=> date("Y-m-d H:i:s", strtotime($controller->reg_date)),
				'regdate_vateir' 	=> date('Y-m-d H:i:s'),
				'vateir_status'		=> 1
			));
			
			if(!$t->findStudent($controller->cid)) {
				$program = $t->program($rating);
				$studentMake = $t->createStudent(array(
					'cid'		=> $controller->cid,
					'program'	=> 	$program
				));
			}
			
			$register["registered"][] = $controller->cid;
		}
	} catch (Exception $e) {
		echo $e->getMessage();
		$register["regfail"][$controller->cid] = $e->getMessage();
	}


/////UPDATING DATA FOR USERS THAT ARE ACTIVE//////


	if(empty($register["registered"]) || !in_array($controller->cid, $register["registered"])) { //CID hasn't just registered above...or no one has registered at all
		try { //update active user's data
			if($user->isActive($controller->cid)) { //check if the user is active
				if($controller->rating > 7) {
					$rating = $user->getRealRating($controller->cid);
				} else {
					$rating = $controller->rating;
				}
				$pilotRating = $t->pilotRating($controller->pilot_rating);
				$update = $user->update(array(
					'controllers.first_name'	=> $controller->firstname,
					'controllers.last_name'		=> $controller->lastname,
					'controllers.email' 		=> $controller->email,
					'controllers.rating'		=> $rating,
					'controllers.pilot_rating' 	=> $controller->pilot_rating,
					'controllers.pratingstring'	=> $pilotRating,
					'controllers.vateir_status' => 1
				), [['id', '=', $controller->cid]]);

				$program = $t->program($rating);

				$studentUpdate = $t->updateStudent(array(
					'program'	=> 	$program
				), [['cid', '=', $controller->cid]]);

				$register["updated"][] = $controller->cid;
			} elseif($user->isAlive($controller->cid) && !$user->isActive($controller->cid)) { //Gets all "active" users, checks to make sure they're active and for those that aren't, changes their status to 0
				$update = $user->update(array(
					'alive' => 0
				), [['id', '=', $controller->cid]]);
				$register["setasinactive"][] = $controller->cid;
			}
		} catch (Exception $f) {
			echo $f->getMessage();
			$register["updatefail"][$controller->cid] = $f->getMessage();
		}	
			
	}
	


}
print_r($register);
require_once("../includes/footer.php");