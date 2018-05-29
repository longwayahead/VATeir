<?php
class Training {
	private $_db,
			$_count = null,
			$_data = array(),
			$_sql = "SELECT `s`.`cid`, `s`.`program`,
					`c`.`id`, `c`.`pratingstring`, `c`.`first_name`, `c`.`last_name`, `c`.`email`, `c`.`rating`, `c`.`pilot_rating`, `c`.`vateir_status`, `c`.`alive`, `c`.`regdate_vatsim`, `c`.`regdate_vateir`, `c`.`grou`, `c`.`adminPerm`,
					`p`.`id`, `p`.`name`, `p`.`sort`, `p`.`permissions` as `program_permissions`,
					`r`.`id`, `r`.`long`, `r`.`short`,
					`v`.`id`, `v`.`status`
					FROM `students` AS `s`
					LEFT JOIN `controllers` AS `c` ON `c`.`id` = `s`.`cid`
					LEFT JOIN `programs` AS `p` ON `p`.`id` = `s`.`program`
					LEFT JOIN `ratings` AS `r` ON `r`.`id` = `c`.`rating`
					LEFT JOIN `vateir_status` AS `v` ON `v`.`id` = `c`.`vateir_status`",
			$_pos = "SELECT `p`.`id` AS `position_id`, `p`.`position_type_id`, `p`.`airport_list_id`, `p`.`callsign`, `p`.`freq`, `p`.`name` AS `position_name`,
					`t`.`id`, `t`.`ident`, `t`.`name_short`, `t`.`name_long`, `t`.`sort`,
					`a`.`id`, `a`.`icao`, `a`.`name` AS `airport_name`, `a`.`major`,
					`av`.`id`, `av`.`airport_list_id`
					FROM `position_list` AS `p`
					LEFT JOIN `position_type` AS `t` ON `t`.`id` = `p`.`position_type_id`
					LEFT JOIN `airport_list` AS `a` ON `a`.`id` = `p`.`airport_list_id`
					RIGHT JOIN `airport_validation` AS `av` ON `av`.`airport_list_id` = `a`.`id`",
			$_val = "SELECT `p`.`id` AS `position_id`, `p`.`position_type_id`, `p`.`airport_list_id`, `p`.`callsign`, `p`.`freq`, `p`.`name` AS `position_name`,
					`t`.`id`, `t`.`ident`, `t`.`name_short`, `t`.`name_long`, `t`.`sort`,
					`a`.`id`, `a`.`icao`, `a`.`name` AS `airport_name`, `a`.`major`,
					`v`.`id` AS `valid`, `v`.`position_list_id`, `v`.`cid` AS `cid`, `v`.`issued_by`, `v`.`valid_from`, `v`.`valid_until`, `v`.`approved`,
					`c`.`id`, `c`.`pratingstring`, `c`.`first_name` AS `student_fname`, `c`.`last_name` AS `student_lname`, `c`.`alive`,
					`m`.`id`, `m`.`first_name` AS `mentor_fname`, `m`.`last_name` AS `mentor_lname`
					FROM `position_list` AS `p`
					LEFT JOIN `position_type` AS `t` ON `t`.`id` = `p`.`position_type_id`
					LEFT JOIN `airport_list` AS `a` ON `a`.`id` = `p`.`airport_list_id`
					RIGHT JOIN `validation_list` AS `v` ON `v`.`position_list_id` = `p`.`id`
					LEFT JOIN `controllers` AS `c` ON `c`.`id` = `v`.`cid`
					LEFT JOIN `controllers` AS `m` ON `m`.`id` = `v`.`issued_by`";

	public	$pilotRatings = array(
						1 => "P1",
						2 => "P2",
						4 => "P3",
						8 => "P4",
						16 => "P5",
						32 => "P6",
						64 => "P7",
						128 => "P8",
						256 => "P9"
					),
			$position_types = array(),
			$positions = null,
			$count = "",
			$count2 = [];

	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function getPrograms($where = null) {
		if(!isset($where)) {
			 $where = "WHERE name != 'completed'";
		} else {
			$where = null;
		}
		$data = $this->_db->query("SELECT * FROM programs $where ORDER BY sort ASC");
		if($data->count()) {
			$this->_data = $data->results();
			return $this->_data;
		}
		return false;
	}

