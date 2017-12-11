<?php

if(PHP_SAPI != 'cli') die('Please run this script in cli mode only');

// Autoloading für Klassen
function __autoload( $class ) {
	$file = 'lib/' . str_replace( '_', '/', $class ) . '.php';
	if( file_exists( $file )) require( $file );
}

// Funktion
require 'inc/functions.php';
// Datenbankverbindung herstellen
require 'inc/database.config.php';

if( empty( $argv[1] ) || empty( $argv[2] )) {
	echo "Please provide username and password";
} else {

	$db->insert('user_data', array(
			'name' => $argv[1],
			'email' => $argv[3],
			'pass_salt' => $salt = uniqid(),
			'pass_hash' => md5($salt . md5($argv[2] . $salt)),
			'type' => 7
	));
}