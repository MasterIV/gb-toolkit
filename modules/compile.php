<?php

$view->js('emulator/toolbox.js');
$view->js('emulator/scrollbar.js');
$view->js('emulator/jsgb.cpu.js');
$view->js('emulator/jsgb.memory.js');
$view->js('emulator/jsgb.rom.js');
$view->js('emulator/jsgb.interrupts.js');
$view->js('emulator/jsgb.input.js');
$view->js('emulator/jsgb.lcd.js');
$view->js('emulator/jsgb.timers.js');
$view->js('emulator/jsgb.debugger.js');
$view->js('emulator/jsgb.gameboy.js');

$view->css('emulator/jsgb.styles.css');

function execute( $cmd ) {
	$spec = array(
			0 => array("pipe", "r"),  // stdin
			1 => array("pipe", "w"),  // stdout
			2 => array("pipe", "w"),  // stderr
	);

	$process = proc_open($cmd, $spec, $pipes);

	$stdout = stream_get_contents($pipes[1]);
	$stderr = stream_get_contents($pipes[2]);

	fclose($pipes[1]);
	fclose($pipes[2]);
	proc_close($process);

	return $stdout.$stderr;
}

$settings = json_decode($project['settings'], true);
chdir(PROJECT);

if(!empty($settings['banks'])) {
	$error = execute(COMPILER_PATH." -Wa-l -Wl-m -Wl-j -c -o main.o {$project['name']}.c");
	$files = "main.o";

	for($i=1; $i <= $settings['banks']; $i++) {
		$error .= execute(COMPILER_PATH." -Wa-l -Wl-m -Wl-j -Wf-bo{$i} -c -o bank{$i}.o bank{$i}.c");
		$files .= " bank{$i}.o";
	}

	$error .= execute(COMPILER_PATH." -Wa-l -Wl-m -Wl-j -Wl-yt0x01 -Wl-yo4 -o {$project['name']}.gb {$files}");
	unlink($project['name'].'.map');
} else {
	$error = execute(COMPILER_PATH." -o {$project['name']}.gb {$project['name']}.c");
}

chdir(ROOT);

$error = preg_replace(
		'/([\w\.]+.c)\((\d+)\):error/',
		'<a href="?modul=source&id=$1&line=$2">$0</a>',
		$stderr);

if(empty($stderr))
	copy( PROJECT.$project['name'].'.gb', 'roms/'.$project['name'].'.gb');

$view->assign('error', $error);
$view->assign('template', 'emulator.twig');
$view->assign('rom', 'roms/'.$project['name'].'.gb');
