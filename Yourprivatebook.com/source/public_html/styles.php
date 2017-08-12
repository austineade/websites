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
if ($_GET[style]=='nomobile') { $_SESSION[nomobile] = 1; $_GET[style] = $s[def_style]; }
$_SESSION['GC_style'] = str_replace(' ','&nbsp;',$_GET[style]);

if (strstr($_SERVER[HTTP_REFERER],$s[site_url])) $url = $_SERVER[HTTP_REFERER];
else $url = "$s[site_url]/";

header("Location: $url");

?>