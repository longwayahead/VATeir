<?php
require_once("includes/header.php");

$t = new Training;
$user = new User;


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
$listCID = array();
//echo '<pre>', print_r($controllers), '</pre>';
foreach($controllers as $controller) {	//Register users if they aren't already in the VATeir database.
	$listCID[] = $controller->cid;
	try {
		if(!$user->find($controller->cid)) { //CID not already in database. New member - register them!

			if($controller->rating > 7) {
				$rating = $user->getRealRating($controller->cid);
			} else {
				$rating = $controller->rating;
			}
			$pilotRating = $t->pilotRating($controller->pilot_rating);
			$make = $user->create(array(			//
				'id' 				=> $controller->cid,			//
				'first_name' 		=> $controller->firstname,			//
				'last_name' 		=> $controller->lastname,			//
				'email' 			=> $controller->email,			//
				'rating' 			=> $rating,			//
				'pilot_rating' 		=> $controller->pilot_rating,			//
				'pratingstring'		=> $controller->humanized_pilot_rating,			//
				'regdate_vatsim' 	=> date("Y-m-d H:i:s", strtotime($controller->reg_date)),			//
				'regdate_vateir' 	=> date('Y-m-d H:i:s'),			//
				'vateir_status'		=> 1			//
			));
			if(!$t->findStudent($controller->cid)) {
				$program = $t->program($rating);
				$studentMake = $t->createStudent(array(				//
					'cid'		=> $controller->cid,				//
					'program'	=> 	$program				//
				));
			}

			$register["registered"][] = $controller->cid;
		} else { //status of user's account has changed since first created
			// if($controller->rating > 7) {
			// 	$rating = $user->getRealRating($controller->cid);
			// } else {
			// 	$rating = $controller->rating;
			// }
			//echo $controller->cid;
			// $user->update([
			// 		'alive' 		=> 1,
			// 		'vateir_status' => 1,
			// 	], [['id', '=', $controller->cid]]);
			// if(!$t->findStudent($controller->cid)) {
			// 	$program = $t->program($rating);
			// 	$studentMake = $t->createStudent(array(				//
			// 		'cid'		=> $controller->cid,				//
			// 		'program'	=> 	$program				//
			// 	));
			// }
		}
	} catch (Exception $e) {
		echo $e->getMessage();
		$register["regfail"][$controller->cid] = $e->getMessage();
	}


/////UPDATING DATA FOR USERS THAT ARE ACTIVE//////


	if(empty($register["registered"]) || !in_array($controller->cid, $register["registered"])) { //CID hasn't just registered above...or no one has registered at all
		try { //update active user's data
			if($data = $user->isActive($controller->cid)) { //check if the user is active
				$change = [];

				if($data->first_name != $controller->firstname) {
					$change['first_name'] = $controller->firstname;
				}
				if($data->last_name != $controller->lastname) {
					$change['last_name'] = $controller->lastname;
				}
				if($data->email != $controller->email) {
					$change['email'] = $controller->email;
				}

				//get real rating
				if($controller->rating > 7) {
					$rating = $user->getRealRating($controller->cid);
				} else {
					$rating = $controller->rating;
				}
				//end get real rating


				if($data->rating != $rating) {
					$change['rating'] = $rating;
				}

				$programChange = false;
				if($data->rating != $rating) {
					$programChange = true;
				}
				if($data->pilot_rating != $controller->pilot_rating) {
					$change['pilot_rating'] = $controller->pilot_rating;
					$change['pratingstring'] = $controller->humanized_pilot_rating;
				}
				if($data->vateir_status != 1) {
					$change['vateir_status'] = 1;
				}
				if(!empty($change)) {
					print_r($change);
				}

				if(!empty($change)) {
					$update = $user->update(
						$change
					, [['id', '=', $controller->cid]]);
					if($programChange === true) { //only change the student's programme if their rating has changed. new rating = new training programme.
						$program = $t->program($rating);

						$studentUpdate = $t->updateStudent(array(
							'program'	=> 	$program
						), [['cid', '=', $controller->cid]]);
					}
					$register["updated"][$controller->cid] = $change;
				}

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

unset($change);

}
//print_r($listCID);


$crons = new Crons;//
try{
	// $select = $crons->deleteNonVATeir($listCID);
	// echo '<pre>';
	// print_r($select);
	// echo '</pre>';
	// $register["deleted"] = $listCID;


	 $json = (!empty($register)) ? json_encode($register) : '';

	// $crons->add([	'date' => date("Y-m-d H:i:s"),//
	// 				'data' => $json//
	// 			]);
}catch(Exception $q) {
	$register["deletefail"] = $q->getMessage();
}

