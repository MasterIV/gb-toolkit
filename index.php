<?php

// Ne Menge geraffel
require 'inc/common.php';
define( 'IV_SELF', 'index.php?' );

// User ermitteln
if( !$user = $session->relogin( 2 ))
	$user = $session->user( 2 );

// template loader
$loader = new template_loader( 'tpl' );

if( $user ) {
	$view = new view('main');
	$modul = $_GET['modul'];

	if( !preg_match('/^[-\w]+(\.[-\w]+)*$/', $modul ) || !is_file( 'moduls/'.$modul.'.php' ))
		$modul = 'projects';
	if(empty($session->project))
		$modul = 'projects';

	try {
		include( 'moduls/'.$modul.'.php' );
		$view->content( ob_get_clean());
		$view->display();
	} catch( redirect $e ) {
		ob_end_clean();
		header( 'Location: '.$e->getMessage());
	} catch( ErrorException $e ) {
		ob_end_clean();
		echo $e->getMessage();
		echo $e->getTraceAsString();
	} catch( exception_user $e ) {
		ob_end_clean();
		$view->error($e->getMessage());
		$view->content('<p align="center"><button class="btn" onclick="window.history.back()">Zurück</button></p>');
		$view->display();
	} catch( Exception $e ) {
		ob_end_clean();
		$view->error( $e->getMessage().PHP_EOL.$e->getTraceAsString());
		$view->display();
	}
} else {
	$view = new view('login');
	$view->assign( 'submit_url', IV_SELF );

	if( isset( $_POST['admin_name'] ))
		if( !$session->login( $_POST['admin_name'], $_POST['admin_pass'],  $_POST['relogin'] )) {
			$view->error( 'Userdaten ungültig!' );
		} else {
			header( 'LOCATION: index.php' );
			exit();
		}

	$view->display();
}
