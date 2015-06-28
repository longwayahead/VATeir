<?php
class Events {
	public $current = [],
			$past = [];
	private $_events  = [];

	public function __construct() {
		cacheFile(URL.'datafiles/events.json', 'http://api.vateud.net/events/vacc/IRL.json');
		$this->_events = json_decode(file_get_contents(URL.'datafiles/events.json'));
		foreach($this->_events as $event) {
			$starts_date = date('j-M-y', strtotime($event->starts));
			$starts_time = date('H:i', strtotime($event->starts));
			$ends_date = date('j-M-y', strtotime($event->ends));
			$ends_time = date('H:i', strtotime($event->ends));
			if(date('Y-m-d') > strtotime($event->ends) && $ends_time > date('H:i')) {
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
	}

	public function future() {
		if(!empty($this->current)) {
				return $this->current;
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