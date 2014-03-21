<?php
if (eregi(basename(__FILE__),$_SERVER['PHP_SELF'])) { header("Location: ../index.php"); die(); }

$weba = new WEB_ACCOUNT($db_cfg, $global_cfg, $_SESSION['userid'], $_SESSION['passwd']);

if (isset($weba->weba_error_num)) {
    $smarty->assign('error_msg', $weba->weba_error_num.': '.$weba->weba_error_desc);
    $smarty->display('error.html');
    die();
}

$smarty->assign('userid', $_SESSION['userid']);
$smarty->assign('role', $weba->my_role());

switch ($_action) {
    case "weba_add":
          if (!validateName($_GET['userid'])) {
            $smarty->assign('error_msg', 'Niedozwolone znaki w nazwie konta');
        } elseif ((($_GET['fullname'])!=NULL) && !validateFullname($_GET['fullname'])) {
            $smarty->assign('error_msg', 'Niedozwolone znaki w opisie konta');
        } else {
            $weba->weba_create($_GET['userid'], $_GET['role'], $_GET['fullname']);
        }
    break;
    case "weba_enable":
        if (!$weba->weba_enable($_GET['userid'])) $smarty->assign('error_msg', 'Blad '.$weba->weba_error_num.': '.$weba->weba_error_desc);
    break;
    case "weba_disable":
        $weba->weba_disable($_GET['userid']);
    break;
    case "weba_del":
        $weba->weba_destroy($_GET['userid']);
    break;
    case "weba_change_role":
        $weba->weba_change_role($_GET['userid'], $_GET['role']);
    break;
    case "weba_passwd_form":
        if  (isset($_GET['userid'])) {
            $smarty->assign('userid', $_GET['userid']);
            $smarty->assign('weba_or_ftpa', 'weba');
            $smarty->assign('passwd_form', true);
        }
    break;
    case "weba_passwd":
        if (isset($_POST['userid'])&&(isset($_POST['passwd1']))&&(isset($_POST['passwd2']))) {
            if ($_POST['passwd1']==$_POST['passwd2']) {
                if (!$weba->weba_passwd($_POST['userid'], $_POST['passwd1'])) $smarty->assign('error_msg', 'Blad '.$weba->weba_error_num.': '.$weba->weba_error_desc);
            } else {
                $smarty->assign('passwd_typo', true);
                $smarty->assign('userid', $_GET['userid']);
                $smarty->assign('weba_or_ftpa', 'weba');
                $smarty->assign('passwd_form', true);
            }
        }
    break;
}

if (!$weba->weba_list()) $smarty->assign('error_msg', $weba->weba_error_num.' '.$weba->weba_error_desc);
while(($resultArray[] = mysql_fetch_assoc($weba->result)) || array_pop($resultArray));
$smarty->assign('web_account', $resultArray);

$smarty->display('weba_mgm.html');


?>
