<?php

// Ne Menge geraffel
require 'inc/common.php';
define( 'IV_SELF', 'index.php?' );
define( 'ROOT', __DIR__ );

// User ermitteln
if( !$user = $session->relogin())
	$user = $session->user();

// template loader
$loader = new template_loader( 'tpl' );

if( $user ) {
	$view = new view('main');
	$modul = $_GET['modul'];
	$project = $session->project;


	if( !preg_match('/^[-\w]+(\.[-\w]+)*$/', $modul ) || !is_file( 'moduls/'.$modul.'.php' ))
		$modul = 'projects';

	if(empty($project) || !is_dir(PROJECTS_ROOT.'/'.$project)) {
		$modul = 'projects';
	} else {
		$files = [];
		define('PROJECT', PROJECTS_ROOT.'/'.$project.'/');

		foreach(glob(PROJECT.'*.{c,map}', GLOB_BRACE) as $f) {
			$content = file_get_contents($f);
		
			$fileType = 'source';
			if(strrchr($f, '.') == '.map')
				$fileType = 'map';
			if(strpos($content, 'Tile Source File')) 
				$fileType = 'sprite';
			   
			$files[] = [
					'type' => $fileType,
					'name' => substr( $f, 1+strrpos( $f, '/' ), -1*strlen(strrchr( $f, '.')))
			];
		}

		if(!empty($_GET['id']) && (!preg_match('/^[-\w]+(\.[-\w]+)*$/', $_GET['id'] ) || !is_file( PROJECT.$_GET['id'].'.c' )))
			throw new Exception('Invalid File!');

		$view->assign('files', $files);
	}


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
