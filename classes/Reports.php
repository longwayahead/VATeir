<?php
class Reports {
	private $_db,
			$_data = array();

	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function getTypes($type = 0, $options = array()) { //Get all the types of session. OBS-S1: Live. OBS-S1:Sweatbox etc. //type = 0 for session. = 1 for note
		if($type === 0) {
			$value = null;
			$idwhere = "";
			if(isset($options['program'])) {
				switch($options['program']) {
					case($options['program'] >0):
						$idwhere = '`p`.`id` = ?';
						$value[] = $options['program'];
					break;
					case($options['program'] == 0):
						$idwhere = '`p`.`id` > 0';
					break;
				}
				
			} elseif(isset($options['type'])) {
				switch($options['type']) {
					case($options['type']>0):
						$idwhere = '`t`.`id` = ?';
						$value[] = $options['type'];
					break;
					case($options['type'] == 0):
						$idwhere = '`t`.`id` > 0';
					break;
				}
			}
			

			$types = $this->_db->query("SELECT `t`.`id` AS `report_type_id`, `t`.`program_id`, `t`.`session_type`, `t`.`deleted`,
				`s`.`id`, `s`.`type`, `s`.`name` AS `session_type_name`, `s`.`colour`, `s`.`sort` AS `typesort`,
				`p`.`id` AS `pid`, `p`.`name` AS `program_name`, `p`.`ident`, `p`.`sort` AS `programsort`
				FROM `report_types` AS `t`
				LEFT JOIN `card_types` AS `s` ON `s`.`id` = `t`.`session_type`
				LEFT JOIN `programs` AS `p` ON `p`.`id` = `t`.`program_id`
				WHERE `s`.`type` = 0
						AND `s`.`deleted` = 0
						AND `t`.`deleted` = 0
						AND $idwhere
				ORDER BY `p`.`sort` ASC, `s`.`sort` ASC",
				[$value]
			);
		} elseif($type === 1) {
				$types = $this->_db->db("SELECT 
				`s`.`id`, `s`.`type`, `s`.`name`, `s`.`colour`, `s`.`sort` AS `notesort`
				FROM `card_types` AS `s`",
				[
					['`s`.`type`', '=', 1],
					['`s`.`deleted`', '=', 0]
				],
				"ORDER BY `s`.`sort` ASC"
			);
		}
		// print_r($types);
		if($types->count()) {

			return($types->results());
			
			
		}
		return false;
	}


	// 
	public function getPositionsByProgram($program) {
		$positions = $this->_db->db("SELECT `p`.`id` AS `position_id`, `p`.`position_type_id`, `p`.`callsign`,
	 		`t`.`id`,
	 		`j`.`id`, `j`.`program_id`, `j`.`sector_id`,
	 		`g`.`id`
			FROM `position_list` AS `p`
			LEFT JOIN `position_type` AS `t` ON `t`.`id` = `p`.`position_type_id`
			LEFT JOIN `report_programs_sectors` AS `j` ON `j`.`sector_id` = `t`.`id`
			LEFT JOIN `programs` AS `g` ON `g`.`id` = `j`.`program_id`",
			[
				['`j`.`program_id`', '=', $program]
			]);
		if($positions->count()) {
			return $positions->results();
		}
		return false;
	}

