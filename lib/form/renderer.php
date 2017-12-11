<?php

class form_renderer extends html_tag {
	protected $buttons;

	public function __construct( $action, $submit = "Speichern", $method = "post" ) {
		parent::__construct('form');
		$this->action = $action;
		$this->method = $method;
		$this->class = 'form-horizontal';

		$this->buttons = html_tag::create('div')->attr( 'class', 'controls' );
		$this->button( $submit, 'submit', 'btn btn-primary' );
	}

	public function field( form_field $f ) {
		$this->append( $f );
		return $f;
	}

	public function text( $name, $caption, $value = NULL ) {
		return $this->field( new form_field_text( $name, $caption, $value ));
	}

	public function password( $name, $caption, $value = NULL ) {
		return $this->field( new form_field_password( $name, $caption, $value ));
	}

	public function select( $name, $caption, $options, $value = NULL ) {
		return $this->field( new form_field_select( $name, $caption, $options, $value ));
	}

	public function radio( $name, $caption, $options, $value = NULL ) {
		return $this->field( new form_field_radio( $name, $caption, $options, $value ));
	}

	public function textarea( $name, $caption, $value = NULL ) {
		return $this->field( new form_field_textarea( $name, $caption, $value ));
	}

	public function checkbox( $name, $caption, $value = NULL ) {
		return $this->field( new form_field_checkbox( $name, $caption, $value ));
	}

	public function hidden( $name, $value = NULL ) {
		return $this->field( new form_field_hidden( $name, $value ));
	}

	public function upload( $name, $caption, $type = null ) {
		$this->enctype = 'multipart/form-data';
		$field = $this->text($name, $caption);
		$field->input( 'type', 'file' );
		if( $type ) $field->input( 'accept', $type );
		return  $field;
	}

	public function button( $value, $type = 'button', $class = 'btn' ) {
		$button = html_standalone::create('input')->attr('type', $type)->attr('value', $value)->attr('class', $class);
		$this->buttons->append( $button );
		return $button;
	}

	public function linkbutton( $value, $link, $class = 'btn' ) {
		$button =  html_standalone::create('input')->attr('type', 'button')->attr('value', $value)->attr('class', $class);
		$this->buttons->append( $button->attr( 'onclick', "location.href = '{$link}'" ));
		return $button;
	}

	/**
	 * Converts the object into html string
	 * @return string
	 */
	public function __toString() {
		$this->append( html_tag::create('div')->attr( 'class', 'control-group' )->append( $this->buttons ) );
		return parent::__toString();
	}
}
