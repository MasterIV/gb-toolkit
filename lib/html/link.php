<?php

class html_link extends html_tag implements ArrayAccess {
	protected $link;
	protected $args;

	public function __construct( $link, $caption = NULL ) {
		parent::__construct( 'a' );
		if( $caption ) $this->append( $caption );

		$this->href = $link;
		$this->link = parse_url( $link );
		parse_str( $this->link['query'], $this->args );
	}

	protected function generate() {
		return $this->href =
				((isset( $this->link['scheme'] )) ? $this->link['scheme'] . '://' : '')
				. ((isset( $this->link['user'] )) ? $this->link['user'] . ((isset( $this->link['pass'] )) ? ':' . $this->link['pass'] : '') . '@' : '')
				. ((isset( $this->link['host'] )) ? $this->link['host'] : '')
				. ((isset( $this->link['port'] )) ? ':' . $this->link['port'] : '')
				. ((isset( $this->link['path'] )) ? $this->link['path'] : '')
				. ((isset( $this->link['query'] )) ? '?' . $this->link['query'] : '')
				. ((isset( $this->link['fragment'] )) ? '#' . $this->link['fragment'] : '');
	}


	/**
	 * Creates a Link including the passed arguments
	 * @param array $args
	 * @return string
	 */
	public function get( $caption, $args = array() ) {
		$this->children = array( $caption );
		$this->link['query'] = http_build_query( array_merge( $this->args, $args ), '', '&' );
		$this->generate();
		return $this;
	}

	public function pure( $args = array()) {
		$this->link['query'] = http_build_query( array_merge( $this->args, $args ), '', '&' );
		return $this->generate();
	}

	public function offsetExists( $offset ) {
		return isset( $this->args[$offset] );
	}

	public function offsetGet( $offset ) {
		return $this->args[$offset];
	}

	public function offsetSet( $offset, $value ) {
		$this->args[$offset] = $value;
	}

	public function offsetUnset( $offset ) {
		unlink( $this->args[$offset] );
	}
}
