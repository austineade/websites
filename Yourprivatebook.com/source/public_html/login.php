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
get_messages('user.php');
include("$s[phppath]/data/data_forms.php");

//if ($_SESSION[temporary_password]) edit_temporary_password($_SESSION);
switch ($_GET[action]) {
case 'user_login'					: user_login($_GET);
case 'password_remind'				: password_remind();
//case 'edited_temporary_password'	: edited_temporary_password($_GET);
}
switch ($_POST[action]) {
case 'user_login'					: user_login($_POST);
case 'password_reminded'			: password_reminded($_POST);
//case 'edited_temporary_password'	: edited_temporary_password($_POST);
}
user_login_page();

#################################################################################

function user_login($in) {
global $s,$m;
$in = replace_array_text($in);
if (!$in) user_login_page($who,$in);

if (($in[email]) AND (!$in[password]))
{ $s[info] = info_panel($m[wrong_login],'','danger');
  user_login_page('user',$in);
}
if (($s[user_login_captcha]) AND  (!strstr(getenv('HTTP_REFERER'),"$s[site_url]/administration/")) AND (!$in[n]))
{ $x = check_entered_captcha($in[image_control]);
  if ($x)
  { $s[info] = info_panel($x,'','danger');
    user_login_page('user',$in);
  }
}
if ((!check_email($in[email])) OR (!preg_match("/^[a-z0-9]{6,15}$/i",$in[password])))
{ $s[info] = info_panel($m[wrong_login],'','danger');
  user_login_page('user',$in);
}
$table = "$s[pr]users"; $script = 'user.php';
if ((strstr(getenv('HTTP_REFERER'),"$s[site_url]/administration/")) AND ($_GET[n])) $q = dq("select * from $table where email = '$in[email]' AND password = '$in[password]' AND n = '$in[n]'",1);
else
{ $password = md5($in[password]);
  //$password = $in[password];
  $q = dq("select * from $table where email = '$in[email]' AND password = '$password'",1);
}
$user_vars = mysqli_fetch_assoc($q);
//foreach ($user_vars as $k => $v) echo "$k - $v<br>\n";exit;
if (($user_vars[n]) AND (!$user_vars[confirmed]))
{ $s[info] = info_panel($m[not_confirmed],'','danger');
  user_login_page('user',$in);
}
if (!$user_vars[n])
{ //check_if_too_many_logins('user',$table,$in[email],$in[password]);
  check_for_temporary_password('user',$in[email],$in[password]);
  $s[info] = info_panel($m[wrong_login],'','danger');
  user_login_page('user',$in);
}
if ($in[remember_me])
{ //setcookie(GC_u_username,$user_vars[username],$s[cas]+31536000); 
  setcookie(GC_u_password,$user_vars[password],$s[cas]+31536000); 
  setcookie(GC_u_n,$user_vars[n],$s[cas]+31536000);
  setcookie(GC_u_email,$user_vars[email],$s[cas]+31536000);
  setcookie(GC_u_style,$user_vars[style],$s[cas]+31536000);
}
else
{ //$_SESSION[GC_u_username] = $user_vars[username];
  $_SESSION[GC_u_password] = $user_vars[password];
  $_SESSION[GC_u_n] = $user_vars[n];
  $_SESSION[GC_u_email] = $user_vars[email];
  $_SESSION[GC_u_style] = $user_vars[style];
}
//$s[GC_u_username] = $user_vars[username];
$s[GC_u_password] = $user_vars[password];
$s[GC_u_email] = $user_vars[email];
$s[GC_u_n] = $user_vars[n];
$s['no_test'] = 1;
user_home_page();
exit;
}

#####################################################################################

function check_for_temporary_password($who,$email,$password) {
global $s;
$q = dq("select * from $s[pr]users where email = '$email' AND password_temp = '$password'",1);
$user = mysqli_fetch_assoc($q);
//foreach ($user as $k => $v) echo "$k - $v<br>\n";exit;
$user[info] = $s[info];
if ($user[n])
{ $a[email] = $email; 
  $a[password_temp] = $password;   
  $a[n] = $user[n];
  page_from_template('user_temporary_password.html',$a);
}
}

#####################################################################################
/*
function edited_temporary_password($in) {
global $s,$m;
$table = "$s[pr]users";
foreach ($in as $k => $v) $in[$k] = trim($v);
$in[n] = round($in[n]);
if (!$in[new_password]) $problem[] = $m[missingfield];
if (!preg_match("/^[a-z0-9]{6,15}$/i",$in[username])) exit;
if (!preg_match("/^[a-z0-9]{6,15}$/i",$in[new_password])) $problem[] = $m[wrongpassword];
$in = replace_array_text($in);
if ($problem)
{ $s[info] = info_panel($m[errors],implode('<br>',$problem),'danger');
  edit_temporary_password($in);
  //check_for_temporary_password($who,$in[username],$in[temp_pass]);
}
$password = md5($in[new_password]);
//echo "update $table set password = '$password', temp_pass = '' where n = '$in[n]' and username = '$in[username]' AND temp_pass = '$in[temp_pass]'";exit;
dq("update $table set password = '$password', temp_pass = '' where n = '$in[n]' and username = '$in[username]' AND temp_pass = '$in[temp_pass]'",1);
//$q = dq("select * from $table where n = '$in[n]' and username = '$in[username]' and password = '$password'",1);
$s[info] = info_panel($m[i_saved]);
user_login_page();
}
*/
#####################################################################################
#####################################################################################
#####################################################################################

function password_remind($in) {
global $s,$m;
$in[info] = $s[info];
page_from_template('user_remind.html',$in);
}

##################################################################################

function password_reminded($in) {
global $s,$m;
$in = replace_array_text($in);
if (!$in[email]) password_remind($in);
if (!check_email($in[email])) password_remind($in);
$q = dq("select username,password from $s[pr]users where email = '$in[email]'",1);
while ($data = mysqli_fetch_assoc($q))
{ $found = 1;
  //$x = explode(' ',microtime()); $x = explode('.',$x[0]); $data[password] = substr(md5(($s[cas]/$x[1])*105815540020070/148),-14);
  //dq("update $s[pr]users set temp_pass = '$data[password]' where username = '$data[username]'",1);
  $data[to] = $in[email];
  //foreach ($data as $k => $v) echo "$k - $v<br>\n";
  mail_from_template('user_password_remind.txt',$data);
}
if ($found)
{ $s[info] = info_panel($m[mail_sent]);
  user_login_page();
}
else { $s[info] = info_panel($m[no_account]); password_remind($in); }
}

##################################################################################
##################################################################################
##################################################################################

?>