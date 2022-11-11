<?php

class mysql_table implements IteratorAggregate {
	private $db;
	private $name = '';

	public function  __construct( mysql_connection $db, $name ) {
		$this->db = $db;
		$this->name = $name;
	}

	public function __toString() {
		return $this->name;
	}

	public function getIterator() : Traversable {
		return $this->all();
	}

	/**
	 * Selects a record using a single column
	 * @param string $name
	 * @param array $arguments
	 * @return stdclass
	 */
	public function __call( $name, $arguments ) {
		return $this->row( $arguments[0], $name )->object();
	}

	/**
	 * Returns a list of fields from a table
	 * @return array
	 */
	public function flist() {
		return $this->db->query( "SHOW COLUMNS FROM `{$this->name}`;" )->rows();
	}

	/**
	 * Returns a result containing all records from the table
	 * @return mysql_result
	 */
	public function all() {
		return $this->get( 1 );
	}

	/**
	 * Creates a query and executes it based on the given conditions
	 * @param string $cond
	 * @return mysql_result
	 */
	public function get( $cond ) {
		if(func_num_args() > 1 ) $cond = $this->db->formatSQL( func_get_args());
		return $this->db->query( "SELECT * FROM `{$this->name}` WHERE {$cond}" );
	}

	/**
	 * Selects a single record from a table using a single column primary key
	 * @param string $value
	 * @param string $column
	 * @return mysql_result
	 */
	public function row( $value, $column = 'id') {
		return $this->get( "`$column` = '%s' LIMIT 1", $value );
	}

	/**
	 * Selects all record from a table using a single column primary key
	 * @param string $value
	 * @param string $column
	 * @return mysql_result
	 */
	public function rows( $value, $column = 'id') {
		return $this->get( "`$column` = '%s'", $value );
	}

	/**
	 * Creates and executes a delete-statement
	 * @param string $cond
	 * @return boolean
	 */
	public function del( $cond ) {
		if(func_num_args() > 1 ) $cond = $this->db->formatSQL( func_get_args());
		return $this->db->exec( "DELETE FROM `{$this->name}` WHERE {$cond}" );
	}

	/**
	 * Deletes a record using a single column primary key
	 * @param string $value
	 * @param string $column
	 * @return boolean
	 */
	public function delRow( $value, $column = 'id') {
		return $this->del( "`$column` = '%s' LIMIT 1", $value );
	}

	/**
	 * Creates a new record in a table based on an array of values
	 * @param array $values column => value
	 * @param string $type INSERT or REPLACE
	 * @return boolean
	 */
	public function insert( $values, $type = 'INSERT' ) {
		$values['create_date'] = time();
		if( function_exists( 'userid' ))
			$values['create_by'] = userid();

		$fields = $vals = array();
		foreach( $this->flist() as $f )
			if( isset( $values[$f[0]] )) {
				$fields[] = "`$f[0]`";

				if(empty( $values[$f[0]] ) && $f[2] == 'YES') $vals[] =  "NULL";
				else $vals[] = "'".$this->db->escape( $values[$f[0]] )."'";
			}

		return $this->db->exec( "{$type} INTO `{$this->name}` ( ".implode( ", ", $fields ).") VALUES ( ".implode( ", ", $vals ).");" );
	}

	/**
	 * Updates a table based on an array
	 * @param array $values column => value
	 * @param string $cond
	 * @return boolean
	 */
	public function update( $values, $cond ) {
		if(func_num_args() > 2 ) {
			$args = func_get_args();
			array_shift( $args );
			$cond = $this->db->formatSQL( $args );
		}

		$values['update_date'] = time();
		if( function_exists( 'userid' ))
			$values['update_by'] = userid();

		$ups = array();
		foreach( $this->flist() as $f )
			if( isset( $values[$f[0]] ))
				if( empty( $values[$f[0]] ) && $f[2] == 'YES' ) $ups[] = "`$f[0]` = NULL";
				else $ups[] = "`$f[0]` = '".$this->db->escape( $values[$f[0]] )."'";

		return $this->db->exec( "UPDATE `{$this->name}` SET ".implode( ", ", $ups )." WHERE {$cond}" );
	}

	/**
	 * Creates multiple records in a table based on an array of values
	 * @param array $values column => value
	 * @param string $type INSERT or REPLACE
	 * @return boolean
	 */
	public function multiInsert( $values, $type = 'INSERT' ) {
		if( empty( $values )) return;

		$flist = $vals = array();
		foreach( $this->flist() as $f ) $flist[] = $f[0];

		$possible = array_merge( array_keys( $values[0] ), array( 'create_date', 'create_by' ));
		$fields = array_intersect( $possible, $flist );

		foreach( $values as $row ) {
			$val = array();
			$row['create_date'] = time();
			if( function_exists( 'userid' ))
				$row['create_by'] = userid();

			foreach( $fields as $f )
				$val[] = "'".$this->db->escape( $row[$f] )."'";

			$vals[] = '('.implode(',', $val ).')';
		}

		return $this->db->exec( "{$type} INTO `{$this->name}` ( `".implode( "`, `", $fields )."` ) VALUES ".implode( ", ", $vals ).";" );
	}


	public function multiUpdate( $values ) {
		throw new Exception('to be implemented');
	}

	/**
	 * Updates a record in a table based on an array of values using a single column primary key
	 * @param array $values column => value
	 * @param string $value
	 * @param string $column
	 * @return boolean
	 */
	public function updateRow( $values, $value, $column = 'id' ) {
		return $this->update( $values, "`$column` = '%s'", $value );
	}
}
