<?PHP

#################################################
##                                             ##
##              Gold Classifieds               ##
##         http://www.abscripts.com/           ##
##         e-mail: mail@abscripts.com          ##
##                                             ##
##                                             ##
##               version:  6.0                 ##
##            copyright (c) 2015               ##
##                                             ##
##  This script is not freeware nor shareware  ##
##    Please do no distribute it by any way    ##
##                                             ##
#################################################

include('./common.php');
check_admin('messages');

switch ($_GET[action]) {
case 'messages_home'			: messages_home($_GET[selected_style]);
}
switch ($_POST[action]) {
case 'messages_edit'			: messages_edit($_POST);
case 'messages_edited'			: messages_edited($_POST);
}

#################################################################################
#################################################################################
#################################################################################

function messages_home($selected_style) {
global $s;
if (!$selected_style) $selected_style = '_common';
if ($selected_style=='_common') $common_info = '<br>These messages are used if there are not messages available for the selected style';

$styles = get_styles_list(0,1);
$styles_list .= '<option value="_common">_common</option>';
foreach ($styles as $stlk=>$st) $styles_list .= '<option value="'.$st.'">'.str_replace('_',' ',$st).'</option>';

$dr = opendir("$s[phppath]/styles/$selected_style/messages");
rewinddir($dr);
while ($q = readdir($dr))
{ if (($q != ".") AND ($q != "..") AND (is_file("$s[phppath]/styles/$selected_style/messages/$q")))
  $pole[] = $q;
}
closedir ($dr);
if ($pole)
{ sort($pole);
  foreach ($pole as $k => $v) $list .= '<tr><td align="left"><input type="radio" name="file" value="'.$v.'">'.$v.'</td></tr>';
  $list = str_replace('>common.php<','>Messages used in multiple scripts<',$list);
}
else $list = '<center>No messages available for the selected style,<br>this styles uses messages defined in folder "_common".<br>If you want to have special messages for this style, copy directory<br>"messages" from directory "styles/_common" to directory "styles/'.$selected_style.'". ';
ih();
echo $s[info];

echo '<table border="0" cellspacing="0" cellpadding="2">
<tr><td align="center" colspan="3">

<form action="templates.php" method="get">'.check_field_create('admin').'
<input type="hidden" name="action" value="templates_home">
<table border="0" cellspacing="0" cellpadding="2" class="common_table" width="100%">
<tr><td class="common_table_top_cell">Edit Messages</td></tr>
<tr><td align="center" valign="top">Selected style: <b>'.$selected_style.'</b>, select another: <select class="form-control" name="selected_style">'.$styles_list.'</select> <input type="submit" name="A1" value="Submit" class="button10">'.$common_info.'</td></tr>
</table>
</form>
<br>
<form action="messages.php" method="post" name="form1">'.check_field_create('admin').'
<input type="hidden" name="action" value="messages_edit">
<input type="hidden" name="selected_style" value="'.$selected_style.'">
<table border="0" cellspacing="0" cellpadding="0" class="common_table" width="100%">
<tr><td colspan="2" class="common_table_top_cell">Edit/Translate Messages Generated by Public Scripts</td></tr>
<tr><td align="center">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="left" valign="top" nowrap>'.$list.'</td></tr>';
if ($pole) echo '<tr><td align="center" colspan=2><input type="submit" name="submit" value="Submit" class="button10"></td></tr>';
echo '</table></td></tr></table><br></form>';
ift();
}

#################################################################################

function messages_edit($data) {
global $s;
ih();
echo $data[info];
include("$s[phppath]/styles/$data[selected_style]/messages/$data[file]");
$m[selected_style] = $data[selected_style];
$m = replace_array_text($m);
parse_page("$s[phppath]/data/messages/$data[file].html",$m);
echo '<a href="messages.php?action=messages_home&selected_style='.$data[selected_style].'">Back</a>';
ift();
}

#################################################################################

function parse_page($template, $value) {
global $s;
$fh = fopen($template,'r') or problem ("Unable to read file $template");
while (!feof($fh)) $line .= fgets($fh,4096);
fclose($fh);
while (list($key,$val) = each ($value)) $line = str_replace("#%$key%#",$val, $line);
reset ($value);
$line = preg_replace("/#%[a-z0-9_]*%#/i",'',$line);
$line = str_replace('</form>',check_field_create('admin').'</form>',$line);
$line = unreplace_once_html($line);
echo $line;
}

#################################################################################

function messages_edited($data) {
global $s,$form;
$script = $data[script]; $selected_style = $data[selected_style];
if (!$file = fopen ("$s[phppath]/styles/$data[selected_style]/messages/$script",'w')) problem ("Can't write to file $s[phppath]/styles/$data[selected_style]/messages/$script.");
$zapis = fwrite ($file,"<?PHP\n\n");
unset ($data[action],$data[script],$data[selected_style],$data[submitbut],$data[check_field]);
reset($data);
foreach ($data as $k=>$v)
{ $v = refund_html(htmlspecialchars($v,ENT_QUOTES));
  $zapis = fwrite ($file,"\$m[$k] = '$v';\n");
}
$zapis = fwrite ($file,"\n?>"); fclose($file);
if (!$zapis)
$data[info] = info_line('Can\'t write to file '."$s[phppath]/styles/$data[selected_style]/messages/$script".'.<br>Make sure that your messages directory exists and has 777 permission and the file '."$s[phppath]/styles/$data[selected_style]/messages/$script".' inside has permission 666.');
else
$data[info] = info_line('Your messages have been updated');
$data[file] = $script; $data[selected_style] = $selected_style;
messages_edit($data);
}

#################################################################################
#################################################################################
#################################################################################

?>