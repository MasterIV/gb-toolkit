<?php

/*
For bootstrap grid
Didn't worked very well
Constructor part:
		foreach( func_get_args() as $width ) {
			$col = $this->columns[] = new html_tag('div');
			$col->class = 'span'.$width;
		}

To-String part:
		$result = '<div class="grid">'; // row-fluid
		foreach( $this->columns as $col ) $result .= $col;
		return $result . '</div>';

If you have to specify columns in constructor
	public function __construct( $columns ) {
		for( $i=0; $i<$columns; $i++)
			$this->columns[] = new html_tag('div');
	}
 */

class grid_column extends html_tag {
	public $size;

	public function __construct($size = null) {
		parent::__construct('div');

		if($size) {
			$this->class = 'span'.$size;
			$this->size = $size;
		}
	}

	public function box( $content, $title = "Content Box", $width = NULL ) {
		return $this->append( new widget_box( $content, $title, $width ));
	}

	public function add( $data ) {
		$this->append( $data );
	}
}

class widget_grid extends html_tag implements ArrayAccess {
	public function __construct($sizes = array()) {
		parent::__construct('div');
		$this->class = 'row-fluid';

		foreach($sizes as $offset => $s)
			$this->children[$offset] = new grid_column($s);
	}

	public function offsetExists( $offset ) {
		return isset( $this->children[$offset] );
	}

	public function offsetGet( $offset ) {
		if( isset( $this->children[$offset] )) return $this->children[$offset];
		else	return $this->children[$offset] = new grid_column();
	}

	public function offsetSet( $offset, $value ) {
		return false;
	}

	public function offsetUnset( $offset ) {
		return false;
	}

	function __toString() {
		$remain = 12;
		$cols = 0;

		foreach($this->children as $i => $c)
			if(empty($c->size)) $cols++;
			else $remain -= $c->size;

		foreach($this->children as $i => $c)
			if(empty($c->size)) {
				$c->size = round($remain/$cols);
				$c->class = 'span'.$c->size;
				$remain -= $c->size;
				$cols--;
			}

		return parent::__toString();
	}


}
