<?php
die('plik do skasowania');
if (eregi(basename(__FILE__),$_SERVER['PHP_SELF'])) { header("Location: ../index.php"); die(); }

$weba = new WEB_ACCOUNT($db_cfg, $global_cfg, $_SESSION['userid'], $_SESSION['passwd']);

$smarty->assign('userid', $_SESSION['userid']);
$smarty->assign('role', $weba->my_role());


$smarty->assign('error_msg', 'jakis komunikat bledu');

$weba->weba_create($_GET['userid'], $_GET['role'], $_GET['fullname']);


$weba->weba_list();
while(($resultArray[] = mysql_fetch_assoc($weba->result)) || array_pop($resultArray));
$smarty->assign('web_account', $resultArray);

$smarty->display('weba_mgm.html');


?>
