<?php

class template_loader {
	protected $twig;

	public function  __construct( $p ) {
		$loader = new Twig_Loader_Filesystem( array( $p, 'assets/template/' ));
		$this->twig = new Twig_Environment( $loader, array());
		$this->twig->addGlobal( 'base_dir', $p );

		$options = iv::get('template');
		foreach($options['filter'] as $filter )
			$this->twig->addFilter( new Twig_SimpleFilter( $filter['name'], $filter['callback'] ));
	}

	public function get( $name ) {
		if( defined( 'IV_SELF' )) $this->twig->addGlobal( 'IV_SELF', IV_SELF );
		if( defined( 'MODUL_SELF' )) $this->twig->addGlobal( 'MODUL_SELF', MODUL_SELF );
		if( defined( 'PAGE_SELF' )) $this->twig->addGlobal( 'PAGE_SELF', PAGE_SELF );
		return $this->twig->loadTemplate( $name.'.twig' );
	}
}

/**
 * Creates a Template object of the current Template
 * @param string $name Template Name
 * @return Twig_Template
 */
function template( $name ) {
	return iv::get( 'loader' )->get( $name );
}
