<?php
if (eregi(basename(__FILE__),$_SERVER['PHP_SELF'])) { header("Location: ../index.php"); die(); }

$weba = new WEB_ACCOUNT($db_cfg, $global_cfg, $_SESSION['userid'], $_SESSION['passwd']);

$current_page = isset($_POST['current_page']) ? $_POST['current_page'] : 0;

if (($_POST['page'])==">") {
    $current_page++;
} elseif (($_POST['page']=="<")) {
    $current_page--;
}

$weba->web_log_count();
$count = mysql_fetch_object($weba->result)->count;

$per_page = 100;
$max_page = ceil(($count)/$per_page);

$current_page = $current_page<1 ? 1 : $current_page;

$weba->web_log($per_page, ($current_page-1)*$per_page);
while(($resultArray[] = mysql_fetch_assoc($weba->result)) || array_pop($resultArray));

$smarty->assign('current_page', $current_page);
$smarty->assign('max_page', $max_page);
$smarty->assign('web_log', $resultArray);
$smarty->assign('userid', $_SESSION['userid']);
$smarty->assign('role', $weba->my_role());
$smarty->display('web_log.html');

?>
