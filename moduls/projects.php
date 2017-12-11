<?php

$projects = globFiles(PROJECTS_ROOT.'/*');
$project = $_GET['project'];

if(!empty( $_POST['project_name'])) {
	$name =  $_POST['project_name'];
	if( !preg_match('/^[-\w]+*$/', $name) || in_array($name, $projects))
		throw new exception_user('Invalid Project Name!');

	mkdir(PROJECTS_ROOT.'/'.$name);
	$project = $name;
}

if(!empty($project) && in_array($project, $projects)) {
	$session->project = $project;
	throw new redirect('?modul=overview');
}


$view->assign('template', 'projects.twig');
$view->assign('projects', $projects);


