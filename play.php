<?php

require 'inc/common.php';

$loader = new template_loader( 'tpl' );
$rom = 'roms/'.$_GET['game'].'.gb';

if( !preg_match('/^[-\w]+$/', $_GET['game']) || !is_file($rom))
	throw new exception_user('Invalid Project Name!');

$view = new view('play');

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

$view->assign('rom', $rom);
$view->display();
