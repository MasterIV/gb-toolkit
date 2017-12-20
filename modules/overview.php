<?php

$extensions = array_flip($types);
$rows = ['sprite' => 2, 'background' => 45];

if (!empty($_POST['file_name']) && !empty($extensions[$_POST['file_type']])) {
	$id = $_POST['file_name'] . $extensions[$_POST['file_type']];

	if (!preg_match('/^[-\w]+(\.[-\w]+)*$/', $id))
		throw new Exception('Invalid File: '.$id);
	if (is_file(PROJECT . $id))
		throw new exception_user('File already exists: ' . $_POST['file_name']);

	$spriteRow = "\t" . str_repeat('0x00, ', 8) . "\n";
	$data = [
			'name' => $_POST['file_name'],
			'data' => trim(str_repeat($spriteRow, $rows[$_POST['file_type']]), " ,\n"),
			'tiles' => $files['sprite'][0],
			'dimensions' => '20,18'
	];

	$tpl = template('code/' . $_POST['file_type'] . $extensions[$_POST['file_type']]);
	file_put_contents(PROJECT . $id, $tpl->render($data));

	throw new redirect('?modul=' . $_POST['file_type'] . '&id=' . $id);
} else {
	throw new redirect('?modul=source&id=' . $project . '.c');
}