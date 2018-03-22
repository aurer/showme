<?php
require('Requests.php');
require('cors.php');
$requests = new Requests;
$parameters = $requests->getAll();
$action = $requests->getAction();

if (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] == 'application/json') {
	require('views/json.php');
} else {
	require('views/html.php');
}