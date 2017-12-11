<?php

abstract class list_renderer extends html_standalone {
	protected $columns = array();
	protected $rownum = 0;

	protected $page = 0;
	protected $total = 0;

	protected $link;
	protected $args = array();

	public $pagesize = 50;
	public $prefix = '';
	public $format = ' %d ';

	public function __construct( $link, $prefix = '', $pagesize = 50 ) {
		parent::__construct('table');
		$this->class = 'table table-striped';

		$this->prefix = $prefix;
		$this->pagesize = $pagesize;
		$this->link = $link instanceof html_link ? $link : new html_link( $link );

		$this->page = intval( $_GET[$this->prefix.'page'] );
		$this->link[$this->prefix.'page'] = $this->page;
	}

	/**
	 * Add a column to the list
	 * @param list_column $col
	 * @return list_column the added column
	 */
	public function unshift( list_column $col ) {
		array_unshift($this->columns, $col );
		return $col;
	}

	/**
	 * Add a column to the list
	 * @param list_column $col
	 * @return list_column the added column
	 */
	public function add( list_column $col ) {
		return $this->columns[] = $col;
	}

	/**
	 * Shortcut to add a text column
	 * @param string $caption
	 * @param string $data
	 * @return \list_column
	 */
	public function text( $caption, $data ) {
		return $this->add( new list_column_text( $caption, $data ));
	}

	/**
	 * Shortcut to add a number column
	 * @param string $caption
	 * @param string $data
	 * @param int $decimals
	 * @return \list_column
	 */
	public function num( $caption, $data, $decimals = 0 ) {
		return $this->add( new list_column_number( $caption, $data, $decimals ));
	}

	/**
	 * Shortcut to add a date column
	 * @param string $caption
	 * @param string $data
	 * @param string $format
	 * @return \list_column
	 */
	public function date( $caption, $data, $format = 'd.m.Y H:i' ) {
		return $this->add( new list_column_date( $caption, $data, $format ));
	}

	/**
	 * Shortcut to add a assoc column
	 * @param string $caption
	 * @param string $data
	 * @param array $options
	 * @return \list_column
	 */
	public function assoc( $caption, $data, $options ) {
		return $this->add( new list_column_assoc( $caption, $data, $options ));
	}

	/**
	 * Shortcut to add a callback column
	 * @param string $caption
	 * @param $callback
	 * @return \list_column
	 */
	public function callback( $caption, $callback ) {
		return $this->add( new list_column_callback( $caption, $callback ));
	}

	/**
	 * Generates the table header
	 * @return string
	 */
	protected function header() {
		$content =  '<thead><tr>';

		foreach( $this->columns as $col )	{
			$content .= '<th>'.$col->header().'</th>';
		}

		return $content.'</tr></thead>';
	}

	/**
	 * Generates a single table row
	 * @param array $row
	 * @return string
	 */
	protected function row( $row ) {
		$content =  '<tr class="row_'.($this->rownum++%2).'">';

		foreach( $this->columns as $col )	{
			$content .= '<td>'.$col->cell( $row ).'</td>';
		}

		return $content.'</tr>';
	}
}
