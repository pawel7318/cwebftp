<?php
if (eregi(basename(__FILE__),$_SERVER['PHP_SELF'])) { header("Location: ../index.php"); die(); }

$ftpa = new FTP_ACCOUNT($db_cfg, $global_cfg, $_SESSION['userid'], $_SESSION['passwd']);

if (isset($ftpa->ftpa_error_num)) {
    $smarty->assign('error_msg', $ftpa->ftpa_error_num.': '.$ftpa->ftpa_error_desc);
    $smarty->display('error.html');
    die();
}

$smarty->assign('userid', $_SESSION['userid']);
$smarty->assign('role', $ftpa->my_role());

switch ($_action) {
    case "ftpa_add":
        if (!validateName($_GET['userid'])) {
            $smarty->assign('error_msg', 'Niedozwolone znaki w nazwie konta');
        } elseif ((($_GET['fullname'])!=NULL) && !validateFullname($_GET['fullname'])) {
            $smarty->assign('error_msg', 'Niedozwolone znaki w opisie konta');
        } else {
            if (!$ftpa->ftpa_create($_GET['userid'], $_GET['fullname'])) $smarty->assign('error_msg', 'Blad '.$ftpa->ftpa_error_num.': '.$ftpa->ftpa_error_desc);
        }
    break;
    case "ftpa_enable":
        if (!$ftpa->ftpa_enable($_GET['userid'])) $smarty->assign('error_msg', 'Blad '.$ftpa->ftpa_error_num.': '.$ftpa->ftpa_error_desc);
    break;
    case "ftpa_disable":
        if (!$ftpa->ftpa_disable($_GET['userid'])) $smarty->assign('error_msg', 'Blad '.$ftpa->ftpa_error_num.': '.$ftpa->ftpa_error_desc);
    break;
    case "ftpa_del":
        if (!$ftpa->ftpa_destroy($_GET['userid'])) $smarty->assign('error_msg', 'Blad '.$ftpa->ftpa_error_num.': '.$ftpa->ftpa_error_desc);
    break;
    case "ftpa_passwd_form":
        if  (isset($_GET['userid'])) {
            $smarty->assign('userid', $_GET['userid']);
            $smarty->assign('weba_or_ftpa', 'ftpa');
            $smarty->assign('passwd_form', true);
        }
    break;
    case "ftpa_passwd":
        if (isset($_POST['userid'])&&(isset($_POST['passwd1']))&&(isset($_POST['passwd2']))) {
            if ($_POST['passwd1']==$_POST['passwd2']) {
                if (!$ftpa->ftpa_passwd($_POST['userid'], $_POST['passwd1'])) $smarty->assign('error_msg', $ftpa->ftpa_error_num.': '.$ftpa->ftpa_error_desc);
            } else {
                $smarty->assign('passwd_typo', true);
                $smarty->assign('userid', $_GET['userid']);
                $smarty->assign('weba_or_ftpa', 'ftpa');
                $smarty->assign('passwd_form', true);
            }
        }
    break;
    case "ftpa_change_owner":
        if ((!validateName($_GET['userid']))||(!validateName($_GET['owner']))) {
            $smarty->assign('error_msg', 'Niepoprawne parametry');
            $ftpa->logger(basename(__FILE__), __LINE__);
        } else {
            if (!$ftpa->ftpa_change_owner($_GET['userid'], $_GET['owner'])) $smarty->assign('error_msg', 'Blad '.$ftpa->ftpa_error_num.': '.$ftpa->ftpa_error_desc);
        }
    break;
}

if (!$ftpa->ftpa_list()) $smarty->assign('error_msg', $ftpa->ftpa_error_num.' '.$ftpa->ftpa_error_desc);
while(($resultArray[] = mysql_fetch_assoc($ftpa->result)) || array_pop($resultArray));
$smarty->assign('ftp_account', $resultArray);

if ($ftpa->my_role()=='admin') {
    if (!$ftpa->ftpa_list_valid_owners()) $smarty->assign('error_msg', $ftpa->ftpa_error_num.' '.$ftpa->ftpa_error_desc);
    while(($resultArray2[] = mysql_fetch_assoc($ftpa->result)) || array_pop($resultArray2));
    $smarty->assign('ftpa_vo', $resultArray2);
}

$smarty->display('ftpa_mgm.html');


?>
