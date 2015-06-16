<?php
class Airports{
	private $_airports,
			$_positions,
			$_db,
			$_notams;
	public $airports,
			$positions,
			$icao = [],
			$notams = "";

	public function __construct() {
		$this->_db = DB::getInstance();
		
		cacheFile(URL.'datafiles/airports.json', 'http://api.vateud.net/airports/country/IE.json');
		$this->_airports = json_decode(file_get_contents(URL.'datafiles/airports.json'), true);
	}

	public function all() {		

		cacheFile(URL.'datafiles/frequencies.json', 'http://api.vateud.net/frequencies/IRL.json');
		$this->_positions = json_decode(file_get_contents(URL.'datafiles/frequencies.json'), true);
		
		foreach($this->_positions as $position) {
			if(strpos($position['callsign'], "ATIS") == false) {
				$icao = substr($position['callsign'], 0, 4);
				$pos = substr($position['callsign'], -3);
				switch($pos) {
					case($pos == 'DEL'):
						$type = 1;
					break;
					case($pos == 'GND'):
						$type = 2;
					break;
					case($pos == 'TWR'):
						$type = 3;
					break;
					case($pos == 'APP'):
						$type = 4;
					break;
					case($pos == 'CTR' && $icao == 'EIDW'):
						$type = 5;
					break;
					case($pos == 'CTR' && $icao == 'EISN'):
						$type = 6;
					break;
				}
				$position['type'] = $type;
				$positions[$icao][] = $position;
			}
		}
		

		foreach(array_reverse($this->_airports) as $airport) {
			$this->airports[$airport['icao']] = $airport;
			$this->airports[$airport['icao']]['positions'] = $positions[$airport->icao];
		}

		$this->airports['EISN']['icao'] = 'EISN';
		$this->airports['EISN']['major'] = 1;
		$this->airports['EISN']['data']['name'] = 'SHANNON CONTROL';
		$this->airports['EISN']['positions'] = $positions['EISN'];

		return $this->airports;
	}

	public function notams() {
		foreach($this->_airports as $airport) {
			$icao[] = strtolower($airport['icao']);
		}
		$airports = implode(',', array_reverse($icao));
		cacheFile(URL.'datafiles/notams.json', 'http://api.vateud.net/notams/' . $airports . '.json');
		$this->_notams = json_decode(file_get_contents(URL.'datafiles/notams.json'), true);
		foreach($this->_notams as $notam) {
			preg_match("/ B\) (.{10})/", $notam['raw'], $time);
			$start = '20' . $time[1] . '00';
			preg_match("/ C\) (.{10})/", $notam['raw'], $times);
			$end = '20' . $times[1] . '00';
			$this->notams[] = [
				'icao' 	=> $notam['icao'],
				'raw'	=> $notam['raw'],
				'message'	=> $notam['message'],
				'start'		=> $start,
				'end'		=> $end
			];
		}

		return $this->notams;
	}

	public function get($icao) {
		$get = $this->_db->query("SELECT * FROM airport_list
								LEFT JOIN position_list ON position_list.airport_id = airport_list.id
								WHERE icao = ?", [[$icao]]);
		if($get->count()) {
			return $get->results();
		}
		return false;
	}

	public function id($icao) {
		$get = $this->_db->query("SELECT MAX(id) as id FROM airport_list");
		if($get->count()) {
			return $get->first()->id;
		}
		return false;
	}

	public function getPos($callsign) {
		$get = $this->_db->query("SELECT * FROM FROM position_list
								WHERE callsign = ?", [[$callsign]]);
		if($get->count()) {
			return $get->results();
		}
		return false;
	}

	public function add($fields = array()) {
		if(!$this->_db->insert('airport_list', $fields)) {
			throw new Exception('There was a problem adding an airport.');
		}
	}

	public function addPos($fields = array()) {
		if(!$this->_db->insert('position_list', $fields)) {
			throw new Exception('There was a problem adding a position.');
		}
	}


}
