<?php

function escape_gpc_value(&$value) {
	$value = db()->escape($value);
}
function escape_gpc() {
	array_walk_recursive( $gpc = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST), 'sql_escape_value');
}

function alert( $message, $type = 'error' ) {
	return '<div class="alert alert-'.$type.'"><button type="button" class="close" data-dismiss="alert">&times;</button>'.$message.'</div>';
}

function literal( $value ) {
	return is_array( $value ) ? $value[0] : $value;
}

function globFiles( $pattern, $extension = true ) {
	$glob = glob( $pattern );

	if( !empty( $glob ))
		foreach(  $glob as $file )
			if( $extension ) $result[] = substr( $file, 1+strrpos( $file, '/' ));
			else $result[] = substr( $file, 1+strrpos( $file, '/' ), -1*strlen(strrchr( $file, '.')));

	return $result ? array_combine($result, $result) : array();
}

/**
 * Get DB Connection
 * @return mysql_connection
 */
function db() {
	return $GLOBALS['db'];
}

/**
 * Get Current User
 * @return mixed
 */
function user() {
	return $GLOBALS['user'];
}

/**
 * Returns the id of current user
 * @return int
 */
function userid() {
	return $GLOBALS['user'];
}

/**
 * @return string
 */
function encode_image_data($raw) {
	$raw = json_decode($raw);
	$data = "\t";
	$total = count($raw);

	foreach ($raw as $i => $d) {
		$data .= $d;
		if ($i < $total - 1)
			$data .= ($i + 1) % 8 == 0 ? ",\n\t" : ", ";
	}
	return $data;
}


function valid_name($name) {
	return $name && preg_match('/^[-\w]+(\.[-\w]+)*$/', $name );
}
