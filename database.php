<?php
define('MYSQL_SERVER', '192.168.0.4:3306');
define('MYSQL_USER', 'a1');
define('MYSQL_PASSWORD', '1');
define('MYSQL_DB', 'vesna');

$con = mysql_connect(MYSQL_SERVER, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB) or die(mysql_error());
mysql_select_db(MYSQL_DB) or die("Cannot select DB");
?>