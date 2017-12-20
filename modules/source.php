<?php

if (isset($_POST['source'])) {
	file_put_contents(PROJECT . $id, $_POST['source']);
}

$view->assign('template', 'source.twig');
$view->assign('source', file_get_contents(PROJECT . $id));
$view->assign('action', '?modul=source&id=' . $id);
$view->assign('line', intval($_GET['line']));