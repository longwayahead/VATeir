<?php
class Sessions {
	private $_db,
			$_count;
	public 	$end = "",
			$where = [],
			$order = "",
			$bits = "";

	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function add($fields = array()) {
		if(!$this->_db->insert('sessions', $fields)) {
			throw new Exception('There was a problem adding a session.');
		}
	}

	public function get($options = []) {
		$this->where=[];
		$this->end="";
		if(isset($options['student'])) {
			$this->end .= "sessions.student = ?";
			$this->where = [$options['student']];
		} elseif(isset($options['mentor'])) {
			$this->end .= "sessions.mentor = ?";
			$this->where = [$options['mentor']];
		} elseif(isset($options['id'])) {
			$this->end .= "sessions.id = ?";
			$this->where = [$options['id']];
		} elseif(isset($options['all'])) {
			$this->end .= "sessions.id > 0";
		}

		if(isset($options['noreport'])) {
			$this->end .= " AND sessions.report_id IS null";
		}

		if(isset($options['future']) && $options['future'] == 1) {
			$this->end .= " AND sessions.finish >= CURDATE()";
		} elseif(isset($options['future']) && $options['future'] == 2) {
			$this->end .=" AND sessions.finish < CURDATE()";
		}

		if(isset($options['limit'])) {
			$limit = $options['limit'];
			$this->bits = " LIMIT $limit";
		}
		$session = $this->_db->query("SELECT sessions.id as session_id, sessions.student, sessions.mentor, sessions.report_id, sessions.position_id, sessions.report_type, sessions.start, sessions.finish, sessions.deleted as session_deleted,
										report_types.id, report_types.program_id, report_types.session_type, report_types.deleted as report_deleted,
										programs.id, programs.permissions as program_permissions, programs.name as program_name, programs.ident, programs.sort AS program_sort,
										position_list.id, position_list.airport_list_id, position_list.callsign, position_list.freq, position_list.name as position_name,
										card_types.id, card_types.name as session_name,
										controllers.id, controllers.first_name AS sfname, controllers.last_name AS slname,
										control.id, control.first_name AS mfname, control.last_name AS mlname
									FROM sessions
									LEFT JOIN report_types on report_types.id = sessions.report_type
									LEFT JOIN programs on programs.id = report_types.program_id
									LEFT JOIN position_list on position_list.id = sessions.position_id
									LEFT JOIN card_types on card_types.id = report_types.session_type
									LEFT JOIN controllers on controllers.id = sessions.student
									LEFT JOIN controllers as control ON control.id = sessions.mentor
									WHERE $this->end
									ORDER BY sessions.start ASC, sessions.finish ASC
									$this->bits", [$this->where]);
		// print_r($session);
		if($session->count()) {
			return $session->results();
		}
		return false;
	}

	public function edit($fields, $where) {
		if(!$this->_db->update('sessions', $fields, $where)) {
			throw new Exception('There was a problem updating the session.');
		}
		return true;
	}

	public function countStudent($cid) {
		return $this->_db->query("SELECT * FROM sessions WHERE student = ? AND sessions.start >= CURDATE()", [[$cid]])->count();
	}

}