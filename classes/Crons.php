<?php
class Crons {
	private $_db;
	public function __construct() {
		$this->_db = DB::getInstance();
	}
	public function add($fields = array()) {
		if(!$this->_db->insert('crons', $fields)) {
			throw new Exception('There was a problem adding a cron.');
		}
	}
	public function limit() {
		$cron = $this->_db->query("SELECT * FROM crons WHERE data IS NOT NULL ORDER BY date DESC LIMIT 5");
		if($cron->count()) {
			return $cron->results();
		}
		return false;
	}
	public function get($id) {
		$cron = $this->_db->query("SELECT * FROM crons WHERE id = ?", [[$id]]);
		if($cron->count()) {
			return $cron->first();
		}
		return false;
	}
}