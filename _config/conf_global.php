<?php
if (eregi(basename(__FILE__),$_SERVER['PHP_SELF'])) { header("Location: ../index.php"); die(); }

$global_cfg = array (
    'app_version' => 'v0.9',
    'ftp_root' => '/home/FTP/',
);

?>
