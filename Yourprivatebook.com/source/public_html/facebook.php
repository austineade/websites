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
if ((!$s[facebook_id]) OR (!$s[facebook_secret])) exit;
require 'files/facebook/src/facebook.php';

$facebook = new Facebook(array(
  'appId'  => $s[facebook_id],
  'secret' => $s[facebook_secret],
));

if ((!$_GET[state]) OR (!$_GET[code]))
//if (!$user)
{ //print_r($_SESSION);
  $loginUrl = $facebook->getLoginUrl();
  echo 'document.write(\'<a href="'.$loginUrl.'"><img border="0" src="'.$s[site_url].'/images/facebook_login.gif"></a>\');';
  exit;
}
/*
try {
    $uid = $facebook->getUser();
    $fbme = $facebook->api('/me');
} catch (FacebookApiException $e) { 
    print_r($e);
}
*/
$user = $facebook->getUser();
//foreach ($user as $k => $v) echo "$k - $v<br>\n";
if ($user)
{ //try { 
  $user_profile = $facebook->api('/me'); 
  //}
  //catch (FacebookApiException $e) { print_r($e); if ($e) exit; error_log($e); $user = null; }
}
//print_r($user_profile);exit;
if (!$user_profile[username]) { header ("Location: $s[site_url]/"); exit; }


$q = dq("select * from $s[pr]users where email = '$user_profile[username]@facebook.com'",1);
if (!mysqli_num_rows($q))
{ $password = get_random_password();
  $user_profile = replace_array_text($user_profile);
  $nick = str_replace(' ','-',$user_profile[username]);
  dq("insert into $s[pr]users values(NULL,'$user_profile[username]@facebook.com','$password','','$user_profile[name]','$nick','','','','','','','','','','','','','','','','','','','','','1','$s[cas]','1','1','$s[def_style]','0','0','0','0','1','0','0','0','0')",1);
  $n = mysqli_insert_id($s[db]);
  $user_vars = get_user_variables($n);
}
else $user_vars = mysqli_fetch_assoc($q);

//foreach ($user_vars as $k => $v) echo "$k - $v<br>\n";

$_SESSION[GC_u_username] = $user_vars[username];
$_SESSION[GC_u_password] = $user_vars[password];
$_SESSION[GC_u_n] = $user_vars[n];
$_SESSION[GC_u_email] = $user_vars[email];
$_SESSION[GC_u_style] = $user_vars[style];
$s[GC_u_username] = $user_vars[username];
$s[GC_u_password] = $user_vars[password];
$s[GC_u_email] = $user_vars[email];
$s[GC_u_n] = $user_vars[n];
$s[GC_u_style] = $s[GC_style] = $user_vars[style];

header ("Location: $s[site_url]/user.php?action=user_home"); exit;

?>