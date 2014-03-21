<?

function f_form($action,$method,$content)
{
    $f= <<< END_FORM
<form action="$action" method="$method">
 $content
</form>
END_FORM;
    return $f;
}

function f_form_input_text($name, $value)
{
    $f= <<< END_FORM_INPUT_TEXT
 <input type="text" name="$name" value="$value">
END_FORM_INPUT_TEXT;
    return $f;
}

function f_form_input_hidden($name, $value) {
    $f= <<< END_FORM_INPUT_HIDDEN
 <input type="hidden" name="$name" value="$value">
END_FORM_INPUT_HIDDEN;
    return $f;
}

function f_form_input_image($name, $value, $src, $alt, $extra_opts) {
    $f= <<< END_FORM_INPUT_IMAGE
 <input type="image" name="$name" value="$value" src="$src" alt="$alt" "$extra_opts">
END_FORM_INPUT_IMAGE;
    return $f;
}


function f_form_submit($value) {
    $f= <<< END_FORM_SUBMIT
 <input type="submit" value="$value">
END_FORM_SUBMIT;
    return $f;
}

function f_form_select($name, $body, $extra) {
    $f= <<< END_FORM_SELECT
 <select name="$name" $extra>
$body
 </select>
END_FORM_SELECT;
    return $f;
}



function f_form_select_option($value, $desc, $selected) {
    (($selected=='t')||($selected=='true')) ? $selected=" selected" : $selected="";
    $f= <<< END_FORM_SELECT_OPTION
  <option value="$value"$selected>$desc</option>

END_FORM_SELECT_OPTION;
    return $f;
}

?>