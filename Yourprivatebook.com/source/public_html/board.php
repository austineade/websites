<?PHP

#################################################
##                                             ##
##               Link Up Gold                  ##
##       http://www.phpwebscripts.com/         ##
##       e-mail: info@phpwebscripts.com        ##
##                                             ##
##                                             ##
##               version:  9.0                 ##
##            copyright (c) 2014               ##
##                                             ##
##  This script is not freeware nor shareware  ##
##    Please do no distribute it by any way    ##
##                                             ##
#################################################

include('./common.php');
$s[selected_menu] = 5;
get_messages('board.php');
include($s[phppath].'/data/data_forms.php');

if ($_GET[action]=='add_message') add_message();
if ($_POST) added_message($_POST);
show_board();

###############################################################################
###############################################################################
###############################################################################

function add_message($in) {
global $s,$m;
if (($s[board_reg_only]) AND (!$s[GC_u_n])) problem($m[no_logged]);

if ($s[board_v_name]) { $x[item_name] = $m[name]; $x[field_name] = 'name'; $x[field_value] = $in[name]; $x[field_maxlength] = 255; $in[field_name] = parse_part('form_field.txt',$x); }
if ($s[board_v_email]) { $x[item_name] = $m[email]; $x[field_name] = 'email'; $x[field_value] = $in[email]; $x[field_maxlength] = 255; $in[field_email] = parse_part('form_field.txt',$x); }
if ($s[board_v_captcha]) $in[field_captcha_test] = parse_part('form_captcha_test.txt',$a);
$in[info] = $s[info];

$q = dq("select * from $s[pr]smilies group by description order by n",1);
while ($x = mysqli_fetch_assoc($q))
{ $in[smilies] .= "<img src=\"$s[site_url]/images/smilies/$x[image]\" onclick=\"insertSmiley('$x[shortcut]','board_comment')\"> ";
}

page_from_template('board_add_message.html',$in);
}

###############################################################################

function show_board() {
global $s,$m;
$q = dq("select * from $s[pr]board order by time desc limit $s[board]",0);
while ($x = mysqli_fetch_assoc($q)) 
{ $x[date] = datum ($x[time],0);
  if ($x[user])
  { $user_vars = get_user_variables($x[user]);
    $x[name] = '<a href="'.get_detail_page_url('u',$user_vars[n],$user_vars[nick]).'">'.$user_vars[nick].'</a>';
  }
  $a[messages] .= parse_part('board_message.txt',$x);
}
if ($s[GC_u_n])
{ $user_vars = get_user_variables($s[GC_u_n]);
  $_POST[name] = $user_vars[name]; $_POST[email] = $user_vars[email];
}
$a[info] = $s[info];

page_from_template('board.html',$a);
}

###############################################################################
###############################################################################
###############################################################################

function added_message($in) {
global $s,$m;
$in = replace_array_text($in);
if ($s[GC_u_n])
{ $user = get_user_variables($s[GC_u_n]);
  $in[name] = $user[name]; $in[email] = $user[email];
}
elseif ($s[board_reg_only]) problem ($m[no_logged]);
if ($s[board_v_captcha]) { $x = check_entered_captcha($in[image_control]); if ($x) $problem[] = $x; }

if (!trim($in[board_comment])) $problem[] = $m[m_message];
elseif (strlen($in[board_comment]) > $s[board_max]) $problem[] = "$m[l_message] $s[board_max] $m[characters].";
$black = try_blacklist($in[board_comment],"word");
if ($black) $problem[] = $black;

if (($s[board_r_name]) AND (!trim($in[name]))) $problem[] = "$m[missing_field] $m[name]";
elseif (strlen($in[name]) > 255) $problem[] = $m[l_name];

if (($s[board_r_email]) AND (!trim($in[email]))) $problem[] = "$m[missing_field] $m[email]";
elseif (strlen($in[email]) > 255) $problem[] = $m[l_email];
elseif (($s[board_r_email]) AND (!check_email($in[email]))) $problem[] = $m[w_email];
$black = try_blacklist($in[email],'email'); if ($black) $problem[] = $black;

if ($problem)
{ $s[info] = info_panel(implode('<br>',$problem));
  add_message($in);
}

if ($s[GC_u_n])
{ $q = dq("select name,email from $s[pr]users where n = '$s[GC_u_n]'",1);
  $data = mysqli_fetch_row($q);
  $in[name] = $data[0]; $in[email] = $data[1];
}
$q = dq("select * from $s[pr]smilies group by shortcut",1);
while ($x = mysqli_fetch_assoc($q)) { $search[] = $x[shortcut]; $replace[] = '<img border="0" src="'.$s[site_url].'/images/smilies/'.$x[image].'">'; }
$in[board_comment] = str_replace($search,$replace,$in[board_comment]);
dq("insert into $s[pr]board values ('$in[name]','$in[email]','$s[GC_u_n]','$s[ip]','$in[title]','$in[board_comment]','$s[cas]')",0);
$s[info] = info_panel($m[message_created]);
show_board();

}

###############################################################################
###############################################################################
###############################################################################

?>