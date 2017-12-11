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

$path = PROJECTS_ROOT.'/'.$project;
chdir($path);
$result = shell_exec(COMPILER_PATH." -o $project.gb $project.c");
chdir(ROOT);

$rom = $path.'/'.$project.'.gb';
$view->assign('template', 'emulator.twig');
$view->assign('rom', $rom);
