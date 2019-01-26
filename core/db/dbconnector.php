<?php

/**
* Access DBConnector Class
*/
class DBConnector
{

	protected $name;
	protected $db;

	public $lastSQL;

	protected $create = false;

	protected $table;
	protected $parser;
	
	public function __construct($dbName)
	{
		$this->name = $dbName;
		$this->Connect($dbName);
	}

	protected function Connect($dbName) 
	{
		$dbFile = __DIR__ . "/data/$dbName.db";
		$dbFile = str_replace("\\", "/", $dbFile);
		if (!file_exists($dbFile)) {
			file_put_contents($dbFile, "");
			$this->create = true;
		}
		$this->db = new PDO("sqlite:$dbFile");

		$this->CreateTables();
	}

	protected function CreateTables() 
	{
		if ($this->create) {
			require "tables.php";
			if (!empty($tables[$this->name])) {
				foreach ($tables[$this->name] as $tableName => $def) {
					$fields = "";
					foreach ($def as $field => $type) {
						$fields .= $field." ".$type.",";
					}
					$fields = substr($fields, 0, -1);
				}
				$this->db->exec(
					"CREATE TABLE $tableName ($fields)"
				);
			}
			$this->InitializeTables();
		}
	}

	protected function InitializeTables() 
	{
		require "default.php";
		if (!empty($default[$this->name])) {
			foreach ($default[$this->name] as $tableName => $data) {
				foreach ($data as $row) {
					$this->Insert($tableName, $row);
				}
			}
		}
	}

	public function Execute($sql) 
	{
		$this->lastSQL = $sql;
		$data = array();

		$result = $this->db->query($sql);
		if (!empty($result)) {
			require "parser.php";
			$this->parser = $parser;
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$data[] = $this->Parse($row);
			}
		}

		return $data;
	}

	public function Get($table, $conditions = "", $view = "default") 
	{
		$data = array();
		$fields = "*";

		require "views.php";		
		if (!empty($views[$this->name][$table][$view])) {
			$fields = $views[$this->name][$table][$view];
		}

		$sql = "SELECT " . $fields . " FROM $table" . $this->Where($conditions);

		$this->table = $table;
		return $this->Execute($sql);
	}

	public function GetFirst($table, $conditions = "", $view = "default") 
	{
		$data = $this->Get($table, $conditions, $view);

		if (!empty($data[0])) {
			$data = $data[0];
		}

		return $data;
	}

	public function Count($table, $conditions = "") 
	{
		$count = 0;

		$sql = "SELECT COUNT(*) AS Total FROM $table" . $this->Where($conditions);

		$data = $this->Execute($sql);

		if (!empty($data[0]['Total'])) {
			$count = $data[0]['Total'];
		}

		return $count;
	}


	public function GetIndexed($table, $field_id, $conditions = "", $view = "default") 
	{
		$idxData = array();
		$data = $this->Get($table, $conditions, $view);

		if (!empty($data[0][$field_id])) {
			foreach ($data as $row) {
				$key = $row[$field_id];
				$idxData[$key] = $row;
			}
		}

		return $idxData;
	}

	public function Insert($table, $keyvalues) 
	{
		$fields = "'" . implode("','", array_keys($keyvalues)) . "'";
		$values = "'" . implode("','", array_values($keyvalues)) . "'";

		$sql = "INSERT INTO $table ($fields) VALUES ($values)";

		$this->Execute($sql);

		return true;
	}


	public function Update($table, $keyvalues, $conditions = "") 
	{
		$values = "";
		foreach ($keyvalues as $key => $value) {
			$values .= " " . $key . "='" . $value . "',";
		}
		$values = substr($values, 0, -1);

		$sql = "UPDATE $table SET $values" . $this->Where($conditions);

		$this->Execute($sql);

		return true;
	}

	public function Delete($table, $key, $id) 
	{
		$sql = "DELETE FROM $table WHERE $key='$id'";
		$this->Execute($sql);

		return true;
	}

	protected function Where($conditions)
	{
		if (empty($conditions)) {
			return "";
		}

		if (is_array($conditions)) {
			return " WHERE " . where($conditions, "AND");
		}

		return " WHERE $conditions";
	}

	protected function Parse($row) {
		if (empty($this->parser[$this->name][$this->table])) {
			return $row;
		}

		$parser = $this->parser[$this->name][$this->table];

		foreach ($parser as $field => $getData) {
			$row[$field] = $getData($row);
		}

		return $row;
	}

	public function Escape($string) {
		return str_replace("'", '"', $string);
	}

}

