<?php 

require "core/config.php";
require "core/functions.php";
startSession();

$base = getBaseUrlPath() . "api/";
$route = $_SERVER['REQUEST_URI'];
$pos = strpos($route, $base);
if ($pos !== false) {
	$route = substr($route, $pos + strlen($base));
}
$route = preg_replace("/\?.*/", "", $route);
$parts = explode("/", $route);

if (count($parts) != 2) {
	notFound();
}
$module = $parts[0];
$action = $parts[1];

require "core/db/dbconnector.php";
require 'core/allow.php';
require "core/log.php";

if (file_exists(__DIR__."/core/api/".$module."/".$action.".php")) {
	$db = new DBConnector($module);
	require("core/api/".$module."/".$action.".php");
	exit;
}

notFound();
