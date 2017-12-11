<?php

class form_field_text extends form_field {
	public function __construct($name, $caption, $value = NULL) {
		parent::__construct($name, $caption, $value);
		$this->input->type = 'text';
	}
}
