<?php

abstract class rights_abstract {
	protected $arguments;
	public $always = array();

	public function __construct( $arguments, $always ) {
		$this->arguments = $arguments;
		if( $always ) $this->always = array_flip( $always );
	}

	public  function flags( $key, $value, $sysadmin ) {
		return array();
	}

	public function flagNames( $key ) {
		return array();
	}

	public abstract function keys();
}
