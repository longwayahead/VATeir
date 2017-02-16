<?php
class User {
	private $_db,
			$_sessionName = null,

			$_data = array(),

			$_isLoggedIn = false;



			public function __construct($user = null) {
					$this->_db = DB::getInstance();
					$this->_sessionName = 'user';
					//Checking for a session
					if(isset($_SESSION['user']) && !$user) { //If $_SESSION is set...and if login is open
						$user = $_SESSION['user'];
						if($this->find($user)) {
							$this->_isLoggedIn = true;
						} else {
							$this->logout();
						}
					} else {
						$this->find($user);
					}
				}



	public function exists() {

		return (!empty($this->_data)) ? true :false;

	}

	public function find($user = null, $approved = null) { //approved will only login home or visiting controllers


		if($user) {
			if(!isset($approved)) {
				$data = $this->_db->get('controllers', [['id', '=', $user]]); //1=home controller/2=visitingcontroller/3=transfer request/4=visiting request
			} else {
				$data = $this->_db->get('controllers', [['id', '=', $user], ['vateir_status', '<>', 3], ['vateir_status', '<>', 4]]);
			}



			if($data->count()) {

				$this->_data = $data->first();

			//	return true;

				return $this->_data;

			}

		}

		return false;

	}



	public function login($cid = null, $approved = null) {


			if($this->exists()) {

				Session::put($this->_sessionName, $this->data()->id);

			} else {

				$user = $this->find($cid, 'approved');



				if($user) {

					//Store the login and ip address of user

					$this->_db->insert('logins', ['cid' => $this->data()->id, 'ip' => $_SERVER['REMOTE_ADDR'], 'datetime' => date("Y-m-d H-i-s")]);

					Session::put($this->_sessionName, $this->data()->id);

					return true;
				}
			}

		return false;
	}

	public function create($fields = array()) {
		if(!$this->_db->insert('controllers', $fields)) {
			throw new Exception('There was a problem creating an account.');
		}
	}

	public function update($fields, $where) {
		if(!$this->_db->update('controllers', $fields, $where)) {
			throw new Exception('There was a problem updating the controller.');
		}
		return true;
	}

	public function isLoggedIn() {
		return $this->_isLoggedIn;
	}

	public function data() {
		return $this->_data;
	}

	public function delete($where) {
		if(!$this->_db->delete('controllers', $where)) {
			throw new Exception('There was a problem deleting.');
		}
	}

	public function logout() {
		Session::delete($this->_sessionName);
	}

	public function getRealRating($cid) {
		$build = "https://cert.vatsim.net/cert/vatsimnet/idstatusprat.php?cid=" . $cid;
		$pull = simplexml_load_file($build);
		return $pull->user->PreviousRatingInt;
	}

