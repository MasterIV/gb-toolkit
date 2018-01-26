<?php

$templates = globFiles('templates/*');
$projects = db()->query("SELECT p.*, c.rights FROM projects p 
				LEFT JOIN collaborators c ON c.project = p.id
				WHERE p.owner = %d OR c.user = %d",
		$user->id, $user->id)->assocs('id');

if(!empty($_POST['project_name']) && !empty($_POST['project_template'])) {
	$name = $_POST['project_name'];
	$tpl = $_POST['project_template'];

	if(!in_array($tpl, $templates))
		throw new exception_user("Invalid Project template");
	if( !preg_match('/^[-\w]+$/', $name) || in_array($name, $projects))
		throw new exception_user('Invalid Project Name!');

	 db()->insert('projects', [
			'owner' => $user->id,
			'name' => $name,
			'description' => $_POST['project_description']
	]);

	mkdir(PROJECTS_ROOT.'/'.$name);
	foreach(glob('templates/'.$tpl.'/*') as $f)
		copy($f, PROJECTS_ROOT.'/'.$name.strrchr($f, '/'));

	rename(PROJECTS_ROOT.'/'.$name.'/'.$tpl.'.c', PROJECTS_ROOT.'/'.$name.'/'.$name.'.c');
	$project = db()->insert_id;
}

$project = $_GET['project'];
if(!empty($project) && isset($projects[$project])) {
	$session->project = $project;
	throw new redirect('?modul=overview');
}

$view->assign('projects', db()->get('projects', 'owner = '.$user->id));
$view->assign('templates', $templates);
$view->setFile( 'projects' );


