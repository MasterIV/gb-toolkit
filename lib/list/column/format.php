<?php

class list_column_format extends list_column {
	protected $args;

	public function __construct( $caption, $format ) {
		parent::__construct( $caption, $format );
		$this->args = array_slice( func_get_args(), 2 );
	}

	public function cell($row) {
		$args = array();

		foreach( $this->args as $a ) {
			$args[] = $row[$a];
		}

		return vsprintf( $this->data, $args );
	}
}
