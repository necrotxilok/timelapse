<?php 

$tables = array(
	'auth' => array(
		'users' => array(
			// Basic
			'user' => 'TEXT PRIMARY KEY',
			'pass' => 'TEXT',
			// Profile
			'name' => 'TEXT',
			'email' => 'TEXT',
			// System
			'role' => 'TEXT',
			'active' => 'BOOLEAN',
			'lastlogon' => 'DATETIME'
		)
	),
	'timelapse' => array(
		'timing' => array(
			'date' => 'DATE',
			'user' => 'TEXT',
			'hours' => 'INTEGER',
			'created' => 'DATETIME',
			'updated' => 'DATETIME'
		)
	),
	'system' => array(
		'log' => array(
			'datetime' => 'DATETIME PRIMARY KEY',
			'user' => 'TEXT',
			'action' => 'TEXT',
			'sql' => 'TEXT'
		)
	)
);

