<?php

if (isset($_POST['source'])) {
	preg_match_all('/\s*(\w+\s+\w+\s*\([\w\s,\*]*\))\s*\{/is', $_POST['source'], $headers);
	preg_match_all('/^#include .*$/im', $_POST['source'], $includes);

	$tpl = template('code/source.h');
	file_put_contents(PROJECT . $_GET['id'] . '.c', $_POST['source']);
	file_put_contents(PROJECT . $_GET['id'] . '.h', $tpl->render([
			'headers' => array_map('trim', $headers[1]),
			'includes' => $includes[0]
	]));
}

$view->assign('template', 'source.twig');
$view->assign('source', file_get_contents(PROJECT . $_GET['id'] . '.c'));
$view->assign('action', '?modul=source&id=' . $_GET['id']);
$view->assign('line', intval($_GET['line']));