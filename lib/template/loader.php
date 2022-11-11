<?php

class template_loader {
	protected $twig;

	public function  __construct( $p ) {
		$loader = new \Twig\Loader\FilesystemLoader( array( $p ));
		$this->twig = new \Twig\Environment( $loader, array());
		$this->twig->addGlobal( 'base_dir', $p );

		$options = ['filter' => [
				[
						'name' => 'dateformat',
						'callback' => ['template_filter_date', 'format'],
				], [
						'name' => 'datefancy',
						'callback' => ['template_filter_date', 'fancy'],
				], [
						'name' => 'markdown',
						'callback' => ['template_filter_markdown', 'transform',],
				], [
						'name' => 'bbcode',
						'callback' => ['template_filter_bbcode', 'bbcode2html']
				], [
						'name' => 'gravatar',
						'callback' => ['template_filter_gravatar', 'gravatar']
				],
		]];

		foreach($options['filter'] as $filter )
			$this->twig->addFilter( new \Twig\TwigFilter( $filter['name'], $filter['callback'] ));
	}

	public function get( $name ) {
		if( defined( 'IV_SELF' )) $this->twig->addGlobal( 'IV_SELF', IV_SELF );
		if( defined( 'MODUL_SELF' )) $this->twig->addGlobal( 'MODUL_SELF', MODUL_SELF );
		if( defined( 'PAGE_SELF' )) $this->twig->addGlobal( 'PAGE_SELF', PAGE_SELF );
		return $this->twig->load( $name.'.twig' );
	}
}

/**
 * Creates a Template object of the current Template
 * @param string $name Template Name
 * @return \Twig\Template
 */
function template( $name ) {
	return $GLOBALS['loader']->get( $name );
}
