<?php

$view->js('emulator/resize.js');
$view->js('emulator/resampler.js');
$view->js('emulator/swfobject.js');
$view->js('emulator/XAudioServer.js');
$view->js('emulator/GameBoyCore.js');
$view->js('emulator/GameBoyIO.js');
$view->js('emulator/Main.js');

function execute( $cmd ) {
	$spec = array(
			0 => array("pipe", "r"),  // stdin
			1 => array("pipe", "w"),  // stdout
			2 => array("pipe", "w"),  // stderr
	);

	$process = proc_open($cmd, $spec, $pipes);

	$stdout = stream_get_contents($pipes[1]);
	$stderr = stream_get_contents($pipes[2]);

	fclose($pipes[0]);
	fclose($pipes[1]);
	fclose($pipes[2]);

	proc_close($process);

	return $stdout.$stderr;
}

chdir(PROJECT);

if(!empty($settings['banks'])) {
	$error = execute(COMPILER_PATH." -Wa-l -Wl-m -Wl-j -c -o main.o {$project['name']}.c");
	$files = "main.o";

	for($i=1; $i <= $settings['banks']; $i++) {
		$error .= execute(COMPILER_PATH." -Wa-l -Wl-m -Wl-j -Wf-bo{$i} -c -o bank{$i}.o bank{$i}.c");
		$files .= " bank{$i}.o";
	}

	$error .= execute(COMPILER_PATH." -Wa-l -Wl-m -Wl-j -Wl-yt0x01 -Wl-yo4 -o {$project['name']}.gb {$files}");
	if(is_file($project['name'].'.map'))
		unlink($project['name'].'.map');
} else {
	$error = execute(COMPILER_PATH." -o {$project['name']}.gb {$project['name']}.c");
}

chdir(ROOT);

$error = preg_replace(
		'/([\w\.]+.c)\((\d+)\):error/',
		'<a href="?modul=source&id=$1&line=$2">$0</a>',
		$error);

if(empty($error))
	copy( PROJECT.$project['name'].'.gb', 'roms/'.$project['name'].'.gb');

$view->assign('error', $error);
$view->assign('template', 'emulator.twig');
$view->assign('rom', 'roms/'.$project['name'].'.gb');
