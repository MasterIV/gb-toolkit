<?php

if(isset($_GET['tiles'])) {
	$data = file_get_contents(PROJECT . $id);
	preg_match('/\[\]\s*=\s*\{(.+)\};/is', $data, $matches);
	$view->content('['.$matches[1].']');
	$view->format = 'plain';
} elseif (!empty($_POST['data'])) {
	$tpl = template('code/background.map');
	file_put_contents(PROJECT . $id, $tpl->render([
			'name' => substr($id, 0, strrpos($id, '.')),
			'data' => encode_image_data($_POST['data']),
			'tiles' => $_POST['tiles'],
			'dimensions' => $_POST['data'] . ',' . $_POST['data']
	]));

	$view->format = 'plain';
} else {
	$data = file_get_contents(PROJECT . $id);

	$m = preg_match('/Dimensions:\s*(\d+)\s*,\s*(\d+)/is', $data, $matches);
	$view->assign('dimensions',  $m ? [$matches[1], $matches[2]] : [20,18]);

	preg_match('/Tiles:\s*([-\w]+(\.[-\w]+))/is', $data, $matches);
	$view->assign('tiles', $matches[1] ?: $files['sprite'][0]);

	preg_match('/\[\]\s*=\s*\{(.+)\};/is', $data, $matches);
	$view->assign('data', $matches[1]);

	$view->js('js/sprite_encoder.js');
	$view->js('js/sprite_tile.js');
	$view->js('js/map_editor.js');

	$view->assign('template', 'background.twig');
	$view->assign('action', '?modul=background&id=' . $id);
	$view->assign('code', '?modul=source&id=' . $id);
}

