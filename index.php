<?

define(APP_ROOT,dirname(__FILE__));

/* config */
require_once(APP_ROOT."/_config/conf_global.php");
require_once(APP_ROOT."/_config/conf_db.php");

/*  session */
require_once(APP_ROOT."/_M/lib_session.php");
ses_start();


/*  libs */
require_once(APP_ROOT."/_M/lib_db.php");
require_once(APP_ROOT."/_M/lib_weba.php");
require_once(APP_ROOT."/_M/lib_ftpa.php");
require_once(APP_ROOT."/_M/lib_validate.php");
require_once(APP_ROOT."/_M/smarty/Smarty.class.php");


/* front controller */
$smarty = new Smarty();
include(APP_ROOT."/_config/conf_smarty.php");

$_action = isset($_GET['action']) ? $_GET['action'] : $_SESSION['action'];

if (!isset($_SESSION["logedin"])) { $_action = "login"; }

switch ($_action) {
    case "weba_mgm":
    case "weba_enable":
    case "weba_disable":
    case "weba_add":
    case "weba_del":
    case "weba_change_role":
    case "weba_passwd":
    case "weba_passwd_form":
        include(APP_ROOT."/_C/weba_mgm.php");
    break;
    case "ftpa_mgm":
    case "ftpa_enable":
    case "ftpa_disable":
    case "ftpa_add":
    case "ftpa_del":
    case "ftpa_passwd":
    case "ftpa_passwd_form":
    case "ftpa_change_owner":
        include(APP_ROOT."/_C/ftpa_mgm.php");
    break;
    case "web_log":
        include(APP_ROOT."/_C/web_log.php");
    break;
    case "ftp_log":
        include(APP_ROOT."/_C/ftp_log.php");
    break;
    case "logout":
        include(APP_ROOT."/_C/logout.php");
    break;
    case "login":
    default:
        include(APP_ROOT."/_C/login.php");
    break;
}


?>