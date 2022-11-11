<?php

class mysql_result implements Iterator {
	protected $res;
	protected $row = 0;
	protected $max = 0;

	public function  __construct( mysqli_result $res ) {
		$this->res = $res;
		$this->max = $this->num_rows();
	}

	/**
	 * Returns the number of rows found
	 * @return int
	 */
	public function num_rows() {
		return $this->res->num_rows;
	}

	/**
	 * Fetches a record as associative array
	 * @return array
	 */
	public function assoc() {
		return $this->res->fetch_assoc();
	}

	/**
	 * Fetches a record as numeric array
	 * @return array
	 */
	public function row() {
		return $this->res->fetch_row();
	}

	/**
	 * Fetches a record as object
	 * @return stdclass
	 */
	public function object($class_name = null) {
		return $class_name ? $this->res->fetch_object($class_name) : $this->res->fetch_object();
	}

	/**
	 * Fetches a singe value
	 * @param int $key column number
	 * @return mixed
	 */
	public function value( $key = 0 ) {
		$erg = $this->row();
		return $erg[$key];
	}

	/**
	 * Fetch all records in two dimensional accociative array
	 * @param string $key
	 * @return array
	 */
	public function assocs( $key = NULL ) {
		$erg = array();

		if( $key ) while( $row = $this->assoc()) $erg[$row[$key]] = $row;
		else while( $row = $this->assoc()) $erg[] = $row;

		return $erg;
	}


	/**
	 * Fetch all records in two dimensional numeric array
	 * @param int $key
	 * @return array
	 */
	public function rows( $key = NULL ) {
		$erg = array();

		if( $key ) while( $row = $this->row()) $erg[$row[$key]] = $row;
		else while( $row = $this->row()) $erg[] = $row;

		return $erg;
	}


	/**
	 * Fetch all records in array of objects
	 * @param string $key
	 * @return array
	 */
	public function objects( $key = NULL, $class_name = null) {
		$erg = array();

		if( $key ) while( $row = $this->object($class_name)) $erg[$row->{$key}] = $row;
		else while( $row = $this->object($class_name)) $erg[] = $row;

		return $erg;
	}

	public function values( $key = 0 ) {
		$erg = array();

		while( $row = $this->row())
			$erg[] = $row[$key];

		return $erg;
	}

	public function relate( $value = 'name', $key = 'id' ) {
		$erg = array();

		while( $row = $this->assoc())
			$erg[$row[$key]] = $row[$value];

		return $erg;
	}

	public function current() : mixed {
		return $this->assoc();
	}

	public function key() : mixed {
		return $this->row;
	}

	public function next() : void {
		$this->row++;
	}

	public function rewind() : void {
		$this->row = 0;
		$this->res->data_seek( 0 );
	}

	public function valid() : bool {
		return $this->row < $this->max;
	}
}
