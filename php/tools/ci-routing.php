<?php
if (!isset($_SERVER))
	$_SERVER = array();
$_SERVER['CI_ENV'] = 'development';

//$BASE_DIR = __DIR__ . '' ;
$BASE_DIR = __DIR__ . '/../web' ;
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (file_exists($BASE_DIR . $_SERVER['REQUEST_URI']) || file_exists($BASE_DIR.$path) ) {
	return false; // serve the requested resource as-is.
} else {
	// this is the missing piece!
	$_SERVER['SCRIPT_NAME'] = '/index.php'; 
	include_once ($BASE_DIR . '/index.php');
}
