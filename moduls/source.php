<?php

if(isset($_POST['source']))
	file_put_contents(PROJECT.$_GET['id'].'.c', $_POST['source']);

$view->assign('template', 'source.twig');
$view->assign('source', file_get_contents(PROJECT.$_GET['id'].'.c'));
$view->assign('action', '?modul=source&id='.$_GET['id']);
$view->assign('line', intval($_GET['line']));