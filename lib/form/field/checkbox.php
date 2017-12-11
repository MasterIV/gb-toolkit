<?php

class form_field_checkbox extends form_field {
	public function __construct($name, $caption, $value = NULL) {
		parent::__construct($name, $caption, 1 );
		$this->input->type = 'checkbox';
		if( $value ) $this->input->checked = "checked";
	}
}
