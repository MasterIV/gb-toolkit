<?php


class form_field_hidden extends form_field {
	public function __construct($name, $value = NULL) {
		parent::__construct($name, '', $value);
		$this->input->type = 'hidden';
	}

	public function __toString() {
		return (string) $this->input;
	}
}
