<?php

class form_field {
	protected $label;
	protected $input;

	protected static $count = 0;

	protected function create_label( $id, $caption ) {
		$this->label = html_tag::create('label')->append( $caption );
		$this->label->for = $id;
		$this->label->class = 'control-label';
	}

	public function __construct( $name, $caption, $value = NULL ) {
		$id = 'form_field_'.self::$count++;
		$this->create_label($id, $caption);

		$this->input = html_standalone::create('input');
		$this->input->id = $id;
		$this->input->value = $value;
		$this->input->name = $name;
	}

	public function input( $attr, $value ) {
		$this->input->attr( $attr, $value );
		return $this;
	}

	public function required() {
		return $this->input("required", "true");
	}

	public function label( $attr, $value ) {
		$this->label->attr( $attr, $value );
		return $this;
	}

	public function __toString() {
		return '<div class="control-group">'.$this->label.'<div class="controls">'.$this->input.'</div></div>';
	}
}
