<?php

define('DS', DIRECTORY_SEPARATOR);

class Requests {
	public $formSubmitsTo;
	public $cachedRequestAge;
	public $referer;

	private $requests = [];
	private $is_cache = false;
	private $cached_file_dir;

	public function __construct() {
		// Ensure cache dir exists
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

		var_dump($_SERVER);

		// Parse request
		// $this->parseParams('get', $this->getRealInput('GET'));
		// $this->parseParams('post', $this->getRealInput('POST'));
		// $this->parseParams('file', $_FILES);
		//
		// // Tidy up
		// $this->setReferer();
		// $this->clean_saved_files();
	}

	private function getRealInput($source) {
		if ($source === 'GET' && !empty($_SERVER['QUERY_STRING'])) {
			$pairs = explode("&", $_SERVER['QUERY_STRING']);
		} elseif ($source === 'POST') {
			$pairs = explode("&", file_get_contents("php://input"));
		}	else {
			return [];
		}

		$vars = [];
		foreach ($pairs as $pair) {
			$nv = explode("=", $pair);
			$name = urldecode($nv[0]);
			$value = isset($nv[1]) ? urldecode($nv[1]) : '';
			$vars[$name] = $value;
		}
		return $vars;
	}

	// Return all request parameters of GET, POST and FILE types
	public function getAll() {
		// Sort by parameter name
		usort($this->requests, function($a, $b){
			return $a->name > $b->name;
		});

		// Filter out special parameters
		$requests = array_filter($this->requests, function($req){
			return ($req->name != 'formSubmitsTo') && $req->name != '';
		});

		return $requests;
	}

	// Return the original value for the form 'action'
	public function getAction() {
		return array_reduce($this->requests, function($carry, $item){
			if ($item->name == 'formSubmitsTo' && is_string($item->value)) {
				$carry = $item->value;
			}
			return $carry;
		});
	}

	// Is this a cached request
	public function isCache() {
		return $this->is_cache;
	}

	// Set referer as long as request is not for cached request
	private function setReferer() {
		if (isset($_SERVER['HTTP_REFERER']) && $this->is_cache === false) {
			$this->referer = $_SERVER['HTTP_REFERER'];
		}
	}

	// Return the referer
	public function getReferer() {
		return $this->referer;
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
	public function load($hash) {
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

	// Build path to cache file
	private function cached_file_path($filename) {
		return $this->cached_file_dir . DS . $filename;
	}

	// Escape and manage key/value pairs taken from request
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

	// Extract key/value pairs from a file upload
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

	// Extract key/value pairs from GET and POST requests
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
