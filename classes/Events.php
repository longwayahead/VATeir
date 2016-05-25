<?php
class Events {
	public $current = [],
			$past = [];
	private $_db,
			$_events  = [];


	public function __construct() {
		$this->_db = DB::getInstance();
		cacheFile(URL.'datafiles/events.json', 'http://api.vateud.net/events/vacc/IRL.json');
		$this->_events = json_decode(file_get_contents(URL.'datafiles/events.json'));

		$sessions = $this->sessions();
		if(!empty($sessions)) {
			foreach($sessions as $session) {
				if($session->card_id == 1) {
					$session_name = 'Mentoring Session';
					$prep = ' a ';
				} else {
					$session_name = 'Exam';
					$prep = ' an ';
				}
				$description = 'There will be' . $prep . $session_name . ' on <strong>' . $session->position_name . '</strong> from ' . date('H:i', strtotime($session->start)) . ' to ' . date('H:i', strtotime($session->finish)) . '(IST) on ' . date('l', strtotime($session->start)) . ' the ' . date('j\<\s\u\p\>S\<\/\s\u\p\>', strtotime($session->start)) . ' of ' . date('F', strtotime($session->start));

				$this->current[$session->session_id] = [
					'id' => $session->session_id,
					'title' => $session_name . ' on ' . $session->callsign,
					'subtitle' => $session_name . ' on ' . $session->position_name,
					'banner_url' => 'http://www.vateir.org/img/logo.png',
					'description' => $description . '<br> Why not fly, contact ' . $session->first_name . ' on the frequency ' . $session->freq . ' and help him progress to his next rating?!',
					'short_description' => $description,
					'starts_date' => date('j-M-y', strtotime($session->start)),
					'starts_time' => date('H:i', strtotime($session->start)),
					'ends_date' => date('j-M-y', strtotime($session->finish)),
					'ends_time' => date('H:i', strtotime($session->finish))
				];
			}
		}

		foreach($this->_events as $event) {
			$starts_date = date('j-M-y', strtotime($event->starts));
			$starts_time = date('H:i', strtotime($event->starts));
			$ends_date = date('j-M-y', strtotime($event->ends));
			$ends_time = date('H:i', strtotime($event->ends));
			if(date('Y-m-d') < date('Y-m-d', (strtotime($event->ends)))) {
				$this->current[$event->id] = array(
					'id' => $event->id,
					'title' => $event->title,
					'subtitle' => $event->subtitle,
					'banner_url' => $event->banner_url,
					'description' => $event->description,
					'short_description' => substr($event->description, 0, 200).'...',
					'starts_date' => $starts_date,
					'starts_time' => $starts_time,
					'ends_date' => $ends_date,
					'ends_time' => $ends_time
					);

			} else {
				$this->past[$event->id] = array(
					'title' => $event->title,
					'subtitle' => $event->subtitle,
					'banner_url' => $event->banner_url,
					'description' => $event->description,
					'short_description' => substr($event->description, 0, 200).'...',
					'starts_date' => $starts_date,
					'starts_time' => $starts_time,
					'ends_date' => $ends_date,
					'ends_time' => $ends_time
					);

			}

		}
		usort($this->current, "sortFunction");
	}

	public function future() {
		if(!empty($this->current)) {
				return $this->current;
		}
		return false;
	}
	public function sessions() {
		$sessions = $this->_db->query("SELECT c.id as card_id, c.type, c.name as session_type_name,
										r.*,  u.*,
										s.id as session_id, s.student, s.position_id, s.report_type, s.start, s.finish, s.deleted,
										p.id, p.name as program_name,
										pos.id, pos.position_type_id, pos.airport_list_id, pos.callsign, pos.freq, pos.name as position_name
										FROM card_types c
										LEFT JOIN report_types r ON r.session_type = c.id
										RIGHT JOIN sessions s ON s.report_type = r.id
										LEFT JOIN programs p ON r.program_id = p.id
										LEFT JOIN controllers u ON u.id = s.student
										LEFT JOIN position_list pos ON pos.id = s.position_id
										WHERE (c.id = 1 OR c.id = 3)
											AND s.deleted = 0
											AND s.finish >= NOW()");
		if($sessions->count()) {
			return $sessions->results();
		}
		return false;
	}

	public function past() {
		if(!empty($this->past)) {
			return array_reverse($this->past);

		}
		return false;
	}

	public function random() {
		if(!empty($this->current)) {
			$k = array_rand($this->current);
			return a2o($this->current[$k]);
		}
		return false;
	}
}
// 27<sup>th</sup> December, 2014
