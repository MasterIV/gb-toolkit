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

$spec = array(
		0 => array("pipe", "r"),  // stdin
		1 => array("pipe", "w"),  // stdout
		2 => array("pipe", "w"),  // stderr
);

chdir(PROJECT);
$process = proc_open(COMPILER_PATH." -o $project.gb $project.c", $spec, $pipes);
chdir(ROOT);

$stdout = stream_get_contents($pipes[1]);
$stderr = stream_get_contents($pipes[2]);
fclose($pipes[1]);
fclose($pipes[2]);

//preg_match()

$error = preg_replace(
		'/([\w\.]+).c\((\d+)\):error/',
		'<a href="?modul=source&id=$1&line=$2">$0</a>',
		$stderr);

if(empty($stderr))
	copy( PROJECT.$project.'.gb', 'roms/'.$project.'.gb');

$view->assign('error', $error);
$view->assign('template', 'emulator.twig');
$view->assign('rom', 'roms/'.$project.'.gb');
