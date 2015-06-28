<?php
class User {
	private $_db,
			$_sessionName = null,
			$_data = array(),
			$_isLoggedIn = false;

	public function __construct($user = null) {
		$this->_db = DB::getInstance();

		$this->_sessionName = Config::get('session/session_name');

		//Checking for a session
		if(Session::exists($this->_sessionName) && !$user) { //If $_SESSION is set...and if login is open
			$user = Session::get($this->_sessionName);
			 //&& ($this->loginOpen() || ((isset($this->_data) && $this->_data->id == "1032602"))) for next line check login is open
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

	public function find($user = null) {

		if($user) {
			$data = $this->_db->get('controllers', [['id', '=', $user], ['vateir_status', '<>', 3], ['vateir_status', '<>', 4]]); //1=home controller/2=visitingcontroller/3=transfer request/4=visiting request

			if($data->count()) {
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}

	public function login($cid = null) {

			if($this->exists()) {
				Session::put($this->_sessionName, $this->data()->id);
			} else {
				$user = $this->find($cid);
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
				AND `c`.`id` = {$cid}");
		if($this->_db->count()) {
			return true;
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
		$email = $this->_db->query("SELECT c.* FROM controllers c WHERE not exists (SELECT * FROM email_unsubscribe e WHERE e.email = c.email) AND c.email = 'cillianlong@gmail.com'");
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
		return 'http://www.gravatar.com/avatar/' . md5($options['email']) . '?s=' . $size . '?d=mm';
	}

	public function atcHours($cid) {
		$cid = intval($cid);
		cacheFile(URL.'datafiles/profiles/' . $cid . '.xml', 'https://cert.vatsim.net/cert/vatsimnet/idstatusrat.php?cid=' . $cid, 604800);
		$xml = new SimpleXMLElement(file_get_contents(URL.'datafiles/profiles/' . $cid . '.xml'));
		return o2a($xml->user);
	}

}