<?php

if(!empty( $_POST['data'])) {
	$tpl = template('code/sprite.c');
	$data = "\t";
	$total = count($_POST['data']);

	foreach($_POST['data'] as $i => $d) {
		$data .= $d;
		if($i < $total-1)
			$data .= ($i+1)%8==0 ? ",\n\t" : ", ";
	}

	file_put_contents(PROJECT.$_GET['id'].'.c', $tpl->render([
			'name' => $_GET['id'], 'data' => $data
	]));

	$view->format = 'plain';
} else {
	$data = file_get_contents(PROJECT.$_GET['id'].'.c');
	preg_match('/\[\]\s*=\s*\{(.+)\};/is', $data, $matches);

	$view->js('js/sprite_encoder.js');
	$view->js('js/sprite_tile.js');
	$view->js('js/sprite_editor.js');

	$view->assign('template', 'sprite.twig');
	$view->assign('data', $matches[1]);
	$view->assign('action', '?modul=sprite&id='.$_GET['id']);
	$view->assign('code', '?modul=source&id='.$_GET['id']);
}

