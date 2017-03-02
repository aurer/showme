<?php 

class Requests {
	private static $requests = [];

	// Return all request parameters of get, post and file types
	public function getAll() {
		$this->parseParams('get', $_GET);
		$this->parseParams('post', $_POST);
		$this->parseParams('post', $_FILES);

		if ($requests) {
			// Sort by parameter name
			usort($this->requests, function($a, $b){
				return $a->name > $b->name;
			});

			// Remove empty items
			$this->requests = array_filter($this->requests);
		}

		return $this->requests;
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

	// Parse request parameters of specified type
	private function parseParams($type, $params) {
		foreach($params as $name => $value) {
			$request = new stdClass();
			$request->type = $type;
			$request->name = htmlspecialchars($name);
			$request->value = $this->parseValue($value);
			$this->requests[] = $request;
		}
	}
}