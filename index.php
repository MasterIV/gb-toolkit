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

	if( !valid_name( $module ) || !is_file( 'modules/'.$module.'.php' ))
		$module = 'projects';

	// try to load project
	if($session->project)
		$project = db()->query("SELECT p.*, c.rights FROM projects p 
				LEFT JOIN collaborators c ON c.project = p.id
				WHERE p.id = %d AND (p.owner = %d OR c.user = %d)",
				$session->project, $user->id, $user->id)->assoc();

	if(empty($project) || !is_dir(PROJECTS_ROOT.'/'.$project['name'])) {
		$module = 'projects';
	} else {
		define('PROJECT', PROJECTS_ROOT.'/'.$project['name'].'/');
		$settings = json_decode($project['settings'], true);
		$files = [];

		// load project files
		foreach(glob(PROJECT.'*') as $f) {
			$extension = strrchr($f, '.');
			if(isset($types[$extension])) {
				$fileType = $types[$extension];
				$files[$fileType][] = substr( $f, 1+strrpos( $f, '/' ));
			}
		}

		// check current file
		$id = $_GET['id'];
		if(!empty($id) && (!valid_name($id) || !is_file( PROJECT.$id )))
			throw new Exception('Invalid File!');

		// check write permission
		$write_permission = $user->id == $project['owner'] || $project['rights'];
		if(!empty($_POST) && $module != 'projects' && !$write_permission)
			throw new exception_user('No permissions to modify project, please contact project owner.');

		// assign template variables
		$view->assign('files', $files);
		$view->assign('current', $id);
		$view->assign('module', $module);
		$view->assign('writable', $write_permission);
		$view->assign('settings', $settings);
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
		$view->content('<p align="center"><button class="btn" onclick="window.history.back()">Back</button></p>');
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

	if(!empty($_POST['register_name']) && !empty($_POST['register_pass'])) {
		$name = $_POST['register_name'];

		if( !preg_match('/^[-\w]+$/', $name) || db()->user_data->name( $name ))
			$view->error('Invalid Username!');
		elseif($_POST['register_pass'] != $_POST['register_repeat'])
			$view->error('Password and repetition do not match!');
		else {
			$db->insert('user_data', array(
					'name' => $name,
					'email' => $_POST['register_mail'],
					'pass_salt' => $salt = uniqid(),
					'pass_hash' => md5($salt . md5($_POST['register_pass'] . $salt)),
					'type' => 7
			));

			$session->login($name, $_POST['register_pass'], true);
			header( 'LOCATION: index.php' );
			exit();
		}

	}

	$view->display();
}
