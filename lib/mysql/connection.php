<?php

class mysql_connection {
	private $con;

	public function __construct($host, $user, $pass, $db) {
		$this->con = new mysqli($host, $user, $pass, $db);
	}

	/**
	 * Closes the connection on object destruction
	 */
	public function __destruct() {
		$this->con->close();
	}


	public function set_charset($c) {
		$this->con->set_charset($c);
	}

	/**
	 * Creates a teable object for the table that equals the property name
	 * @param string $name
	 * @return mysql_table
	 */
	public function __get( $name ) {
		return $this->t( $name );
	}

	/**
	 * Creates Table Object
	 * @param string $name
	 * @return mysql_table
	 */
	public function t( $name ) {
		return new mysql_table( $this, $name );
	}

	/**
	 * Directly executes SQL
	 * @param string $query
	 * @return mysqli_result
	 * @throws mysql_exception
	 */
	public function exec( $query ) {
		if( $result = $this->con->query( $query )) return $result;
		else throw new mysql_exception( $query, $this->con );
	}

	/**
	 * Formats an SQL
	 * @param array $args First element is the sql an the rest are arguments
	 * @return string
	 */
	public function formatSQL( $args ) {
		$sql = array_shift( $args );

		foreach( $args as &$arg )
			$arg = $this->escape($arg);

		return vsprintf( $sql, $args );
	}

	/**
	 * Executes a query and returns a result set
	 * Additional parameters may be given
	 * @param string $query
	 * @return mysql_result
	 */
	public function query( $query ) {
		if(func_num_args() > 1 )
			$query = $this->formatSQL( func_get_args());

		$res = $this->exec($query);

		if( $res instanceof mysqli_result )
			return new mysql_result( $res);
		else return $res;
	}

	/**
	 * Formats a Statement
	 * Additional parameters may be given
	 * @param string $sql
	 * @return string
	 */
	public function format( $sql ) {
		if( func_num_args() > 1 )
			$sql = $this->formatSQL( func_get_args());
		return $sql;
	}

	/**
	 * Returns the last inserted id
	 * @return int
	 */
	public function id() {
		return $this->con->insert_id;
	}

	/**
	 * Escapes a string
	 * @param string $value
	 * @return string
	 */
	public function escape( $value ) {
		return $this->con->escape_string( $value );
	}



	// Backwards compatibility to be removed
	public function flist( $table ) {
		return $this->t( $table )->flist();
	}

	public function fetch_query() {
		return call_user_func_array( array( $this, 'query' ), func_get_args())->assocs();
	}

	public function get_assoc( $table, $cond = 1, $value = 'name', $key = 'id' ) {
		return $this->t( $table )->get( $cond )->relate($value, $key);
	}

	public function id_select($table, $value, $column = 'id') {
		return $this->t( $table )->row( $value, $column );
	}
	public function id_get($table, $value, $column = 'id') {
		return $this->id_select( $table, $value, $column )->assoc();
	}

	public function select($table, $cond = 1, $limit = NULL, $order = NULL) {
		if( $limit ) $cond .= ' LIMIT '.$limit;
		if( $order ) $cond .= ' ORDER BY '.$order;
		return $this->t( $table )->get( $cond );
	}

	public function get($table, $cond = 1, $limit = NULL, $order = NULL) {
		if( $limit == 1 ) return $this->select($table, $cond, $limit, $order)->assoc();
		else return $this->select($table, $cond, $limit, $order)->assocs();
	}

	public function del( $table, $cond = 1 ) {
		return $this->t( $table )->del( $cond );
	}

	public function id_del($table, $value, $column = 'id') {
		return $this->t( $table )->delRow( $value, $column );
	}

	public function insert($table, array $values, $type = 'INSERT') {
		return $this->t( $table )->insert( $values, $type );
	}

	public function update($table, array $values, $cond = 1) {
		return $this->t( $table )->update( $values, $cond );
	}

	public function id_update( $table, $values, $value, $column = 'id' ) {
		return $this->t( $table )->updateRow( $values, $value, $column );
	}
}
