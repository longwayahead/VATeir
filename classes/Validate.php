<?php
class Validate {
	private $_passed = false,
			$_errors = array(),
			$_db = null,
			$_fileTypes = [];

	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function check($source, $items = array()) {
		foreach($items as $item => $rules) {
			foreach($rules as $rule => $rule_value) {

				$value = trim($source[$item]);
				if($rule == 'field_name') {
					$fieldname = $rule_value;
				}

				if($rule === 'required' && $rule_value === true && empty($value)) {
					$this->addError("{$fieldname} is required.");
				} else if (!empty($value)) {

					switch($rule) {
						case 'min':
							if(strlen($value) < $rule_value) {
								$this->addError("{$fieldname} must be a minimum of {$rule_value} characters.");
							}
						break;
						case 'time_less':
							if($value > $source[$rule_value]) {
								$this->addError("Cannot have negative time!");
							}
						break;
						case 'time_same':
							if($value == $source[$rule_value]) {
								$this->addError("There must be a difference in time!");
							}
						break;
						case 'time_now':
							if($rule_value < date('Y-m-d H:i:s')) {
								$this->addError("{$fieldname} cannot be in the past!");
							}
						break;
						case 'max':
							if(strlen($value) > $rule_value) {
								$this->addError("{$fieldname} must be a maximum of {$rule_value} characters.");
							}
						break;
						case 'valemail':
							if($rule === 'valemail' && $rule_value === true && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
								$this->addError("Not a valid email address");
							}
						break;
						case 'matches':
							if($value != $source[$rule_value]) {
								$this->addError("Passwords must match.");
							}
						break;
						case 'unique':
							$check = $this->_db->get('users', array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$fieldname} is already taken.");
							}
						break;
						case 'uniquee':
							$check = $this->_db->get('users', array($item, '=', $value));
							if($check->count()) {
								$this->addError("{$fieldname} is already taken.");
							}
						break;
						case 'fileError':
							if($rule_value != 0) {
								$this->addError("There was an error uploading the file.");
							}
						break;
						case 'fileType':
							$types = ['application/zip', 'application/x-zip-compressed', 'text/plain'];
							if(!in_array($rule_value, $types)) {
								$this->addError("File must be a ZIP.");
							}
						break;
					}

				}

			}
		}

		if(empty($this->_errors)) {
			$this->_passed = true;
		}

		return $this;
	}

	protected function addError($error) {
		$this->_errors[] = $error;
	}

	public function passed() {
		return $this->_passed;
	}

	public function errors() {
		return $this->_errors;
	}
}
