<?php
if (eregi(basename(__FILE__),$_SERVER['PHP_SELF'])) { header("Location: ../index.php"); die(); }

    $smarty->template_dir = APP_ROOT."/_V/smarty/template/";
    $smarty->compile_dir = APP_ROOT."/_V/smarty/compile/";
    $smarty->cache_dir = APP_ROOT."/_V/smarty/cache/";
    $smarty->config_dir = '';

    $smarty->assign('server_name', $_SERVER['SERVER_NAME']);
    $smarty->assign('app_version', $global_cfg['app_version']);
?>
