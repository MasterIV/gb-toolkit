<?php

class form_field_password extends form_field {
	public function __construct($name, $caption, $value = NULL) {
		parent::__construct($name, $caption, $value);
		$this->input->type = 'password';
	}
}
