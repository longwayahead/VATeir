<?php
class Download {
	private $_db;
	public $f = [],
					$oadURL = 'https://api.openaviationdata.com/';

	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function file($file) {
		$this->f = a2o($file);
		$this->f->name = $this->name($this->f->name);
		// $this->f->uniq = uniqid();
		// $ext = new SplFileInfo($this->f->name);
		// $this->f->ext = $ext->getExtension();
		return $this->f;
	}

	public function upload($name) {
		$upload = move_uploaded_file($name, URL . 'uploads/'.$this->f->name);
		if($upload === true) {
			return true;
		} else {
			throw new Exception("There was a problem uploading a file");
		}
	}

	public function name($name) {
		return strtolower(preg_replace('/\s+/', '', $name)); //strip spaces first
	}

	public function cats($id = null) {
		if(isset($id)) {
			$where = "WHERE id = ?";
			$w = $id;
		} else {
			$where = null;
			$w = null;
		}
		$data = $this->_db->query("SELECT * FROM download_categories
									$where
									ORDER BY sort ASC", [[$w]]);
		if($data->count()) {
			return $data->results();
		}
		return false;
	}

	public function sub_cats($category) {
		$data = $this->_db->query("SELECT c.* FROM download_sub_categories c
									WHERE c.category = ?
									ORDER BY c.sort ASC", [[$category]]);
		if($data->count()) {
			return $data->results();
		}
		return false;
	}

		public function sub_cat($category) {
		$data = $this->_db->query("SELECT c.* FROM download_sub_categories c
									WHERE c.id = ?
									ORDER BY c.sort ASC", [[$category]]);
		if($data->count()) {
			return $data->first();
		}
		return false;
	}

	public function get($where, $sc = null) {
		if($sc == null) {
			$w = "d.sub_category";
		} else {
			$w = "d.id";
		}
		$data = $this->_db->query("SELECT d.*, s.id as cat_id, s.name as cat_name, a.first_name, a.last_name FROM download_files d
									LEFT JOIN download_sub_categories s on s.id = d.sub_category
									LEFT JOIN download_categories c on c.id = s.category
									LEFT JOIN controllers a on a.id = d.added_by
									WHERE $w = ?
									ORDER BY c.sort ASC, s.sort ASC", [[$where]]);
		if($data->count()) {
			return $data->results();
		}
		return false;
	}

	public function add($table, $fields = array()) {
		if(!$this->_db->insert($table, $fields)) {
			throw new Exception('There was a problem adding a record.');
		}
	}

	public function edit($table, $fields, $where) {
		if(!$this->_db->update($table, $fields, $where)) {
			throw new Exception('There was a problem updating the record.');
		}
		return true;
	}

	public function unlink($name) {
		if(unlink(URL . 'uploads/' . $name) === true) {
			return true;
		}
		return false;
	}

	public function delete($id) {
		$delete = $this->_db->delete('download_files', [['id', '=', $id]]);
		if($delete) {
			return true;
		} else {
			throw new Exception("There was a problem deleting a download");
		}
	}

	public function cacheFile($file_name, $url) {
		cacheFile(URL . "datafiles/".$file_name, $url);
		$data = json_decode(file_get_contents(BASE_URL . "datafiles/".$file_name));
		return $data;
	}

	public function oadAPI($call = null, $options = null) {
		$apiToken = Config::get('oad/apitoken');
		$url = null;
		$bits = null;
		if(isset($options)) {
			if(isset($options['station'])) {
				$bits .= '&station=' . $options['station'];
			}
		}
		switch($call) {
			case($call == 'tracks'):
				$url = 'v2/routing/oceanic';
				break;
			case($call == 'airac'):
				$url =  'v2/airac/current';
				break;
			case($call == 'metar'):
				$url = 'v1/metar';
				break;
			case($call == 'taf'):
				$url = 'v1/taf';
				break;
			default:
				throw new Exception("Could not get dataset.");
				break;
		}
		if($url != null) {
			if(isset($options['cache'])) {
				$request = $this->cacheFile($call . '.json', $this->oadURL . $url . '?key=' . $apiToken . $bits);
			} else {
				$request = json_decode(file_get_contents($this->oadURL . $url . '?key=' . $apiToken . $bits));
			}

			if($request->code == 200) {
				return $request->data;
			} else {
				throw new Exception("Could not get data.");
			}
		}

	}

	public static function xml2array($url) {
		$xml = simplexml_load_string(file_get_contents($url));
		return json_decode(json_encode($xml), true);

	}

	public function blackswan() {
		$tracks = $this->xml2array('http://blackswan.ch/nat/nat.xml')['track'];
		return $tracks;
	}

}
