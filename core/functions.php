<?php 

if (DEBUG != 0) {
	ini_set("display_errors", 1);
}


/**
 * Get Base URL Path
 */
function getBaseUrlPath() 
{
	$currentPath = $_SERVER['PHP_SELF']; 
	$pathInfo = pathinfo($currentPath); 
	if ($pathInfo['dirname'] == '\\' || $pathInfo['dirname'] == '/') {
		return "/";
	}
	return $pathInfo['dirname']."/";
}


/**
 * Start Session
 */
function startSession() {
	session_set_cookie_params(0,getBaseUrlPath()); 
	session_name(APPNAME);
	session_start();
}


/**
 * Debug Functions
 */
function pr($data) 
{
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}


/**
 * Generate JSON Response
 */
function json_response($data) {
  header('Content-Type: application/json');
  //header('Access-Control-Allow-Origin: *', false);
  echo json_encode($data);
  exit;
}


/**
 * return JSON error message
 */
function return_error($msg, $code = 1) {
	if (DEBUG != 0) {
		json_response(array(
			'err' => $code,
			'msg' => $msg,
			'sql' => !empty($GLOBALS['db']) ? $GLOBALS['db']->lastSQL : null
		));
	}

	json_response(array(
		'err' => $code,
		'msg' => $msg
	));
}


/**
 * return JSON data
 */
function return_data($data, $msg = "") {
	if (DEBUG != 0) {
		json_response(array(
			'data' => $data,
			'msg' => $msg,
			'sql' => !empty($GLOBALS['db']) ? $GLOBALS['db']->lastSQL : null
		));
	}

	json_response(array(
		'data' => $data,
		'msg' => $msg
	));
}
function return_dl($data, $msg) {
	Log::Write($msg, !empty($GLOBALS['db']) ? $GLOBALS['db']->lastSQL : null);
	return_data($data, $msg);
}


/**
 * return JSON message
 */
function return_msg($msg) {
	if (DEBUG != 0) {
		json_response(array(
			'msg' => $msg,
			'sql' => !empty($GLOBALS['db']) ? $GLOBALS['db']->lastSQL : null
		));
	}

	json_response(array(
		'msg' => $msg
	));
}
function return_ml($msg) {
	Log::Write($msg, !empty($GLOBALS['db']) ? $GLOBALS['db']->lastSQL : null);
	return_msg($msg);
}






/**
 * Not found and exit
 */
function notFound() {
	include "404.php";
	exit;
}






/**
 * Build WHERE conditions string with array of key/values
 */
function where($condArray, $op) {
	$conditions = "";
	$numConds = count($condArray);
	$index = 0;
	foreach ($condArray as $key => $value) {
		$index++;
		$conditions .= $key."='".$value."'";
		if ($index != $numConds) {
			$conditions .= " $op ";
		}
	}
	return $conditions;
}


/**
 * Return the array only with the given keys
 */
function filter($arr, $keys) {
	$result = array();
	foreach ($arr as $key => $value) {
		if (in_array($key, $keys)) {
			$result[$key] = $value;
		}
	}
	return $result;
}

/**
 * Return the array of data only with the given keys
 */
function pluck($arr, $keys) {
	$result = array();
	foreach ($arr as $row) {
		$result[] = filter($row, $keys);
	}
	return $result;
}