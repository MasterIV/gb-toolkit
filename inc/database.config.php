<?php

require 'inc/mysql.config.php';
$db = new mysql_connection( $sql_host, $sql_user, $sql_pass, $sql_db );
$db->set_charset("utf8");
