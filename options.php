<?php

function get_default_options()
{
	$d_options['callout_style'] ='padding: 2px; margin: 1em 1.5em 1em 0.5em; ' .
	                             'background: #FFEC9B none repeat scroll 0% 0%; ' .
								 'border: solid; border-width: thin; border-color: #AE9F44; ' .
								 'display: block; float: left; width: 20em;';
    $d_options['callout_title_style'] = 'padding: 5px; color: #0037A3; ' .
										'font-weight: bold; font-size: 9pt;';
    $d_options['callout_body_style'] = 'background: #FEFFF7; padding: 0.5em; color: #0062A8;';
    return $d_options;
}

function callout_request($name, $default=null)
{
    if (!isset($_REQUEST[$name])) return $default;
    if (get_magic_quotes_gpc()) return callout_stripslashes($_REQUEST[$name]);
    else return $_REQUEST[$name];
}

function callout_stripslashes($value)
{
    $value = is_array($value) ? array_map('callout_stripslashes', $value) : stripslashes($value);
    return $value;
}

function callout_field_text($name, $label='', $tips='', $attrs='')
{
  global $options;
  if (strpos($attrs, 'size') === false) $attrs .= 'size="30"';
  echo '<tr valign="top"><th scope="row">';
  echo '<label for="options[' . $name . ']">' . $label . '</label></th>';
  echo '<td><input type="text" ' . $attrs . ' name="options[' . $name . ']" value="' .
    htmlspecialchars($options[$name]) . '"/>';
  echo ' ' . $tips;
  echo '</td></tr>';
}

function callout_field_checkbox($name, $label='', $tips='', $attrs='')
{
  global $options;
  echo '<tr valign="top"><th scope="row">';
  echo '<label for="options[' . $name . ']">' . $label . '</label></th>';
  echo '<td><input type="checkbox" ' . $attrs . ' name="options[' . $name . ']" value="1" ' .
    ($options[$name]!= null?'checked':'') . '/>';
  echo ' ' . $tips;
  echo '</td></tr>';
}

function callout_field_textarea($name, $label='', $tips='', $attrs='')
{
  global $options;

  if (strpos($attrs, 'cols') === false) $attrs .= 'cols="70"';
  if (strpos($attrs, 'rows') === false) $attrs .= 'rows="5"';

  echo '<tr valign="top"><th scope="row">';
  echo '<label for="options[' . $name . ']">' . $label . '</label></th>';
  echo '<td><textarea wrap="off" ' . $attrs . ' name="options[' . $name . ']">' .
    htmlspecialchars($options[$name]) . '</textarea>';
  echo '<br /> ' . $tips;
  echo '</td></tr>';
}

if (isset($_POST['save']))
{
  $options = callout_request('options');
  update_option('calloutopts', $options);
}
else
{
    $options = get_option('calloutopts', get_default_options());
}
?>
<div class="wrap">
<form method="post">

<h2>Callout box options</h2>
A callout box is constructed as three DIV objects: an outside DIV with an embedded title and body.
&nbsp;Enter css in the boxes below to format these DIVs.
<table class="form-table">
<?php callout_field_textarea('callout_style', 'Style to use for the callout frame.'); ?>
<?php callout_field_textarea('callout_title_style', 'Style to use for the callout title.'); ?>
<?php callout_field_textarea('callout_body_style', 'Style to use for the callout body.'); ?>
</table>

<p class="submit"><input type="submit" name="save" value="Save"/></p>
</form>
</div>
