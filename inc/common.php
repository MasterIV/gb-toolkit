<?php

// Error Reporting Konfigurieren
define( 'ERROR_LEVEL', E_ALL ^ E_NOTICE);
error_reporting(ERROR_LEVEL);

@ini_set( 'display_errors', 1 );
@ini_set( 'register_globals', 'off' );

function errorExceptionHandler($errno, $errstr, $errfile, $errline) {
	if( $errno & ERROR_LEVEL )
		throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}

set_error_handler('errorExceptionHandler');

// Charset Header
header( 'Content-Type: text/html; charset=UTF-8' );

// Autoloading für Klassen
function ivAutoloader($class) {
	$file = 'lib/' . str_replace( '_', '/', $class ) . '.php';
	if( file_exists( $file )) require( $file );
}

spl_autoload_register('ivAutoloader');

// Magic Quotes entfernen
if( get_magic_quotes_gpc()) {
	function strip_quotes(&$value) { $value = stripslashes($value); }
	$gpc = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
	array_walk_recursive($gpc, 'strip_quotes');
}

// Funktion
require 'inc/functions.php';
require 'inc/settings.config.php';

try {
	// Datenbankverbindung herstellen
	require 'inc/database.config.php';
} catch (Exception $e) {
	// Datenbankzugangsdaten sollen nicht als Stacktrace ausgegeben werden
	die('Could not connect to database');
}

// init session
$session = new session_iv( 'IVSESSID' );

// Sitzung beenden
if( isset( $_GET['logout'] ))
	$session->logout();
