<?php

class form_field_date extends form_field {
//	public function __construct($name, $caption, $value = NULL) {
//		parent::__construct($name, $caption, date( 'd.m.Y', $value ));
//		$this->input->type = 'text';
//		$this->input->class = 'datepicker';
//	}

	public function __construct($name, $caption, $value = null) {
		$id = 'form_field_' . self::$count++;
		$this->create_label($id, $caption);

		$input = $this->input[] = html_standalone::create('input');
		$input->value = $value;
		$input->type = 'hidden';
		$input->name = $name;
		
		$input = $this->input[] = html_standalone::create('input');
		$input->id = $id;
		$input->type = 'text';
		$input->class = 'datepicker';
		$input->value = $value ? date('d.m.Y', $value) : '';

	}

	public function input($attr, $value) {
		foreach ($this->input as $i)
			$i->attr($attr, $value);
	}

	public function __toString() {
		return '<div class="control-group">'.$this->label . '<div class="controls">' . implode(' ', $this->input) . '</div></div>';
	}
}
