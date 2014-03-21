<?php
if (eregi(basename(__FILE__),$_SERVER['PHP_SELF'])) { header("Location: ../index.php"); die(); }

function validateName($in)
{
    if (preg_match('/^[a-zA-Z0-9_\-\.]+$/', $in)==0) {
        return false;
    } else {
        return true;
    }
}

function validateFullname($in)
{
    if (preg_match('/^[a-zA-Z0-9_\-\. ]+$/', $in)==0) {
        return false;
    } else {
        return true;
    }
}


function doStrip($in)
{
	return doArray($in,'stripslashes');
}

function doStripTags($in)
{
	return doArray($in,'strip_tags');
}

function doHtmlSpecial($in)
{
	return doArray($in,'htmlspecialchars');
}

?>
