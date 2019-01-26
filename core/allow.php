<?php


/**
* Allow
*/
class Allow
{
	/**
	 * Allow access if logged in
	 */
	public static function LoggedIn() {
		if (empty($_SESSION['current_user'])) {
			notfound();
		}
	}


	/**
	 * Allow access if user has some of these roles
	 */
	public static function Roles($roles) {
		self::LoggedIn();
		$currentRole = $_SESSION['current_user']['role'];
		if (is_array($roles) && in_array($currentRole, $roles)) {
			return true;
		}
		notfound();
	}

	/**
	 * Allow access if user in the list
	 */
	public static function Users($users) {
		self::LoggedIn();
		$userName = $_SESSION['current_user']['user'];
		if (is_array($users) && in_array($userName, $users)) {
			return true;
		}
		notfound();
	}


	/**
	 * Deny Access Response
	 */
	/*
	protected static function DenyAccess() {
		json_response(array(
			'code' => 500,
			'message' => "Access denied"
		));
	}
	*/
}