	public function getStudentsProgram($pid) {
		$data = $this->_db->db("$this->_sql", [['`p`.`id`', '=', $pid], ['`c`.`alive`', '=', 1], ['`c`.`rating`', '>', 0]], "ORDER BY `c`.`rating` ASC, `c`.`last_name` ASC"
							);
		if($data->count()) {

			return $data->results();
		}
		return false;
	}



	public function getStudents($param = null) {
		if(!empty($param)) {
			//$data = $this->_db->query("$this->_sql", [["{$s}", 'LIKE', "%{$param}%"]], "ORDER BY `c`.`rating` DESC, `c`.`last_name` ASC"
			$data = $this->_db->query("$this->_sql
							WHERE	CONCAT(`c`.`first_name`, ' ', `c`.`last_name`) LIKE ?
								OR`c`.`id` LIKE ?
								OR `c`.`email` LIKE ?
							ORDER BY `c`.`rating` DESC,
							`c`.`last_name` ASC",
						[
							["%{$param}%"],
							["%{$param}%"],
							["%{$param}%"]

						]
					);
		} else {
			$data = $this->_db->query("$this->_sql WHERE `c`.`rating` > 0 AND `p`.`name` <> 'completed' AND `c`.`alive` = '1' ORDER BY `c`.`rating` DESC, `c`.`last_name` ASC"
					);
		}
		if($data->count()) {

			return $data->results();
		}
		return false;
	}

	public function getStudent($cid) {
		$data = $this->_db->db("$this->_sql", [['`s`.`cid`', '=', $cid]]);
		if($data->count()) {
			return $data->first();
		}
		return false;
	}

	public function assignProgram($cid, $rating) {
		if(!$this->findStudent($cid)) {
			$program = $this->program($rating);
			$studentMake = $this->createStudent(array(
				'cid'		=> $cid,
				'program'	=> 	$program,
			));
			return true;
		} else {
			return false;
		}
	}

	public function fixStudents() {
		$data = $this->_db->query("SELECT c.id, c.rating FROM controllers c LEFT JOIN students s ON c.id = s.cid WHERE s.cid IS NULL AND c.id <> 0 and c.vateir_status <> 3 AND c.vateir_status <> 4");
		if($data->count()) {
			$update = [];
			foreach($data->results() as $d) {
				$assign = $this->assignProgram($d->id, $d->rating);
				if($assign == true) {
					$update['success'][] = $d->id;
				} else {
					$update['fail'][] = $d->id;
				}

			}
			return $update;
		} else {
			return false;
		}
	}

