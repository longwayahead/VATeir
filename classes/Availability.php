<?php
class Availability {
	private $_db;
	public 	$where = [],
			$end = "",
			$limitstr = null,
			$count = null;

	public function __construct() {
		$this->_db = DB::getInstance();
	}
	
	public function add($fields = array()) {
		if(!$this->_db->insert('availability', $fields)) {
			throw new Exception('There was a problem adding an availability.');
		}
	}

	public function get($options = []) {
		$this->where=[];
		$this->end="";
		$this->limitstr = null;
		if(!empty($options)) {
			$this->end = " WHERE availability.date >= CURDATE() ";
		}

		if(isset($options['limit'])) {
			$this->limitstr = 'LIMIT ' . $options['limit'];
		}
		
		if(isset($options['student'])) {
			$this->end .= "AND availability.cid = ?";
			$this->where = [$options['student']];
		} elseif(isset($options['id'])) {
			$this->end .= "AND availability.id = ?";
			$this->where = [$options['id']];
		}

		if(isset($options['deleted']) && $options['deleted'] == 1) {
			$this->end .= " AND availability.deleted = '1'";
		} else {
			$this->end .= " AND availability.deleted = '0'";
		}
		$avail = $this->_db->query("SELECT availability.id AS availability_id, availability.cid, availability.date, availability.time_from, availability.time_until, availability.deleted,
									controllers.id, controllers.first_name, controllers.last_name,
									students.id, students.cid, students.program,
									programs.id, programs.name as program_name, programs.ident, programs.sort, programs.permissions
									FROM availability
									LEFT JOIN controllers on controllers.id = availability.cid
									LEFT JOIN students on students.cid = controllers.id
									LEFT JOIN programs on programs.id = students.program
									$this->end
									ORDER BY availability.date ASC,
											availability.time_from ASC,
											availability.time_until ASC
									$this->limitstr",
									[$this->where]);
	
		if($avail->count()) {
			$this->count = $avail->count();
			return $avail->results();
		}
		return false;	
	}

	public function edit($fields, $where) {
		if(!$this->_db->update('availability', $fields, $where)) {
			throw new Exception('There was a problem updating the availability.');
		}
		return true;
	}
}