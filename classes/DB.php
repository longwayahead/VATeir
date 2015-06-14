<?php
class DB {
	public static $instance = null;

	private  	$_pdo = null,
				$_query = null,
				$_error = false,
				$_results = null,
				$_count = 0,
				$_whereString = null;


	private function __construct() {
		try {
			$this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
		} catch(PDOExeption $e) {
			die($e->getMessage());
		}
	}

	public static function getInstance() {
		// Already an instance of this? Return, if not, create.
		if(!isset(self::$instance)) {
			self::$instance = new DB();
		}
		return self::$instance;
	}

	public function query($sql, $params = array()) {
		// print_r($params);
		// echo $sql;

		$this->_results = null;
		$this->_count = 0;
		$this->_whereString = null;
		$this->_error = false;
		if($this->_query = $this->_pdo->prepare($sql)) {
			$x = 1;
			if(count($params)) {
				
				foreach($params as $param) {
					foreach($param as $value) {
						$this->_query->bindValue($x, $value);
						$x++;
					}
				}
			}
			
			if($this->_query->execute()) {
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			} else {
				$this->_error = true;
			}
		}
		
		return $this;
	}

	public function get($table, $where = null, $bits = null) {
		return $this->action('SELECT *', $table, $where, $bits);
	}

	public function delete($table, $where = array()) {
		$act = $this->action('DELETE', $table, $where);
		return $act;
	}

	public function action($action, $table, $whereArr = array()) { //Same as $this->db but without need for table joins etc
		
		$where = $this->where($whereArr);
		
		
		$sql = "{$action} FROM {$table} WHERE {$where[0]}";
		if(!$this->query($sql, array($where[1]))->error()) {
			//print_r($this);
			return $this;
		} else {
			return false;
		}

	}

	public function db($start, $whereArray = array(), $end = null) { //Use this if performing more complex queries than DELETE, SELECT *, INSERT and UPDATE
		$where = $this->where($whereArray);

		$sql = "{$start} WHERE {$where[0]} {$end}";
		if(!$this->query($sql, array($where[1]))->error()) {
			return $this;
		} else {
			return false;
		}

	}

	public function insert($table, $fields = array()) {
		$keys 	= array_keys($fields);
		$values = null;
		$x 		= 1;

		foreach($fields as $value) {
			$values .= "?";
			if($x < count($fields)) {
				$values .= ', ';
			}
			$x++;
		}

		$sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";

		if(!$this->query($sql, [$fields])->error()) {
			return true;
		}

		return false;
	}

	public function update($table, $fields = array(), $whereArray = array()) {
		$set 	= null;
		$x		= 1;
		foreach($fields as $name => $value) {
			$set .= "{$name} = ?";
			if($x < count($fields)) {
				$set .= ', ';
			}
			$x++;
		}

		$where = $this->where($whereArray);

		$binds = array_merge($fields, $where[1]);

		$sql = "UPDATE {$table} SET {$set} WHERE {$where[0]}";
		if(!$this->query($sql, [$binds])->error()) {
			return true;
		}

		return false;
	}

	public function where($whereArr = array()) { //where[0] = sql. where[1] = params for binding
		$whereString = $this->_whereString;
		foreach($whereArr as $key=>$value) {

			if(count($value) === 3) {
				$operators = array('=', '>', '<', '>=', '<=', '<>', 'LIKE');

				$field 		= $value[0];
				$operator 	= $value[1];
				$values[] 	= $value[2];
				if(in_array($operator, $operators)) {
					$whereString .= $field . $operator . "?";
				}
			}
			if($key != array_search(end($whereArr), $whereArr)) {
				$whereString .= " AND ";
			}
		}
		$where[0] = $whereString;
		$where[1] = $values;
		return $where;
	}

	public function results() {
		// Return result object
		return $this->_results;
	}

	public function first() {
		return $this->_results[0];
	}

	public function count() {
		// Return count
		return $this->_count;
	}

	public function error() {
		return $this->_error;
	}
}