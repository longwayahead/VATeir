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
			WHERE s.mentor = ?
			AND e.program_id = ?
			AND s.report_id is not null
			AND DATE(s.finish) BETWEEN DATE_SUB(now(), interval 6 month) AND CURDATE()", [[$cid, $program]]);
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
			$data = $this->_db->query("SELECT count(id) as sessions FROM sessions
											WHERE MONTH(finish) = ? AND YEAR(finish) = ?
										GROUP BY MONTH(finish)", [[$month->m], [$month->y]]);
			if($data->count()) {
				$output[$dt]['sessions'] = $data->first()->sessions;
			}
			$da= $this->_db->query("SELECT count(id) as availability FROM availability
											WHERE MONTH(date) = ? AND YEAR(date) = ?
										GROUP BY MONTH(date)", [[$month->m], [$month->y]]);

			if($da->count()) {
				$output[$dt]['availability'] = $da->first()->availability;
			}
			unset($datehere);
			unset($dateObj);
			unset($monthName);
		}
		return $output;
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
