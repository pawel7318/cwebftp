<?
require_once("develconfig.php");

if ($_SESSION['develview'.$devel_config['secret_hash']] == 'ON') {
        $dv_post=var_export($_POST, true);
        $dv_ses=var_export($_SESSION, true);
	$dv_cookie=var_export($_COOKIE, true);

echo <<< END_HTML

<div style="background-color:#99DD77">
<HR>
<font size=-1>
POST:
<PRE>
$dv_post
</PRE>
</font>
</div>

<div style="background-color:#99CCFF">
<HR>
<font size=-1>
SESION:
<PRE>
$dv_ses
</PRE>
</font>
</div>



<div style="background-color:#FFFFCC">
<HR>
<font size=-1>
COOKIE:
<PRE>
$dv_cookie
</PRE>
</font>
</div>

<div style="background-color:#5555CC">
<HR>
<font size=-1>
DEVEL SPECIAL:
<PRE>
<div id="devel_special">
</div>
</PRE>
</font>
</div>

END_HTML;

}
?>
