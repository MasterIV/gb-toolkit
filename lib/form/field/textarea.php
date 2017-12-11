<?php

class form_field_textarea extends form_field {
	public function __construct($name, $caption, $value = NULL) {
		$id = 'form_field_'.self::$count++;
		$this->create_label($id, $caption);

		$this->input = html_tag::create('textarea');
		$this->input->name = $name;
		$this->input->id = $id;
		$this->input->append( $value );
	}
}
