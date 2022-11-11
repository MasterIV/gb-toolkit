<?php

/**
	* Diese Exception wird ausgelöst, sobald bei einem MySQL Query ein Fehler auftritt
	* Sie enthält das fehlerhafte Query sowie die verursachte Meldung
	*/
class mysql_exception extends Exception {
	protected $query;
	protected $error;

	function  __construct( $sql, mysqli $db ) {
		parent::__construct( sprintf("SQL Error: %s\nIn the Query: %s", $db->error, $sql), $db->errno );
		$this->error = $db->error;
		$this->query = $sql;
	}

	/**
		* Gibt das Query zurück, welches die Exception ausgelöst hat
		* @return string Query
		*/
	public function getSql() {
		return $this->query;
	}

	/**
		* Gibt die MySQL-Fehlermeldung zurück, welche verursacht wurde
		* @return string MySQL-Fehlermeldung
		*/
	public function getError() {
		return $this->error;
	}
}
