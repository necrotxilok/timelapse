<?php 

/**
* Log
*/
class Log
{
	
	public static function Write($action, $sql = "") 
	{
		$db = new DBConnector('system');

		$time = gettimeofday();

		$db->Insert(
			'log', 
			array(
				'datetime' => date('Y-m-d H:i:s.').$time["usec"],
				'user' => !empty($_SESSION['current_user']['user']) ? $_SESSION['current_user']['user'] : null,
				'action' => $action,
				'sql' => $db->Escape($sql)
			)
		);
	}

	public static function GetAll()
	{
		$db = new DBConnector('system');

		return $db->Get('log');
	}

}