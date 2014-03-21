<?php
if (eregi(basename(__FILE__),$_SERVER['PHP_SELF'])) { header("Location: ../index.php"); die(); }

$_SESSION['logedin']=false;
ses_destroy();
$smarty->display('login.html');

?>
