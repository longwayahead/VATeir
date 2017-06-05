<?php
class Check {
  private $_db,
          $_vateud = 'http://api.vateud.net/members/id/',
          $_vatsim = 'https://cert.vatsim.net/cert/vatsimnet/idstatusint.php?cid=',
          $_error;
  public $check = [],
          $verdict;

  public function __construct() {
		$this->_db = DB::getInstance();
	}

  public static function xml2json($url) {
    return json_decode(json_encode(simplexml_load_file($url)));
  }

  public function check($cid) {
    $this->vateir($cid);
    if(!empty($this->check['vateir'])) {
      return $this->check;
    } else {
      $this->check['vateir']['controller'] = 0;
      $this->check['vateir']['student'] = 0;
      $this->vateud($cid);
      if(empty($this->check['vateud'])) {
        $this->check['vateud']['account'] = 0;
        $this->check['vateud']['eud'] = 0;
        $this->check['vateud']['irl'] = 0;
      }
      return $this->check;
    }






    return $this->check;
  }


  // public function vatsim($cid) {
  //   $vatsim = $this->xml2json($this->_vatsim . $cid)->user;
  //
  //   if($vatsim->region == 'EUR') {
  //     $this->check['vatsim']['eur'] = 1;
  //   }
  //   if($vatsim->division == 'EUD') {
  //     $this->check['vatsim']['eud'] = 1;
  //   }
  //
  // }

  public function vateud($cid) {
    $vateud = json_decode(file_get_contents($this->_vateud . $cid . '.json'));
    if(!empty($vateud)) {
      $this->check['vateud']['account'] = 1;
      if(!isset($vateud->division) && $vateud->firstname != null) {
        $this->check['vateud']['eud'] = 1;
      }
      if($vateud->subdivision == 'IRL') {
        $this->check['vateud']['irl'] = 1;
      }
    } else {
      $this->check['vateud']['account'] = 0;
    }


  }

  public function vateir($cid) {
    $query = $this->_db->query("SELECT
                        IF(c.id IS NOT NULL, 1, 0) as controller,
                        IF(s.cid IS NOT NULL, 1, 0) as student
                        FROM controllers c
                        LEFT JOIN students s ON s.cid = c.id
                        WHERE c.id = ?",
                      [[$cid]]
                    );
    if($query->count()) {
      $first = $query->first();
      if($first->controller == 1) {
        $this->check['vateir']['controller'] = 1;
      } else {
      }
      if($first->student == 1) {
        $this->check['vateir']['student'] = 1;
      } else {
      }
    }
    return false;
  }
}
