<?php

class widget_liste extends list_array {
	protected $opcol;

	public function __construct( $cols = array()) {
		parent::__construct( defined('MODUL_SELF') ? MODUL_SELF : defined('PAGE_SELF') ? PAGE_SELF : IV_SELF );
		foreach( $cols as $key => $value ) $this->text( $value, $key );
	}

	function addop( $val, $link, $ask = false, $title = "", $onclick = "" ) {
		if( !$this->opcol ) $this->opcol = new list_column_actions('Aktionen');
		$this->opcol->add($icon, $title, $link, $ask, $onclick);
	}

	public function get($data) {
		if( $this->opcol ) $this->add( $this->opcol );
		parent::get($data);
	}

  function write( $values, $width = "100%" ) {
		$this->width = $width;
		$this->display($values);
	}
}