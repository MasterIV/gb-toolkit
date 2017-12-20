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

$types = [
		'.c' => 'source',
		'.map' => 'background',
		'.tiles' => 'sprite'
];


if( $user ) {
	$view = new view('main');
	$module = $_GET['modul'];
	$project = $session->project;


	if( !preg_match('/^[-\w]+(\.[-\w]+)*$/', $module ) || !is_file( 'modules/'.$module.'.php' ))
		$module = 'projects';

	if(empty($project) || !is_dir(PROJECTS_ROOT.'/'.$project)) {
		$module = 'projects';
	} else {
		define('PROJECT', PROJECTS_ROOT.'/'.$project.'/');
		$files = [];

		foreach(glob(PROJECT.'*', GLOB_BRACE) as $f) {
			$extension = strrchr($f, '.');
			if(isset($types[$extension])) {
				$fileType = $types[$extension];
				$files[$fileType][] = substr( $f, 1+strrpos( $f, '/' ));
			}
		}

		$id = $_GET['id'];
		if(!empty($id) && (!preg_match('/^[-\w]+(\.[-\w]+)*$/', $id ) || !is_file( PROJECT.$id )))
			throw new Exception('Invalid File!');

		$view->assign('files', $files);
		$view->assign('current', $id);
		$view->assign('module', $module);
	}


	try {
		include( 'modules/'.$module.'.php' );
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
