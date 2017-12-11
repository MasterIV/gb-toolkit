<?php

class html_tag extends html_standalone {
	protected $children = array();

	/**
	 * Static constructor for method chaining
	 * @param string $tagname
	 * @return html_tag
	 */
	public static function create( $tagname ) {
		return new self( $tagname );
	}

	/**
	 * Converts the object into html string
	 * @return string
	 */
	function __toString() {
		return parent::__toString().implode( $this->children )."</{$this->tagname}>";
	}

	/**
	 * Add a child node
	 * @param mixed $child
	 * @return html_tag
	 */
	public function append( $child ) {
		$this->children[] = $child;
		return $this;
	}
}