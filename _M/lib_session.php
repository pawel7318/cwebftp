<?
if (eregi(basename(__FILE__),$_SERVER['PHP_SELF'])) { header("Location: ../index.php"); die(); }

function ses_start() {

	session_name("CWebFTP");
//	session_cache_expire('180');
	session_start();

	if (isset($_SESSION['exist'])) {
		if (($_SESSION['remote_addr'] != $_SERVER['REMOTE_ADDR'])||
		    ($_SESSION['user_agent'] != $_SERVER['HTTP_USER_AGENT'])) {
			ses_create_new();
		}
	} else {
		ses_create_new();
	}
}

function ses_create_new() {
  		session_regenerate_id();
                $_SESSION['exist'] = true;
                $_SESSION['remote_addr'] = $_SERVER['REMOTE_ADDR'];
                $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
}


function ses_destroy() {
	$_SESSION = array();
	if (isset($_COOKIE[session_name()])) {
    		setcookie(session_name(), '', time()-42000, '/');
                session_destroy();
		header("Location: index.php");

	}
	
}



?>
