<?php
define('DS', DIRECTORY_SEPARATOR);

class Requests {
	private $requests = [];
	public $formSubmitsTo;
	public $cachedRequestAge;
	public $referer;

	private $is_cache = false;
	private $cached_file_dir;

	public function __construct() {
		// Ensure cach dir exists
		$this->cached_file_dir = sys_get_temp_dir() . DS . 'showme';
		if (!is_dir($this->cached_file_dir)) {
			mkdir($this->cached_file_dir);
		}

		// Get cached request is present
		$args = array_values(array_filter(explode('/', parse_url($_SERVER['REQUEST_URI'])['path'])));
		if (count($args) > 0) {
			$names = explode('-', $args[0]);
			$age = isset($names[1]) ? $names[1] : 0;
			$this->is_cache = true;
			return $this->requests = $this->load($args[0]);
		}

		$this->parseParams('get', $_GET);
		$this->parseParams('post', $_POST);
		$this->parseParams('file', $_FILES);
		$this->setReferer();
		$this->clean_saved_files();
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

	public function isCache() {
		return $this->is_cache;
	}

	private function setReferer() {
		if (isset($_SERVER['HTTP_REFERER']) && $this->is_cache === false) {
			$this->referer = $_SERVER['HTTP_REFERER'];
		}
	}

	// Save request into cahched file
	public function save() {
		$data = serialize($this->requests);
		$filename = md5($data);
		$filepath = $this->cached_file_path($filename);
		file_put_contents($filepath, $data);
		return $filename;
	}

	// Load a saved request from cached file
	private function load($hash) {
		$filepath = $this->cached_file_path($hash);
		if (file_exists($filepath)) {
			return unserialize(file_get_contents($filepath));
		}
		return array();
	}

	// Remove cached files more than 10 minutes old
	private function clean_saved_files() {
		foreach (scandir($this->cached_file_dir) as $filename) {
			$file = realpath($this->cached_file_dir . DS . $filename);
			if (is_file($file)) {
				$age = time() - filectime($file);
				if ($age > 60*10) {
					unlink($file);
				}
			}
		}
	}

	private function cached_file_path($filename) {
		return $this->cached_file_dir . DS . $filename;
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
				continue;
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
