<?php
class Admin {
	private $_db,
			$_data;
	public 	$where = null,
			$bits = [];

	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function incoming($cid = null) {
		if(isset($cid)) {
			$this->where = " AND controllers.id = ?";
			$this->bits[] = $cid;
		}
		$incoming = $this->_db->query("SELECT controllers.id as cid, controllers.first_name, controllers.last_name, controllers.email, controllers.rating, controllers.pilot_rating, controllers.vateir_status, controllers.alive, controllers.regdate_vatsim, controllers.regdate_vateir, controllers.grou,
										ratings.id, ratings.long, ratings.short,
										vateir_status.id, vateir_status.status
										FROM controllers
										LEFT JOIN ratings ON ratings.id = controllers.rating
										LEFT JOIN vateir_status ON vateir_status.id = controllers.vateir_status
										WHERE (controllers.vateir_status = 3
												OR controllers.vateir_status = 4)
											$this->where
										ORDER BY controllers.regdate_vateir DESC", [[$cid]]);

		if($incoming->count()) {
			$this->_data = $incoming->results();
			return $this->_data;
		}
		return false;
	}

	public function getAllowed() {
		$allowed = $this->_db->query("SELECT * FROM allowed");
		if($allowed->count()) {
			return $allowed->results();
		}
	}

	public function deleteAllow($cid) {
		$delete = $this->_db->delete('allowed', [['cid', '=', $cid]]);
		if($delete) {
			return true;
		} else {
			throw new Exception("There was a problem deleting an allowed CID.");
		}
	}

	public function addAllow($fields = array()) {
		if(!$this->_db->insert('allowed', $fields)) {
			throw new Exception('There was a problem adding an authorised CID.');
		}
	}

	public function config($fields = array(), $where) {
		if(!$this->_db->update('config', $fields, $where)) {
			throw new Exception('There was a problem updating site config settings.');
		}
	}
}