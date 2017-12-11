<?php

class form_field_select extends form_field {
	public function __construct($name, $caption, $options, $value = NULL) {
		$id = 'form_field_'.self::$count++;
		$this->create_label($id, $caption);

		$this->input = html_tag::create('select');
		$this->input->name = $name;

		foreach( $options as $k => $v )
			if( $value != $k ) $this->input->append( '<option value="'.htmlspecialchars ($k).'">'.htmlspecialchars($v).'</option>' );
			else $this->input->append( '<option value="'.htmlspecialchars ($k).'" selected>'.htmlspecialchars($v).'</option>' );
	}
}
