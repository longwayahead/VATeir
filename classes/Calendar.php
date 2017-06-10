<?php
Class Calendar {
  private $_db,
          $sessions = [];
  public $dateFormat = 'Ymd\THis\Z',
          $eventAuth = true;

  public function __construct() {
    $this->_db = DB::getInstance();
  }

  public static function generateHash($cid) {
    $hash = md5(substr($cid . time(), 15, 10));
    return $hash;
  }

  public function make($cid) {
    $hash = $this->generateHash($cid);
    $idCheck = $this->_db->query("SELECT cid FROM calendar where cid = ?", [[$cid]]);
    if($idCheck->count()) {
      $this->delete($cid);
    }
    if(!$this->_db->insert('calendar',
      [
        'cid' => $cid,
        'hash' => $hash
      ]
    )) {
      throw new Exception('There was a problem generating an access token.');
    }
  }

  public function delete($cid) {
		$delete = $this->_db->delete('calendar', [['cid', '=', $cid]]);
		if($delete) {
			return true;
		} else {
			throw new Exception("There was a problem deleting the calendar record.");
		}
	}

  public function edit($fields, $where) {
		if(!$this->_db->update('calendar', $fields, $where)) {
			throw new Exception('There was a problem updating your calendar entry.');
		}
		return true;
	}

  public function getHash($cid) {
    $hash = $this->_db->query("SELECT
      cl.hash,
      cl.events
      FROM calendar cl
      WHERE cid = ?
    ", [[$cid]]);
    if($hash->count()) {
      return $hash->first();
    }
    return false;
  }
  public function getAsHash($hash) {
    $events = $this->_db->query("SELECT
      s.id,
      s.start,
      s.finish,
      cl.events,
      ct.name as session_type,
      CONCAT(stu.first_name, ' ',stu.last_name) as sname,
      CONCAT(men.first_name, ' ',men.last_name) as mname,
      IF(ic.card_id = 8, 1, 0) as cancelled,
      pl.callsign as position_callsign,
      IF(s.mentor = cl.cid, 1, 0) as ismentor
      FROM sessions s
      LEFT JOIN calendar cl ON cl.cid = s.mentor
      JOIN (report_types rt, card_types ct) ON (rt.id = s.report_type AND ct.id = rt.session_type)
      LEFT JOIN infocards ic ON ic.session_id = s.id
      JOIN position_list pl ON pl.id = s.position_id
      LEFT JOIN controllers stu ON stu.id = s.student
      LEFT JOIN controllers men ON men.id = s.mentor
      WHERE cl.hash = ?
        AND s.start >= CURDATE()
        AND (s.mentor = cl.cid OR s.student = cl.cid)
        AND s.report_id IS NULL
        AND s.deleted = 0
      ORDER BY s.start ASC",
    [[$hash]]);
    if($events->count()) {
      return $events->results();
    }
    return false;
  }

  public function getSiteEvents() {

    $vateudEvents = json_decode(@file_get_contents('http://api.vateud.net/events/vacc/IRL.json'));
    $events = $this->_db->query("SELECT
      s.id,
      s.start,
      s.finish,
      ct.name as session_type,
      CONCAT(stu.first_name, ' ',stu.last_name) as sname,
      CONCAT(men.first_name, ' ',men.last_name) as mname,
      pl.callsign as position_callsign
      FROM sessions s
      JOIN (report_types rt, card_types ct) ON (rt.id = s.report_type AND ct.id = rt.session_type)
      JOIN position_list pl ON pl.id = s.position_id
      LEFT JOIN controllers stu ON stu.id = s.student
      LEFT JOIN controllers men ON men.id = s.mentor
      WHERE s.start >= CURDATE()
        AND (ct.id = 1 OR ct.id = 3)
        AND s.report_id IS NULL
        AND s.deleted = 0
      ORDER BY s.start ASC");
    if($events->count()) {
      $vateirSessions = $events->results();
    }
    if($vateudEvents) {
      foreach($vateudEvents as $event) {
        if(date("Y-m-d", strtotime($event->starts)) >= date("Y-m-d")) { //change to >= for future events
          $siteEvents[] = [ //vateud events
            'id' => $event->id,
            'type' => 0,
            'summary' => "Event: " . $event->title,
            'starts' => $this->outputDate($event->starts,1),
            'ends' => $this->outputDate($event->ends,1),
          //  'description' => htmlentities(substr($event->description, 0, 200))
            'description' => 'VATEUD Event'
          ];
        }
      }
    }
    if($vateirSessions) {
      foreach($vateirSessions as $session) {
        if(!in_array($session->id, $this->sessions)) { //make sure we don't duplicate the event if it is they that has the session booked
          $siteEvents[] = [ //vateir sessions this time
            'id' => $session->id,
            'type' => 1,
            'summary' => "$session->session_type: $session->position_callsign",
            'starts' => $this->outputDate($session->start),
            'ends' => $this->outputDate($session->finish),
            'description' => "$session->session_type session.\\nTraffic is kindly requested for $session->position_callsign\\n"
          ];
        }
      }
    }
    return $siteEvents;
  }

  public function calendarMake($hash) {
    $this->eventAuth = ($this->_db->query("SELECT events FROM calendar where hash = ?", [[$hash]])->first()->events == 0) ? false : true; //determine whether to show events or not
    $sessions = $this->eventsMake($hash);
    $calendar = "BEGIN:VCALENDAR";
    $calendar .= "\nMETHOD:PUBLISH";
    $calendar .= "\nVERSION:2.0";
    $calendar .= "\nPRODID:-//VATeir//$hash//EN";
    $calendar .= $this->eventsMake($hash); //get personal events
    if($this->eventAuth == true) { //get site events
        $calendar .= $this->siteEventsMake();
    }
    $calendar .= "\nEND:VCALENDAR";
    return $calendar;
  }

  public function eventsMake($hash) {
    $event = $this->getAsHash($hash);
    if($event != false) {
      foreach($event as $ev) {
        $this->sessions[] = $ev->id;
        if($ev->ismentor == 1) {
          $name = $ev->sname;
        } else {
          $name = $ev->mname;
        }
        $e .= "\nBEGIN:VEVENT";
        $e .= "\nUID:s$ev->id";
        $e .= "\nSTATUS:"; $e .= ($ev->cancelled == 0) ? 'CONFIRMED' : 'CANCELLED';
        $e .= "\nDTSTART:" . $this->outputDate($ev->start);
        $e .= "\nDTEND:" . $this->outputDate($ev->finish);
        $e .= "\nSUMMARY:$name on $ev->position_callsign";
        $e .= "\nDESCRIPTION:Type: $ev->session_type.\\nStudent: $ev->sname\\nMentor: $ev->mname\\nPosition: $ev->position_callsign\\n";
        $e .= "\nEND:VEVENT";
      }
    return $e;
    }
  }

  public function siteEventsMake() {
    $event = $this->getSiteEvents();
    if(!empty($event)) {
      foreach($event as $ev) {
        $f .= "\nBEGIN:VEVENT";
        $f .= "\nUID:";
        $f .= ($ev['type'] == 0) ? "v" : "s";
        $f .= $ev['id'];
        $f .= "\nDTSTART:" . $ev['starts'];
        $f .= "\nDTEND:" . $ev['ends'];
        $f .= "\nSUMMARY:" . $ev['summary'];
        $f .= "\nDESCRIPTION:" . $ev['description'];
        $f .= "\nEND:VEVENT";
      }
      return $f;
    }
  }

  public function outputDate($mysqlDate, $tz = null) {
    if($tz == null) {
      $date = new DateTime($mysqlDate,new DateTimeZone('Europe/Dublin'));
    } else {
      $date = new DateTime($mysqlDate,new DateTimeZone('GMT'));
    }

    $date->setTimezone(new DateTimeZone('GMT'));
    return $date->format($this->dateFormat);
  }

}
