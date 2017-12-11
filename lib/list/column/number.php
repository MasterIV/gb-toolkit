<?php

class list_column_number extends list_column {
	protected $decimals;

	public function __construct($caption, $data, $decimals = 0) {
		parent::__construct($caption, $data);
		$this->decimals = $decimals;
	}

	public function cell($row) {
		return number_format( $row[$this->data], $this->decimals, ',', '.' );
	}
}
