<?php

if (!empty($_POST['data'])) {
	$tpl = template('code/sprite.tiles');
	file_put_contents(PROJECT . $id, $tpl->render([
			'name' => substr($id, 0, strrpos($id, '.')),
			'data' => encode_image_data($_POST['data'])
	]));

	$view->format = 'plain';
} else {
	$data = file_get_contents(PROJECT . $id);
	preg_match('/\[\]\s*=\s*\{(.+)\};/is', $data, $matches);

	$view->js('js/sprite_encoder.js');
	$view->js('js/sprite_tile.js');
	$view->js('js/sprite_editor.js');

	$view->assign('template', 'sprite.twig');
	$view->assign('data', $matches[1]);
	$view->assign('action', '?modul=sprite&id=' . $id);
	$view->assign('code', '?modul=source&id=' . $id);
}

