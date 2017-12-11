<?php

class widget_menu extends html_tag {
	protected $link;

	public function __construct( $link ) {
		parent::__construct( 'ul' );
		$this->class = 'nav nav-tabs nav-stacked';
		$this->link = $link instanceof html_link ? $link : new html_link( $link );
	}

	protected function wrap( $content, $active = false ) {
		$this->append(($active?'<li class="active">':'<li>').$content.'</li>');
	}

	public function add( $caption, $args, $active = false ) {
		$this->wrap( $this->link->get( $caption, $args ), $active);
	}

	public function action( $caption, $action ) {
		$this->add( $caption, array( 'action' => $action ));
	}

	public function js( $caption, $js, $active = false  ) {
		$link = clone $this->link;
		$link->onclick = 'return '.$js;
		$this->wrap( $link->get( $caption ), $active );
	}
}
