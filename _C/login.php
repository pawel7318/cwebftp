<?php
if (eregi(basename(__FILE__),$_SERVER['PHP_SELF'])) { header("Location: ../index.php"); die(); }

$db = new MYDB($db_cfg);
if ((!$db->connect())||(!$db->select_db())) {
    $smarty->assign('error_msg', $db->my_error_num.': '.$db->my_error_desc);
    $smarty->display('error.html');
    die();
}

if (isset($_POST['userid'])&&isset($_POST['passwd'])) {
    if (!$db->sql_p_exec('SELECT role FROM webuser WHERE userid="%s" AND passwd="%s" AND enabled="Y"', array($_POST['userid'], md5($_POST['userid'].$_POST['passwd'])))) {
        $smarty->assign('error_msg', $db->my_error_num.': (supressed)');
        $smarty->display('error.html');
        die();
    } else {
        $_SESSION['userid']=$_POST['userid'];
        $_SESSION['passwd']=$_POST['passwd'];
        switch (mysql_fetch_object($db->result)->role) {
            case admin:
                $_SESSION['logedin']=true;
                $_SESSION['action']='weba_mgm';
                include(APP_ROOT.'/_C/weba_mgm.php');
            break;
            case superuser:
            case user:
                $_SESSION['logedin']=true;
                $_SESSION['action']='ftpa_mgm';
                include(APP_ROOT.'/_C/ftpa_mgm.php');
            break;
            default:
                $smarty->assign('error_msg', 'Logowanie nie powiodło się - niepoprawny login lub hasło');
                $smarty->display('login.html');
            break;
        }
    }
} else $smarty->display('login.html');



?>
