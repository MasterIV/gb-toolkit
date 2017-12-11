<?php

class form_field_boxtree extends form_field_checkbox {
	private $subs = array();

	public function sub( $name, $caption, $value ) {
		return $this->subs[] = new form_field_boxtree( $name, $caption, $value );
	}

	public function __toString() {
		return '<div class="control-group">'.$this->label.'<div class="controls">'
						.$this->input.implode('', $this->subs ).'</div></div>';
	}
}