<?php

class widget_form extends form_renderer {
	public function add( $name, $caption, $value = NULL, $type = "text", $options = array() ) {
		switch( $type ) {
			case 'password':
				$inp = $this->password($name, $caption, $value );
				if( !empty( $options )) $inp->input( 'maxlength', $options );
				break;
			case 'radio':
				$inp = $this->radio($name, $caption, $options, $value);
				break;
			case 'textarea':
				$inp = $this->textarea($name, $caption, $value);
				if( !empty( $options )) $inp->input( 'rows', $options );
				break;
			case 'text':
				$inp = $this->text($name, $caption, $value);
				if( !empty( $options )) $inp->input( 'maxlength', $options );
				break;
			case 'select':
				$inp = $this->select($name, $caption, $options, $value);
				break;
			default:
				$inp = $this->text($name, $caption, $value);
				$inp->input( 'type', $type );
				if( !empty( $options )) $inp->input( 'maxlength', $options );
		}

		return $inp;
	}

	function hr() {
		$this->append( new html_standalone('hr'));
	}

  function get( $submit = "Senden", $name = "formular" ) {
		$this->buttons[0]->value = $submit;
		$this->name = $name;
		return (string) $this;
	}

	 function write( $submit = "Senden", $name = "formular" ) {
		 echo $this->get($submit, $name);
	 }
}
