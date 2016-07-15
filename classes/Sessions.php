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
			$this->where[] = $options['student'];
		} elseif(isset($options['mentor'])) {
			$this->end .= "sessions.mentor = ?";
			$this->where[] = $options['mentor'];
		} elseif(isset($options['id'])) {
			$this->end .= "sessions.id = ?";
			$this->where[] = $options['id'];
		} elseif(isset($options['all'])) {
			$this->end .= "sessions.id > 0";
		}

		if(isset($options['noreport'])) {
			$this->end .= " AND sessions.report_id IS null AND sessions.id NOT IN (SELECT session_id FROM infocards)";
		}

		if(isset($options['future']) && $options['future'] == 1) {
					$this->end .= " AND sessions.start >= NOW()";
				} elseif(isset($options['future']) && $options['future'] == 2) {
					$this->end .=" AND sessions.start < NOW()";
				}

		if(isset($options['cancelled']) && $options['cancelled'] = 1) {
			$this->end .= " AND sessions.deleted = 0";
		}

		if(isset($options['deleted'])) {
			$this->end .= " AND sessions.deleted = 0";
		}

		if(isset($options['limit'])) {
			$limit = $options['limit'];
			$this->bits = " LIMIT $limit";
		}

		if(isset($options['thisday'])) {
			$this->end .= " AND DATE(sessions.start) = DATE(?)";
			$this->where[] = $options['thisday'];
		}

		$session = $this->_db->query("SELECT sessions.id as session_id, sessions.student, sessions.mentor, sessions.report_id, sessions.position_id, sessions.report_type, sessions.comment, sessions.start, sessions.finish, sessions.deleted as session_deleted,
										report_types.id as report_type_id, report_types.program_id, report_types.session_type, report_types.deleted as report_deleted,
										programs.id as program_id, programs.permissions as program_permissions, programs.name as program_name, programs.ident, programs.sort AS program_sort,
										position_list.id as position_id, position_list.airport_list_id, position_list.callsign, position_list.freq, position_list.name as position_name,
										card_types.id, card_types.name as session_name, card_types.exam as isexam,
										controllers.id, controllers.email, controllers.first_name AS sfname, controllers.last_name AS slname, controllers.rating,
										control.id, control.first_name AS mfname, control.last_name AS mlname, control.email AS memail
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
			//print_r($session);
			//print_r($session->results());
		}
		return false;
	}

	public function edit($fields, $where) {
		if(!$this->_db->update('sessions', $fields, $where)) {
			throw new Exception('There was a problem updating the session.');
		}
		return true;
	}

	public function max() {
		$max = $this->_db->query("SELECT MAX(id) as session_id FROM sessions");
		if($max->count()) {
			return $max->first()->session_id;
		}
	}

	public function countSessions($cid) {
		return $this->_db->query("SELECT * FROM sessions WHERE student = ? AND sessions.start >= NOW() AND sessions.deleted = 0", [[$cid]])->count();
	}

	public function countAvailabilities($cid) {
		return $this->_db->query("SELECT * FROM availability WHERE cid = ? AND CONCAT(date, ' ', time_from) >= NOW() AND deleted = 0", [[$cid]])->count();
	}

	public function countMentor($cid) {
		$count = $this->_db->query("SELECT COUNT(id) as session,
			(
				SELECT COUNT(id)
					FROM sessions
					WHERE mentor = ?
						AND finish <= NOW()
						AND report_id = null
			) as without,
			(
				SELECT COUNT(id)
					FROM availability
					WHERE CONCAT(date, ' ', time_from) >= NOW()
						AND deleted = 0
			) as available
		FROM sessions
		WHERE mentor = ?
			AND start >= NOW()
			AND deleted = 0", [[$cid, $cid]]);
		if($count->count()) {
			return $count->first()->session + $count->first()->without + $count->first()->available;
		}
	}

}
