<?php

class list_column_date extends list_column {
	protected $format;

	public function __construct($caption, $data, $format = 'd.m.Y H:i') {
		parent::__construct($caption, $data);
		$this->format = $format;
	}

	public function cell($row) {
		return $row[$this->data] ? date( $this->format, $row[$this->data] ) : '';
	}
}
