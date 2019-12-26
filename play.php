<?php

require 'inc/common.php';

$loader = new template_loader( 'tpl' );
$rom = 'roms/'.$_GET['game'].'.gb';

if( !preg_match('/^[-\w]+$/', $_GET['game']) || !is_file($rom))
	throw new exception_user('Invalid Project Name!');

$view = new view('play');

$view->js('emulator/resize.js');
$view->js('emulator/resampler.js');
$view->js('emulator/swfobject.js');
$view->js('emulator/XAudioServer.js');
$view->js('emulator/GameBoyCore.js');
$view->js('emulator/GameBoyIO.js');
$view->js('emulator/Main.js');

$view->assign('rom', $rom);
$view->display();
