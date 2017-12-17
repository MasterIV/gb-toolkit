<?php

$types = ['source', 'sprite'];
function put($type, $name, $data) {
	$tpl = template('code/' . $type . '.c');
	file_put_contents(PROJECT . $name . '.c', $tpl->render($data));
	$tpl = template('code/' . $type . '.h');
	file_put_contents(PROJECT . $name . '.h', $tpl->render($data));
}

if (!empty($_POST['file_name']) && in_array($_POST['file_type'], $types)) {
	if (!preg_match('/^[-\w]+(\.[-\w]+)*$/', $_POST['file_name']))
		throw new Exception('Invalid File!');
	if(is_file( PROJECT.$_POST['file_name'].'.c' ))
		throw new exception_user('File already exists: '.$_POST['file_name']);

	$spriteRow = "\t" . str_repeat('0x00, ', 8);

	put($_POST['file_type'], $_POST['file_name'], [
			'name' => $_POST['file_name'],
			'data' => trim($spriteRow . "\n" . $spriteRow, ', ')
	]);

	throw new redirect('?modul=' . $_POST['file_type'] . '&id=' . $_POST['file_name']);
} else {
	throw new redirect('?modul=source&id=' . $project);
}