<?php

header('Content-Type: application/json');
$data = new stdClass;

// Add referer if present
if ($requests->getReferer()) {
	$data->referer = $requests->getReferer();
}

// Add action is present
if (!empty($action)) {
	$data->action = $action;
}

// Add parameters
$data->parameters = $parameters;

echo json_encode($data);