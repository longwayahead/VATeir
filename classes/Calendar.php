<?php
Class Calendar {
  private $_db;
  public $dateFormat = 'Ymd\THis\Z';

  public function __construct() {
    $this->_db = DB::getInstance();
  }

  public function getCID($id) {
    $events = $this->_db->query("SELECT s.id,
      s.start,
      s.finish,
      ct.name as session_type,
      pl.callsign as position_callsign,
      CONCAT(stu.first_name, ' ',stu.last_name) as sname,
      CONCAT(men.first_name, ' ',men.last_name) as mname,
      IF(s.mentor = ?, 1, 0) as ismentor,
      IF(ic.card_id = 8, 1, 0) as cancelled
      FROM sessions s
      JOIN position_list pl ON pl.id = s.position_id
      LEFT JOIN (report_types rt, card_types ct) ON (rt.id = s.report_type AND ct.id = rt.session_type)
      LEFT JOIN controllers stu ON stu.id = s.student
      LEFT JOIN controllers men ON men.id = s.mentor
      LEFT JOIN infocards ic ON ic.session_id = s.id
      WHERE (s.student = ? OR s.mentor = ?)
        AND DATE(s.start) >= CURDATE()
        AND s.report_id IS NULL
        AND s.deleted = 0
      ORDER BY s.start ASC",
    [[$id, $id, $id]]);
    if($events->count()) {
      return $events->results();
    }
    return false;
  }

  public function calendarMake($id) {
    $sessions = $this->eventsMake($id);
    if($sessions != false) {
      $calendar = "BEGIN:VCALENDAR";
      $calendar .= "\nMETHOD:PUBLISH";
      $calendar .= "\nVERSION:2.0";
      $calendar .= "\nPRODID:-//VATeir//Mentoring Sessions for $id//EN";
      $calendar .= $this->eventsMake($id);
      $calendar .= "\nEND:VCALENDAR";
      return $calendar;
    } else {
      throw new Exception("No sessions found");
    }
  }

  public function eventsMake($id) {
    $event = $this->getCID($id);
    if($event != false) {
      foreach($event as $ev) {
        if($ev->ismentor == 1) {
          $name = $ev->sname;
        } else {
          $name = $ev->mname;
        }
        $e .= "\nBEGIN:VEVENT";
        $e .= "\nUID:$ev->id";
        $e .= "\nSTATUS:"; $e .= ($ev->cancelled == 0) ? 'CONFIRMED' : 'CANCELLED';
        $e .= "\nDTSTART:" . $this->outputDate($ev->start);
        $e .= "\nDTEND:" . $this->outputDate($ev->finish);
        $e .= "\nSUMMARY:$name on $ev->position_callsign";
        $e .= "\nDESCRIPTION:Type: $ev->session_type.\\nStudent: $ev->sname\\nMentor: $ev->mname\\nPosition: $ev->position_callsign\\n";
        $e .= "\nEND:VEVENT";
      }
    return $e;
    }
    return false;
  }

  public function outputDate($mysqlDate = null) {
    $date = new DateTime($mysqlDate,new DateTimeZone('Europe/Dublin'));
    $date->setTimezone(new DateTimeZone('GMT'));
    return $date->format($this->dateFormat);
  }

}
