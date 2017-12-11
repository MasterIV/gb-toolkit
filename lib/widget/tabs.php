<?php

class widget_tabs extends html_tag {
	protected static $tab_count = 1;

	protected $tabs;
	protected $content;

	public function __construct() {
		parent::__construct( 'div' );
		$this->class = 'tabs';

		$this->append( $this->tabs = html_tag::create('ul'));
		$this->tabs->class = 'nav nav-tabs';

		$this->append( $this->content = html_tag::create('div'));
		$this->content->class = 'tab-content';
	}

	public function add( $title, $content = null ) {
			$id = self::$tab_count++;

			$link = html_tag::create('a')->attr('href', '#tabs-'.$id )->append( $title );
			$link->attr( 'data-toggle', 'tab' );
			$this->tabs->append( html_tag::create('li')->append( $link ));

			$data = html_tag::create('div')->attr('id', 'tabs-'.$id );
			$data->class = 'tab-pane';
			if( $content ) $data->append( $content );
			$this->content->append( $data );

			return $data;
	}

	public function count() {
		return count( $this->children ) - 1;
	}
}
