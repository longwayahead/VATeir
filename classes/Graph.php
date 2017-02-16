<?php
class Graph {
	private $_db;

	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function sessionsByMentor($cid, $time = 0) {
		if($time != 0) {
			$first = 2;
			$second = "DATE_SUB(now(), interval 1 month)";
		} else {
			$first = 1;
			$second = "CURDATE()";
		}
		$r = $this->_db->query("SELECT COUNT(s.id) as count, c.id, c.first_name, c.last_name
								FROM sessions s
								LEFT JOIN controllers c ON c.id = s.mentor
								WHERE c.id = ?
								AND s.report_id is not null
								AND DATE(s.finish) BETWEEN DATE_SUB(now(), interval $first month) AND $second", [[$cid]]);

		if($r->count()) {
			return $r->results();
		}
	}
	public function sbm() {
		$t = new Training;
		foreach($t->getMentors() as $mentor) {
			$graph[$mentor->first_name . ' ' . $mentor->last_name]['this'] = $this->sessionsByMentor($mentor->cid)[0]->count;
			$graph[$mentor->first_name . ' ' . $mentor->last_name]['last'] = $this->sessionsByMentor($mentor->cid, 1)[0]->count;
		}
		return $graph;
	}


	public function myMentoring($cid, $program) {
		$m = $this->_db->query("SELECT COUNT(s.id) as count
			FROM sessions s
			LEFT JOIN report_types e ON e.id = s.report_type
			LEFT JOIN infocards ic ON ic.session_id = s.id
			WHERE s.mentor = ?
			AND e.program_id = ?
			AND s.report_id is not null
			AND s.deleted = 0
			AND ic.id is null
			AND DATE(s.finish) BETWEEN DATE_SUB(now(), interval 6 month) AND CURDATE()", [[$cid], [$program]]);
		if($m->count()) {
			return $m->first();
		}
	}

	public function mm($cid){
		$t = new Training;
		$mm = $t->getPrograms();
		foreach($mm as $m) {
			$programs[$m->ident] = $this->myMentoring($cid, $m->id)->count;
		}
		return $programs;
	}

	public function sa() {
		foreach($this->months() as $month) {
			$datehere = $month->m;
			$dateObj = DateTime::createFromFormat('!n', $datehere);
			$dt = $dateObj->format('F');
			$data = $this->_db->query("SELECT (SELECT count(s.id) FROM sessions s LEFT JOIN infocards ic ON ic.session_id = s.id WHERE MONTH(s.finish) = ? AND YEAR(s.finish) = ? AND s.report_id is not null AND ic.id is null) as sessions,
																(SELECT count(q.id) FROM sessions q RIGHT JOIN infocards ia ON ia.session_id = q.id WHERE MONTH(q.finish) = ? AND YEAR(q.finish) = ? AND ia.card_id = 7) as noshow,
																(SELECT count(c.id) FROM sessions c RIGHT JOIN infocards ib ON ib.session_id = c.id WHERE MONTH(c.finish) = ? AND YEAR(c.finish) = ? AND ib.card_id = 8) as cancelled,
																(SELECT count(a.id) FROM availability a WHERE MONTH(a.date) = ? AND YEAR(a.date) = ?) as availability"
																, [[$month->m, $month->y, $month->m, $month->y, $month->m, $month->y, $month->m, $month->y]]);

			if($data->count()) {
				$output[$dt]['sessions'] = $data->first()->sessions;
				$output[$dt]['availability'] = $data->first()->availability;
				$output[$dt]['noshow'] = $data->first()->noshow;
				$output[$dt]['cancelled'] = $data->first()->cancelled;
			}
			unset($datehere);
			unset($dateObj);
			unset($monthName);
		}
		return ($output);
	}
	public function months() {
		$data = $this->_db->query("SELECT MONTH(date) as m, YEAR(date) as y FROM availability
									WHERE date >= CURDATE() - INTERVAL 6 MONTH AND date < CURDATE()
									GROUP BY MONTH(date), YEAR(date)
									ORDER BY y ASC, m ASC");
		if($data->count()) {
			return $data->results();
		}
	}
}
