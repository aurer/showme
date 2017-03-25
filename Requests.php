<?php

class Requests {
	private $requests = [];
	public $formSubmitsTo = "";

	public function __construct() {
		$this->parseParams('get', $_GET);
		$this->parseParams('post', $_POST);
		$this->parseParams('file', $_FILES);
	}

	// Return all request parameters of get, post and file types
	public function getAll() {

		// Sort by parameter name
		usort($this->requests, function($a, $b){
			return $a->name > $b->name;
		});

		$requests = array_filter($this->requests, function($req){
			return ($req->name != 'formSubmitsTo') && $req->name != '';
		});

		return $requests;
	}

	public function getAction() {
		return array_reduce($this->requests, function($carry, $item){
			if ($item->name == 'formSubmitsTo' && is_string($item->value)) {
				$carry = $item->value;
			}
			return $carry;
		});
	}

	// Escape and manage values
	private function parseValue($value) {
		if (is_array($value)) {
			$values = [];
			foreach ($value as $key => $val) {
				$values[$key] = $this->parseValue($val);
			}
			return $values;
		} else {
			return htmlspecialchars($value);
		}
	}

	private function parseFileValue($value) {
		$values = [];
		foreach ($value as $key => $val) {
			if (in_array($key, ['error', 'tmp_name'])) {
				// continue;
			}

			$values[$key] = $this->parseValue($val);
		}
		return $values;
	}

	// Parse request parameters of specified type
	private function parseParams($type, $params) {
		foreach($params as $name => $value) {
			$request = new stdClass();
			$request->type = $type;
			$request->name = htmlspecialchars($name);
			if ($type == 'file') {
				$request->value = $this->parseFileValue($value);
			} else {
				$request->value = $this->parseValue($value);
			}
			$this->requests[] = $request;
		}
	}
}
