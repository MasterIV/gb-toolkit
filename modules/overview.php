<?php

$extensions = array_flip($types);
$rows = ['sprite' => 2, 'background' => 45];
$owner = $project['owner']==$user->id;

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
}

if(!empty($_POST['description'])) {
	db()->id_update('projects', [
			'description' => $_POST['description']
	], $project['id']);

	throw new redirect('?modul=overview');
}

if($owner) {
	if(!empty($_POST['user'])) {
		$collab = db()->id_get('user_data', $_POST['user'], 'name');
		if(empty($collab)) throw new exception_user('User not found.');

		if(db()->get('collaborators', 'user = '.$collab['id'].' AND project = '.$project['id']))
			throw new exception_user('User is already a collaborator.');

		db()->insert('collaborators', [
				'project' => $project['id'],
				'user' => $collab['id']
		]);

		throw new redirect('?modul=overview');
	}

	if(!empty($_GET['remove'])) {
		db()->del('collaborators', 'id = '.intval($_GET['remove']).' AND project = '.$project['id']);
		throw new redirect('?modul=overview');
	}
	if(!empty($_GET['toggle'])) {
		db()->query('UPDATE collaborators SET rights = !rights WHERE id = %d AND project = %d', $_GET['toggle'], $project['id']);
		throw new redirect('?modul=overview');
	}
}


$view->assign('template', 'settings.twig');
$view->assign('project', $project);
$view->assign('owner', $owner);

$view->assign('collaborators', db()->query("SELECT c.*, u.name
		FROM collaborators c JOIN user_data u ON c.user = u.id
		WHERE c.project = %d", $project['id'])->assocs());