	public function getPositions($program_type) {
		$positions = $this->_db->query("SELECT `p`.`id` AS `position_id`, `p`.`position_type_id`, `p`.`callsign`,
	 		`t`.`id`,
	 		`j`.`id`, `j`.`program_id`, `j`.`sector_id`,
	 		`g`.`id`
			FROM `position_list` AS `p`
			LEFT JOIN `position_type` AS `t` ON `t`.`id` = `p`.`position_type_id`
			LEFT JOIN `report_programs_sectors` AS `j` ON `j`.`sector_id` = `t`.`id`
			LEFT JOIN `programs` AS `g` ON `g`.`id` = `j`.`program_id`
			WHERE `g`.`id` = ?", [[$program_type]]);
		if($positions->count()) {
			return $positions->results();
		}
		return false;
	}



	public function getSliders($type, $id) { //Get all the sliders for a particular program
		if($type === 1) { //program get questions
			$sliders = $this->_db->db("SELECT `s`.`id`, `s`.`program_id`, `s`.`text`, `s`.`deleted`, `s`.`type`
				FROM `report_slider_questions` AS `s`",
				[
					['`s`.`program_id`', '=', $id],
					['`s`.`deleted`', '=', 0]
				]
			);
		} elseif($type === 2) { //report get answers
			$sliders = $this->_db->db("SELECT `s`.`id`, `s`.`report_id`, `s`.`slider_id`, `s`.`value`,
				`q`.`id`, `q`.`program_id`, `q`.`text`				
				FROM `report_slider_questions` AS `q`
				LEFT JOIN `report_slider_answers` AS `s` ON `s`.`slider_id` = `q`.`id`",
				[
					['`s`.`report_id`', '=', $id]
				]
			);
		}
		if($sliders->count()) {
			return $sliders->results();
		}
		return false;
	}		

	public function testget($id) {
		$sliders = $this->_db->query("SELECT `s`.`id`, `s`.`report_id`, `s`.`slider_id`, `s`.`value`,
				`q`.`id`, `q`.`program_id`, `q`.`text`, `q`.`deleted`				
				FROM `report_slider_questions` AS `q`
				LEFT JOIN `report_slider_answers` AS `s` ON `s`.`slider_id` = `q`.`id`
				WHERE `s`.`report_id` = '{$id}'
			UNION
				SELECT `a`.`id`, `a`.`program_id`, `a`.`text`, `a`.`deleted`, 
				`a`.`id` AS `id`, `a`.`id` AS `report_id`, `a`.`id` AS `slider_id`, `a`.`id` AS `value`, 
					FROM `report_slider_questions` as `a`
				WHERE `a`.`deleted` = '0'
				");
			echo '<pre>';
			print_r($sliders);
			echo '</pre>';
		
	}

	public function getCards($search) {
		$cards = $this->_db->query("SELECT
				`c`.`id`, `c`.`cid`, `c`.`card_type`, `c`.`link_id`, `c`.`submitted`, `c`.`deleted`,
				`ca`.`id` AS `cid`, `ca`.`first_name`, `ca`.`last_name`
				FROM `cards` AS `c`
				LEFT JOIN `controllers` AS `ca` ON `ca`.`id` = `c`.`cid`
				WHERE `c`.`cid` = '{$search}' AND `c`.`deleted` = '0'
				ORDER BY `c`.`submitted` DESC
			"
		);
		if($cards->count()) {
			return $cards->results();
		}
		return false;
	}

	public function updateReport($fields, $where) {
		if(!$this->_db->update('reports', $fields, $where)) {
			throw new Exception('There was a problem updating that card.');
		}
		return true;
	}

	public function updateCard($fields, $where) {
		if(!$this->_db->update('cards', $fields, $where)) {
			throw new Exception('There was a problem updating that card.');
		}
		return true;
	}

	public function updateNote($fields, $where) {
		if(!$this->_db->update('notes', $fields, $where)) {
			throw new Exception('There was a problem updating that note.');
		}
		return true;
	}

	public function getNote($note_id) {
		$note = $this->_db->db("SELECT
			`n`.`id` AS `note_id`, `n`.`student_cid`, `n`.`mentor_cid`, `n`.`note_type`, `n`.`submitted_date`, `n`.`subject`, `n`.`text`,
			`c`.`id` AS `card_id`, `c`.`type`, `c`.`name`, `c`.`colour`, `c`.`sort`,
			`m`.`id`, `m`.`first_name` AS `mfname`, `m`.`last_name` AS `mlname`,
			`s`.`id`, `s`.`first_name` AS `sfname`, `s`.`last_name` AS `slname`,
			`st`.`cid`, `st`.`program`,
			`p`.`id`, `p`.`permissions`
			FROM `notes` AS `n`
			LEFT JOIN `card_types` AS `c` ON `c`.`id` = `n`.`note_type`
			LEFT JOIN `controllers` AS `m` ON `m`.`id` = `n`.`mentor_cid`
			LEFT JOIN `controllers` AS `s` ON `s`.`id` = `n`.`student_cid`
			LEFT JOIN `students` AS `st` ON `st`.`cid` = `s`.`id`
			LEFT JOIN `programs` AS `p` ON `p`.`id` = `st`.`program`
			",
			[
				['`n`.`id`', '=', $note_id]
			]
			);
		if($note->count()) {
			return $note->first();
		}
		return false;
	}

	public function getReport($type, $id) {
		if($type == 1) {
			$array = ['`r`.`id`', '=', $id];
			$bits = null;
		} elseif($type == 2) {
			$array = ['`r`.`id`', '>=', $id];
			$bits = 'ORDER BY `r`.`submitted_date` DESC LIMIT 5';
		}
		$report = $this->_db->db("SELECT
			`r`.`id` AS `rep_id`, `r`.`student_cid`, `r`.`mentor_cid`, `r`.`report_type_id`, `r`.`position_id`, `r`.`submitted_date`, `r`.`session_date`, `r`.`text`,
			`t`.`id` AS `typ_id`, `t`.`program_id`, `t`.`session_type`, `t`.`deleted`,
			`s`.`id` AS `sess_type_id`, `s`.`type`, `s`.`name` AS `sessname`, `s`.`colour`, `s`.`sort` AS `sesssort`,
			`p`.`id` AS `pos_id`, `p`.`callsign`,
			`stu`.`id`, `stu`.`cid`, `stu`.`program`,
			`prog`.`id` AS `prog_id`, `prog`.`name`, `prog`.`ident`, `prog`.`sort`, `prog`.`permissions`,
			`m`.`id`, `m`.`first_name` AS `mfname`, `m`.`last_name` AS `mlname`,
			`st`.`id`, `st`.`first_name` AS `sfname`, `st`.`last_name` AS `slname`
			FROM `reports` AS `r`
			LEFT JOIN `report_types` AS `t` ON `t`.`id` = `r`.`report_type_id`
			LEFT JOIN `card_types` AS `s` ON `s`.`id` = `t`.`session_type`
			LEFT JOIN `students` AS `stu` ON `stu`.`cid` = `r`.`student_cid`
			LEFT JOIN `programs` AS `prog` ON `prog`.`id` = `stu`.`program`
			LEFT JOIN `position_list` AS `p` ON `p`.`id` = `r`.`position_id`
			LEFT JOIN `controllers` AS `m` ON `m`.`id` = `r`.`mentor_cid`
			LEFT JOIN `controllers` AS `st` ON `st`.`id` = `r`.`student_cid`
			",
			[
				$array,
				['`s`.`type`', '=', 0]

			],
			$bits
		);
		if($report->count()) {
			if($type == 1) {
				return $report->first();
			} elseif($type == 2) {
				return $report->results();
			}
		}
		return false;
	}

	public function addReport($fields = array()) {
		if(!$this->_db->insert('reports', $fields)) {
			throw new Exception('There was a problem creating a report.');
		}
	}

	public function addNote($fields = array()) {
		if(!$this->_db->insert('notes', $fields)) {
			throw new Exception('There was a problem creating a note.');
		}
	}

	public function addCard($fields = array()) {
		if(!$this->_db->insert('cards', $fields)) {
			throw new Exception('There was a problem creating a card.');
		}
	}


	public function getLatestID($table) {
		$id = $this->_db->query("SELECT MAX(id) AS maxid FROM {$table}");
		if($id->count()) {
			return $id->first()->maxid;
		}
	}

	public function updateSlider($fields, $where) {
		if(!$this->_db->update('report_slider_answers', $fields, $where)) {
			throw new Exception('There was a problem updating that slider.');
		}
		return true;
	}

	public function addSliderAnswers($fields = array()) {
		if(!$this->_db->insert('report_slider_answers', $fields)) {
			throw new Exception('There was a problem creating a report.');
		}
	}

	public function deleteSliderAnswer($where) {
		if(!$this->_db->delete('report_slider_answers', $where)) {
			throw new Exception('There was a problem deleting a slider\'s answer.');
		}
	}

	public function getSliderAnswers($report_id) {
		$sliders = $this->_db->db("SELECT `s`.`id`, `s`.`report_id`, `s`.`slider_id`, `s`.`value`,
			`q`.`id`, `q`.`text`, `q`.`type`
			FROM `report_slider_answers` AS `s`
			LEFT JOIN `report_slider_questions` AS `q` ON `q`.`id` = `s`.`slider_id`
		",
			[
				['`s`.`report_id`', '=', $report_id]
			]
		);
		if($sliders->count()) {
			return $sliders->results();
		}
	}
}