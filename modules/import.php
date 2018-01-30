<?php

if(!empty($_POST)) {
	if(isset($_POST['save_tiles']) && !empty($_POST['name_tiles'])) {
		$tiles = $_POST['name_tiles'] . '.tiles';
		if(!valid_name($tiles))
			throw new Exception('Invalid File Name: '.$tiles);


		$tpl = template('code/sprite.tiles');
		file_put_contents(PROJECT . $tiles, $tpl->render([
				'name' => $_POST['name_tiles'],
				'data' => $_POST['data_tiles']
		]));
	}

	if(isset($_POST['save_map']) && !empty($_POST['name_map'])) {
		$map = $_POST['name_map'] . '.map';
		if(!valid_name($map))
			throw new Exception('Invalid File Name: '.$map);


		$tpl = template('code/background.map');
		file_put_contents(PROJECT . $map, $tpl->render([
				'name' => $_POST['name_map'],
				'data' => $_POST['data_map'],
				'tiles' => $tiles,
				'dimensions' => $_POST['dimension_map']
		]));
	}

	throw new redirect('?modul=import');
}

$view->js('js/gbtdg.js');
$view->assign('template', 'import.twig');