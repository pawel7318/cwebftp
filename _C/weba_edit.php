<?php

die(' TEN PLIK NIE JEST JUZ UZYWANY');


if (eregi(basename(__FILE__),$_SERVER['PHP_SELF'])) { header("Location: ../index.php"); die(); }

$weba = new WEB_ACCOUNT($db_cfg, $global_cfg, $_SESSION['userid'], $_SESSION['passwd']);

$smarty->assign('userid', $_SESSION['userid']);
$smarty->assign('role', $weba->my_role());

switch ($_GET['do']) {
    case "change_enabled":
        if ( ($_GET['enabled']=='N') || ($_GET['enabled']=='Y') ) {
            $weba->change_enabled($_GET['userid'], $_GET['enabled']);
        }
    break;
    case "delete":
        $weba->weba_destroy($_GET['userid']);
    break;
}


$weba->weba_list();
while(($resultArray[] = mysql_fetch_assoc($weba->result)) || array_pop($resultArray));
$smarty->assign('web_account', $resultArray);

$smarty->display('weba_mgm.html');


?>
