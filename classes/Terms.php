<?php
class Terms {
	private $_db;

	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function get($id) {
		$data = $this->_db->query("SELECT * FROM terms_and_conditions WHERE id = ?", [[$id]]);
		if($data->count()) {
			return $data->results();
		}
		return false;
	}

	public function add($fields = array()) {
		if(!$this->_db->insert('terms_and_conditions', $fields)) {
			throw new Exception('There was a problem adding a T&C.');
		}
	}

	public function edit($fields, $where) {
		if(!$this->_db->update('terms_and_conditions', $fields, $where)) {
			throw new Exception('There was a problem updating T&C.');
		}
		return true;
	}
}