	public function isActive($cid, $interval = "3 MONTH") {
		$this->_db->query("SELECT `c`.`id`, `c`.`first_name`, `c`.`last_name`, `c`.`email`, `c`.`rating`, `c`.`pilot_rating`, `c`.`vateir_status`, `c`.`alive`, `c`.`regdate_vatsim`, `c`.`regdate_vateir`, `c`.`grou`, `l`.`cid`, `l`.`datetime`
			FROM `controllers` AS `c`
			LEFT JOIN `logins` AS `l` ON `l`.`cid` = `c`.`id`
			WHERE `l`.`datetime` > DATE_SUB(now(), INTERVAL $interval)
				AND `c`.`id` = ?", [[$cid]]);
		if($this->_db->count()) {
			return $this->_db->first();
		}
	}

	public function isStatus($cid, $param) { //0=pending/1=home/2=visiting/3=transfer request/4=visiting request
		$this->_db->get('controllers', [['id', '=', $cid], ['vateir_status', '=', $param]]);
		if($this->_db->count()) {
			return true;
		}
	}

	public function isAlive($cid) {
		$this->_db->query("SELECT * FROM controllers WHERE id = {$cid} AND alive = 1");
		if($this->_db->count()) {
			return true;
		} else {
			return false;
		}
	}

	public function checkDupeEmail($email) {
		$email = $this->_db->query("SELECT * FROM email_unsubscribe WHERE email = ?", [[$email]]);
		if($email->count()) {
			return $email->results();
		}
		return false;
	}

	public function getEmailable() {
		$email = $this->_db->query("SELECT c.* FROM controllers c WHERE not exists (SELECT * FROM email_unsubscribe e WHERE e.email = c.email) AND c.vateir_status <> 3 AND c.vateir_status <> 4 AND c.id = 1032602");
		if($email->count()) {
			return $email->results();
		}
	}

	public function unsubscribeEmail($fields = array()) {
		if(!$this->_db->insert('email_unsubscribe', $fields)) {
			throw new Exception('There was a problem unsubscribing you.');
		}
	}

	public function getAll() {
		$this->_db->query("SELECT * FROM controllers");
		return $this->_db->results();
	}

	public function innerjoin() { //for use if
		$this->_db->query("SELECT c.id, c.rating, s.cid FROM controllers c LEFT JOIN students s ON s.cid = c.id WHERE s.cid is null AND first_name <> 'SYSTEM'");
		$ij = $this->_db->results();
		//training class must be initialised
		foreach($ij as $i) {
			$program = Training::program($i->rating);
			$studentMake = Training::createStudent(array(
				'cid'		=> $i->id,
				'program'	=> 	$program
			));
		}

	}

	public function hasPermission($key) {
		$group = $this->_db->query("SELECT * FROM permissions WHERE id = ?", [[$this->data()->grou]]);
		// print_r($group);
		if($group->count()) {
			$permissions = json_decode($group->first()->permissions, true);

			if($permissions[$key] === 1) {
				return true;
			}

		}

		return false;
	}

	public function loginOpen() {
		$isOpen = $this->_db->get('config', [['config', '=', 'login']]);
		if($isOpen->count()) {
			$this->_results = $isOpen->first();
		}
		if($this->_results->setting == '1') {
			return true;
		}
		return false;
	}

	public function getAvatarUrl($options = []) {
		$size = (isset($options['size'])) ? $options['size'] : '100';
		return 'https://www.gravatar.com/avatar/' . md5($options['email']) . '?s=' . $size . '&d=mm';
	}

	public function atcHours($cid) {
		$cid = intval($cid);
		cacheFile(URL.'datafiles/profiles/' . $cid . '.xml', 'https://cert.vatsim.net/cert/vatsimnet/idstatusrat.php?cid=' . $cid, 604800);
		$xml = new SimpleXMLElement(file_get_contents(URL.'datafiles/profiles/' . $cid . '.xml'));
		return o2a($xml->user);
	}

	public function getTerms() {
		$terms = $this->_db->query("SELECT * FROM terms_and_conditions WHERE deleted = 0 ORDER BY type ASC");
		if($terms->count()) {
			return $terms->results();
		}
	}

	public function terms($type, $cid) { //Ooh, an anti-join...
		$terms = $this->_db->query("SELECT t.id, t.type, t.name, t.text, t.date, t.deleted
									FROM terms_and_conditions t
									WHERE NOT EXISTS
										(
											SELECT *
											FROM terms_agreed a
											WHERE a.term_id = t.id
												AND a.cid = ?
										)
										AND t.type = ?
										AND t.deleted = 0
									", [[$cid, $type]]);
		if($terms->count()) {
			return $terms->results();
		}
		return false;
	}

	public function allowed($cid) {
		$allowed = $this->_db->query("SELECT * FROM allowed where cid = ?", [[$cid]]);
		if($allowed->count()) {
			return true;
		}
		return false;
	}

	public function updateTerm($fields = array(), $where) {
		if(!$this->_db->update('terms_and_conditions', $fields, $where)) {
			throw new Exception('There was a problem updating a term.');
		}
	}

	public function addTerm($fields = array()) {
		if(!$this->_db->insert('terms_and_conditions', $fields)) {
			throw new Exception('There was a problem adding a term.');
		}
	}

	public function term_agree($fields = array()) {
		if(!$this->_db->insert('terms_agreed', $fields)) {
			throw new Exception('There was a problem accepting a term.');
		}
	}
}
