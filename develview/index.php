<?php

require_once("../_M/lib_session.php");
require_once("./local/lib_form.php");
require_once("./develconfig.php");

ses_start();

if ($_POST['destroy']=='1') {
	ses_destroy();
}

if (isset($_POST['develview'.$devel_config['secret_hash']])) $_SESSION['develview'.$devel_config['secret_hash']] = $_POST['develview'.$devel_config['secret_hash']];
if (!isset($_SESSION['develview'.$devel_config['secret_hash']])) $_SESSION['develview'.$devel_config['secret_hash']] = 'OFF';

if ($_SESSION['develview'.$devel_config['secret_hash']] == 'ON') {
	$on=true;
} else {
	$on=false;
}


$form_select_options =f_form_select_option('ON', 'Turn DevelView ON', $on);
$form_select_options.=f_form_select_option('OFF', 'Turn DevelView OFF', !$on);
$form_select_options.=f_form_submit('submit');
$form_select=f_form_select('develview'.$devel_config['secret_hash'], $form_select_options, '');
$form=f_form($_SESSION['PHP_SELF'], 'POST', $form_select);

$form_reset =f_form_input_hidden('destroy', '1');
$form_reset.=f_form_submit('destroy');
$form2=f_form($_SESSION['PHP_SELF'], 'POST', $form_reset);


echo "Devel View v1.0";
echo $form;
echo $form2;


f_form($_SERVER['PHP_SELF'], 'GET' , $content);

include("develview.php");
?>