	public function getAirports($major = true) {
		$data = $this->_db->query("SELECT `a`.`id`, `a`.`icao`, `a`.`major`, `a`.`name`,
									`av`.`id`, `av`.`airport_list_id`
						FROM `airport_list` AS `a`
						RIGHT JOIN `airport_validation` AS `av` ON `av`.`airport_list_id` = `a`.`id`
						ORDER BY `a`.`icao` ASC");

		if($data->count()) {
			$this->_data = $data->results();
			return $this->_data;
		}
		throw new Exception("Problem getting major airport data");
		return false;
	}

	public function getPositionsICAO($icao) {
		$data = $this->_db->db("$this->_pos", [['`a`.`icao`', '=', $icao]], "ORDER BY `t`.`id` ASC, `p`.`callsign` ASC");
		if($data->count()) {
			$this->_data = $data->results();
			return $this->_data;
		} else {
			return false;
		}
		throw new Exception("Problem getting position data");
	}

	public function getPositionsID($id) {
		$data = $this->_db->db("$this->_pos", [['`p`.`id`', '=', $id]]);
		if($data->count()) {
			$this->_data = $data->first();
			return $this->_data;
		} else {
			return false;
		}
	}

	public function posUpto($icao, $sector) { //positions up to sector id
		$data = $this->_db->db("$this->_pos", [['`a`.`icao`', '=', $icao], ['position_type_id', '<=', $sector]]);
		if($data->count()) {
			$this->_data = $data->results();
			return $this->_data;
		} else {
			return false;
		}
		throw new Exception("Problem getting position data");
	}

	public function posAbove($icao, $sector) { //positions > a sector id
		$data = $this->_db->db("$this->_pos", [['`a`.`icao`', '=', $icao], ['position_type_id', '>', $sector]]);
		if($data->count()) {
			$this->_data = $data->results();
			return $this->_data;
		} else {
			return false;
		}
		throw new Exception("Problem getting position data");
	}

	public function bookings() {
		cacheFile(URL.'datafiles/bookings.xml', 'http://vatbook.euroutepro.com/xml.php?fir=EISN');
		$xml = new SimpleXMLElement(file_get_contents(URL.'datafiles/bookings.xml'));
		$i = 0;

		foreach($xml->atc as $x) {
			$bookings[$i]['callsign'] = $x->callsign;
			$bookings[$i]['name'] = $x->name;
			$bookings[$i]['time_start'] = $x->time_start;
			$bookings[$i]['time_end'] = $x->time_end;
			$bookings[$i]['cid'] = $x->cid;
			$bookings[$i]['added'] = $x->added;
			$i++;
		}
		return $xml;
	}

	public function userBookings($cid) {
		$bookings = $this->bookings();
		foreach($bookings->atc as $booking) {
			if($booking->cid == $cid) {
				$book[] = $booking;
			}
		}
		if(isset($book)) {
			if(count($book)) {
				return $book;
			}
		}

		return false;
	}


	public function getSectorTypes($airport) {
		$positions = $this->positions;
		$pos_types = $this->position_types;//reset values
		$positions = $this->getPositionsICAO($airport);//get positions at an icao
		if($positions) {
			foreach($positions as $position) {//loop through the positions
				if(!in_array($position->position_type_id, $pos_types)) {
					$pos_types[$position->position_type_id] = $position->ident;
				}
			}
			return $pos_types;
		}
		return false;
	}

	public function getControllers($options = array()) {

		if(isset($options['active'])) {
				$where = "controllers.alive = 1";

		} else {
			$where = "controllers.alive = 0 AND controllers.rating > 0";
		}
		if(isset($options['status'])) {
				$where .= " AND (controllers.vateir_status <= 2)";

		}
		$controllers = $this->_db->query("SELECT controllers.id as cid, controllers.first_name, controllers.last_name, controllers.rating, controllers.pilot_rating, controllers.pratingstring, controllers.vateir_status, controllers.email, controllers.alive, controllers.regdate_vatsim, controllers.regdate_vateir,
										ratings.id, ratings.short, ratings.long
										FROM controllers
										LEFT JOIN ratings ON ratings.id = controllers.rating
										WHERE $where
									
										ORDER BY controllers.rating DESC, controllers.last_name ASC");
		//print_r($controllers);
		if($controllers->count()) {
			return $controllers->results();
		}
	}

	public function validate($fields = array()) {
		if(!$this->_db->insert('validation_list', $fields)) {
			throw new Exception('There was a problem adding a validation.');
		}
	}

	public function generateValFile() {
		$validations = $this->fetchAllValidations(1, '`v`.`approved`');
		if(count($validations)) {
			$file = fopen(Config::get("vatsim/valpath"), "w") or die("Can't open the validation .txt file");
			foreach($validations as $validation) {
				$line = $validation->cid . ";" . $validation->callsign . ";" . date('Y-m-d', strtotime($validation->valid_from)) . ";" . date('Y-m-d', strtotime($validation->valid_until)) . ";" . $validation->issued_by . "\n";
				fwrite($file, $line);
			}
			fclose($file);
			return true;
		}
		return false;
	}
	public function updateValidation($fields = array(), $where) {
		if(!$this->_db->update('validation_list', $fields, $where)) {
			throw new Exception('There was a problem updating.');
		}
	}

	public function getValidatedSector($airport, $sector) {
		$data = $this->_db->query("$this->_val
			WHERE `a`.`icao` = '{$airport}' AND `t`.`id` = {$sector}
			ORDER BY `t`.`sort` ASC, `v`.`valid_until` ASC");
		if($data->count()) {
			$this->_data = $data->results();
			return $this->_data;
		}
		return false;
	}

	public function oneMonth() {
		$data = $this->_db->query("$this->_val WHERE `v`.`valid_until` < DATE_ADD(now(), INTERVAL 1 WEEK) ORDER BY `v`.`valid_until` ASC");
		if($data->count()) {

			return $data->results();
		}
		return false;
	}

	public function getValidatedPositions($airport, $cid) {
		$data = $this->_db->db("$this->_val", [['`a`.`icao`', '=', $airport], ['`v`.`cid`', '=', $cid], ['`v`.`approved`', '=', '1']]);
		if($data->count()) {
			$this->_data = $data->results();
			return $this->_data;
		}
		return false;
	}

	public function isValidated($posid, $cid) {
		$data = $this->_db->db("$this->_val", [['`v`.`position_list_id`', '=', $posid], ['`v`.`cid`', '=', $cid]]);
		if($data->count()) {
			$this->_data = $data->first();
			return $this->_data;
		}
		return false;
	}

	public function deleteVals($posid, $cid) {
		$delete = $this->_db->delete('validation_list', [['cid', '=', $cid], ['position_list_id', '=', $posid]]);
		if($delete) {
			return true;
		} else {
			throw new Exception("There was a problem deleting a validation");
		}
	}

	public function deleteByValID($id) {
		$delete = $this->_db->delete('validation_list', [['id', '=', $id]]);
		if($delete) {
			return true;
		} else {
			throw new Exception("There was a problem deleting a validation");
		}
	}


	public function maxValidatedSectorType($icao, $cid) {
		$data=$this->_db->query("SELECT MAX(`p`.`position_type_id`) AS `position_type_id`, `p`.`airport_list_id`, `p`.`id` AS `positionid`,
			`a`.`id`, `a`.`icao`,
			`v`.`id`, `v`.`cid`, `v`.`position_list_id`, `v`.`approved`
			FROM `position_list` AS `p`
			LEFT JOIN `airport_list` AS `a` ON `a`.`id` = `p`.`airport_list_id`
			LEFT JOIN `validation_list` AS `v` ON `v`.`position_list_id` = `p`.`id`
			WHERE `a`.`icao` = ? AND `v`.`cid` = ? AND `v`.`approved` = 1", [[$icao, $cid]]);
		return $data->first();
	}

	public function showSector($icao, $cid) {
		$data=$this->_db->query("$this->_val WHERE `a`.`icao` = '{$airport}' AND `t`.`id` = {$sector} AND `t`.`id` <>  ORDER BY `t`.`sort` ASC, `v`.`valid_until` ASC");
		return $data->results();
	}

	public function maxValidatedSectorCID($airport, $cid) {
		$data = $this->_db->db("$this->_val", [['`a`.`icao`', '=', $airport], ['`v`.`cid`', '=', $cid]], "ORDER BY `t`.`sort` DESC LIMIT 1");
		if($data->count()) {
			$this->_data = $data->first();
			return $this->_data;
		}
		return false;
	}

	public function fetchAllValidations($search = null, $param = '`c`.`id`', $cid = null, $bits = null) {
		if(isset($cid)) {
			$cidsearch = " AND `v`.`cid` = $cid";
		} else {
			$cidsearch=null;
		}
		if(isset($search)) {
			$data = $this->_db->query("$this->_val WHERE $param = $search $cidsearch ORDER BY `v`.`valid_until` DESC, `t`.`sort` ASC, `p`.`callsign` ASC $bits");
		} else {
			$data = $this->_db->query("$this->_val ORDER BY `v`.`valid_from` DESC $bits");
		}
		if($data->count()) {
			$this->count2[1] = $data->count();
			return $data->results();
		}
		return false;
	}


	public function countVals() {
		$this->fetchAllValidations(0, '`v`.`approved`');
		return $this->_count;
	}

	public function getValidatedPosition($posid) {
		$data = $this->_db->db("$this->_val", [['`p`.`id`', '=', $posid], ['`v`.`approved`', '=', '1']], "ORDER BY `t`.`sort` DESC, `p`.`callsign` ASC");

		if($data->count()) {
			$this->_data = $data->results();
			return $this->_data;
		} else {
			return false;
		}
		throw new Exception("Problem getting position data");
	}
	public function getVisCID($cid) {
		$query = $this->_db->query("SELECT cid FROM visitingCIDs WHERE cid = ?", [[$cid]]);
		if($query->count()) {
			return true;
		}
		return false;
	}

	public function getVisitingCIDs() {

		$cids = $this->_db->query("SELECT * FROM visitingCIDs");
		if($cids->count()) {
			return $cids->results();
		}
		return false;
	}

	public function addVisitingCID($fields = array()) {
		if(!$this->_db->insert('visitingCIDs', $fields)) {
			throw new Exception('There was a problem adding an visiting controller\'s CID.');
		}
	}

	public function deleteVisitingCID($cid) {
		$delete = $this->_db->delete('visitingCIDs', [['cid', '=', $cid]]);
		if($delete) {
			return true;
		} else {
			throw new Exception("There was a problem deleting an visiting controller's CID.");
		}
	}

	public function getMentors() {
		$mentors = $this->_db->query("SELECT controllers.id as cid, controllers.first_name, controllers.pratingstring, controllers.last_name, controllers.grou,
											permissions.id, permissions.name, permissions.sort,
											ratings.id, ratings.long, ratings.short
										FROM controllers
										LEFT JOIN permissions ON permissions.id = controllers.grou
										LEFT JOIN ratings ON ratings.id = controllers.rating
										WHERE controllers.grou >= 11 AND controllers.grou <= 15
										ORDER BY permissions.sort DESC, ratings.id ASC");
		if($mentors->count()) {
			$return = json_decode(json_encode($mentors->results()), true);
		 	$return[] = ['id' => 2, 'long' => 'Senior Controller', 'short' => 'C3', 'name' => 'C1 Mentor', 'sort' => 5, 'cid' => 931070, 'first_name' => 'Martin', 'last_name' => 'Bergin', 'pratingstring' => 'P1', 'grou' => 14, 'fake'=>1];
			$return[] = ['id' => 1, 'long' => 'Enroute Controller', 'short' => 'C1', 'name' => 'C1 Mentor', 'sort' => 5, 'cid' => 1032602, 'first_name' => 'Cillian', 'last_name' => 'Ó Lúing', 'pratingstring' => 'P1', 'grou' => 14, 'fake'=>1];

			 function cmp($a, $b) {
			    if ($a['sort'] == $b['sort']) {
			        return 0;
			    }
			    return ($a['sort'] < $b['sort']) ? 1 : -1;
			}
			uasort($return, 'cmp');
			// print_r($return);
			$return = a2o($return);
			return $return;
		}
	}

	public function getPermissions() {
		$perms = $this->_db->query("SELECT * FROM permissions
										WHERE permissions.id >= 10 AND permissions.id <= 15
										ORDER BY permissions.sort ASC");
		if($perms->count()) {
			return $perms->results();
		}
	}

	public function createStudent($fields = array()) {
		if(!$this->_db->insert('students', $fields)) {
			throw new Exception('There was a problem adding a student.');
		}
	}

	public function updateStudent($fields = array(), $where) {
		if(!$this->_db->update('students', $fields, $where)) {
			throw new Exception('There was a problem updating.');
		}
	}

	public static function program($rating) {

		switch($rating) {
			case($rating==1):
				$program = 2;
			break;
			case($rating==2):
				$program = 3;
			break;
			case($rating==3):
				$program = 4;
			break;
			case($rating==4):
				$program = 5;
			break;
			default:
				$program = 1;
			break;
		}

		return $program;
	}

	public function findStudent($cid) {
		if($cid) {
			$data = $this->_db->get('students', [['cid', '=', $cid]]);
			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	public function getRating($rating) {
		$rating = $this->_db->query("SELECT * FROM ratings WHERE id = ?", [[$rating]]);
		if($rating->count()) {
			return $rating->first();
		}
		return false;
	}

	public function pilotRating($rating) { //Return the actual pilot ratings for the user
		if(isset($rating)) {
			if($rating != 0) {
				$pilotRatings = $this->pilotRatings;

				foreach($pilotRatings as $bitmask=>$textRating) {
					if(($rating & $bitmask) != 0) { //Checking the modulus of the bitmask against the array key provided above
						$r[] = $textRating; //add the rating to the array if user has it
					}
				}

				return implode(", ", $r);

			} else {
				return "P0";
			}
		} else {
			return false;
		}
	}

	public function getSliderCategories() {
		$data = $this->_db->query("SELECT * FROM report_slider_categories ORDER BY sort ASC");
		if($data->count()) {
			return $data->results();
		}
		return false;
	}
	public function whi() {
		$data = $this->_db->query("SELECT controllers.* FROM controllers WHERE controllers.id not in (SELECT students.cid FROM students) AND (controllers.vateir_status = 3 OR controllers.vateir_status = 4)");
		if($data->count()) {
			return $data->results();
		}
		return false;
	}
}
