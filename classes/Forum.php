<?php
class Forum {
	private $_db;
	public $forum_id;

	public function __construct() {
		$this->_db = DB::getInstance();
	}
	public function getID($vatsim_id) {
		$this->_db->query("USE longway1_vateir_forum");
		$results = $this->_db->query("SELECT user_id FROM phpbb_users WHERE vatsim_id = ?", [[$vatsim_id]]);
		if($results->count()) {
			$this->forum_id = $results->first()->user_id;
			return $this->forum_id;
		}
		return false;
	}

	public function update($fields = array(), $where) {
		$this->_db->query("USE longway1_vateir_forum");
		if(!$this->_db->update('phpbb_users', $fields, $where)) {
			throw new Exception('There was a problem updating forum records.');
		}
	}
	
}