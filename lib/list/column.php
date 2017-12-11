<?php

abstract class list_column {
	protected $data;
	protected $caption;

	public function __construct( $caption, $data ) {
		$this->data = $data;
		$this->caption = $caption;
	}

	public function header() {
		return htmlspecialchars($this->caption);
	}

	abstract public function cell( $row );
}
