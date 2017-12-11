<?php

class list_column_assoc extends list_column {
	protected $options;

	public function __construct($caption, $data, $options) {
		parent::__construct($caption, $data);
		$this->options = $options;
	}

	public function cell($row) {
		$index = $row[$this->data];
		if( !isset( $this->options[$index] )) return htmlspecialchars( $index );
		else return htmlspecialchars( $this->options[$index] );
	}
}
