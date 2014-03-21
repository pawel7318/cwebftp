<?php
if (eregi(basename(__FILE__),$_SERVER['PHP_SELF'])) { header("Location: ../index.php"); die(); }

$db_cfg = array(
	'host'		=> 'localhost',
	'port'		=> '3307',
	'dbname'	=> 'cwebftp',
	'user'		=> 'cwebftp',
	'pass'		=> 'set_password_you\'ve_never_liked',
	'encoding'	=> 'utf8',

);

?>
