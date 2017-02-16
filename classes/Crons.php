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
		$cron = $this->_db->query("SELECT * FROM crons WHERE data <> '' ORDER BY date DESC LIMIT 5");
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
	public function deleteNonVATeir($array) {
		$controller = '(id <> '. implode(' AND id <> ', $array) . ')AND first_name <> "SYSTEM" AND id <> 1032602';
		echo $controller;
		$student = '(cid <> '. implode(' AND cid <> ', $array) . ') AND cid <> 1032602';
		$q= $this->_db->query("SELECT * FROM controllers WHERE {$controller}");
		if($q->count()) {
			return $q->results();
		}
		throw new Exception('There was a problem deleting a controller.');
	}
}