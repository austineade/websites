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
$s[selected_menu] = 6;

if ((!$s[GC_u_n]) OR (!is_numeric($_GET[n]))) exit;
$_GET = replace_array_text($_GET);

if ($_GET[action]=='add') dq("insert into $s[pr]u_favorites values ('$s[GC_u_n]','$_GET[what]','$_GET[n]')",1);
elseif ($_GET[action]=='remove') dq("delete from $s[pr]u_favorites where user = '$s[GC_u_n]' AND what = '$_GET[what]' AND n = '$_GET[n]'",1);

header ("Location: $_SERVER[HTTP_REFERER]");
exit;

?>