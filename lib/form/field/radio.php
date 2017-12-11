<?php

class form_field_radio extends form_field {
	public function __construct($name, $caption, $options, $value = NULL) {
		$this->label = html_tag::create('div')->append( $caption );
		$this->label->class = 'form_label';
		$this->input = array();

		foreach( $options as $k => $v ) {
			$id = 'form_field_'.self::$count++;

			$label = html_tag::create('label')->append( $v );
			$label->for = $id;

			$input = html_standalone::create('input');
			$input->type = 'radio';
			$input->id = $id;
			$input->value = $k;
			$input->name = $name;

			if( $k == $value )
				$input->checked = true;

			$this->input[] = array( $input, $label );
		}
	}

	public function input($attr, $value) {
		foreach( $this->input as $i ) $i[0]->attr($attr, $value);
	}

	public function __toString() {
		$inputs = '';
		foreach( $this->input as $i ) $inputs .= $i[0].$i[1].'<br>';
		return '<div class="form_field">'.$this->label.'<div class="form_input">'.$inputs.'</div></div>';
	}
}
