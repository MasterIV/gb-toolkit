<?php

class html_standalone  {
	protected $attributs = array();
	protected $tagname;

	public function __construct( $tagname ) {
		$this->tagname = $tagname;
	}

	/**
	 * Static constructor for method chaining
	 * @param string $tagname
	 * @return html_tag
	 */
	public static function create( $tagname ) {
		return new self( $tagname );
	}

	/**
	 * Generic setter for attributs
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value) {
		$this->attr( $name, $value );
	}

	/**
	 * Generic getter for attributs
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name) {
		return $this->attributs[$name];
	}

	/**
	 * Setter for attributs
	 * @param string $name
	 * @param string $value
	 * @return html_standalone
	 */
	public function attr( $name, $value ) {
			$this->attributs[$name] = $value;
			return $this;
	}

	/**
	 * Converts the object into html string
	 * @return string
	 */
	public function __toString() {
		$result = '<'.$this->tagname;
		foreach( $this->attributs as $name => $value )
			$result .= ' '.$name.'="'.htmlspecialchars( $value ).'"';
		return $result.'>';
	}
}