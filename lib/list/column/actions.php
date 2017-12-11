<?php

class list_column_actions extends list_column {
	protected $options = array();

	public function __construct($caption, $data = 'id' ) {
		parent::__construct($caption, $data);
	}

	/**
	 * Add an Option
	 * @param string $icon
	 * @param string $title
	 * @param string $link
	 * @param boolean $ask
	 * @param string $onclick
	 * @return list_column_actions
	 */
	public function add( $link, $param, $title, $icon, $ask = false, $onclick = "" ) {
		$link = $link instanceof html_link ? clone $link : new html_link( $link );
		$link->title = $title;

		if( $ask ) $link->onclick = "return window.confirm( 'Sind Sie sich sicher?');";
		elseif( $onclick ) $link->onclick = $onclick;

		$this->options[] = array( $link, $param, '<img src="'.$icon.'" border="0" alt="'.$title.'" />' );
		return $this;
	}


	public function cell( $row ) {
		$result = ' ';

		foreach( $this->options as $op ) {
			$result .= $op[0]->get( $op[2], array( $op[1] => $row[$this->data] )).' ';
		}

		return $result;
	}
}
