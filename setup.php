<?php

if(PHP_SAPI != 'cli') die('Please run this script in cli mode only');

// Autoloading für Klassen
function ivAutoloader( $class ) {
	$file = 'lib/' . str_replace( '_', '/', $class ) . '.php';
	if( file_exists( $file )) require( $file );
}

spl_autoload_register('ivAutoloader');

// Funktion
require 'inc/functions.php';
// Datenbankverbindung herstellen
require 'inc/database.config.php';

if( empty( $argv[1] )) {
	$db->query("CREATE TABLE IF NOT EXISTS `update_migration` (
				`id` varchar(250) NOT NULL,
				`create_date` int(10) unsigned DEFAULT NULL,
				`create_by` int(10) unsigned DEFAULT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

	foreach( update_migration::listPending() as $migration ) {
		if( !system('php setup.php '.$migration )) {
			break;
		}
	}
} else {
	$migrations = new update_migration();
	$migrations->install( $argv[1] );
	echo "\t {$argv[1]} installed.\n";
}

