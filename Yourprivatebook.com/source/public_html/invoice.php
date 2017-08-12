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

error_reporting (E_ERROR | E_PARSE);
$filename = htmlspecialchars(iconv($s[charset],'ISO-8859-1//IGNORE//TRANSLIT',$_GET[f]),ENT_QUOTES);
/*
header("Content-type: application/octet-stream\n" );
header("Content-Disposition: filename=$filename.pdf");
header("Content-Disposition: attachment; filename=$filename.pdf");
*/
session_start();
include('./data/data.php');
db_connect();
$s[cas] = time()+$s[timeplus];
get_messages();

$user_vars = check_logged_user();
if (!is_numeric($_GET[n])) exit;
$a = get_order_variables($_GET[what],$_GET[n]);
if ($a[user]!=$s[GC_u_n]) exit;
$a[site_title] = $s[site_title]; $a[site_url] = str_replace('http://','',$s[site_url]); $a[currency] = $s[currency];
$a[name] = $user_vars[name]; $a[address] = $user_vars[address]; $a[date] = datum($a[time]); $a[order_date] = datum($a[order_time]);
if ($a[paid]) $a[status] = $m[paid]; else $a[status] = $m[unpaid]; 
$a[email] = $user_vars[email];

if ($a[what]=='u') $a[item_description] = "$m[premium_membership] - $a[u_days] $m[days]";
else
{ $q = dq("select * from $s[pr]ads_orders_parts where n = '$_GET[n]'",1);
  $order_details = mysqli_fetch_assoc($q);
  $s[extra_options] = array('bold','featured','home_page','featured_gallery','highlight','paypal');
  foreach ($s[extra_options] as $k=>$v) if ($order_details[$v]) $extra[] = $m['xtra_'.$v];
  if ($order_details[pictures]) $extra[] = "$m[xtra_pictures]$order_details[pictures]";
  if ($order_details[files]) $extra[] = "$m[xtra_files]$order_details[files]";
  $a[ad_features] = implode(', ',$extra);
  $a[item_description] = "$m[Ad] #".$a[ad].": $a[ad_features]";
}

foreach ($a as $k=>$v) $a[$k] = strip_tags($v);

//foreach ($a as $k=>$v) $a[$k] = htmlentities($v);
foreach ($a as $k=>$v) $a[$k] = utf8_encode($v);
//foreach ($a as $k => $v) echo "$k - $v<br>\n";
//exit;
require_once('./pdf/config/lang/eng.php');
require_once('./pdf/tcpdf.php');

class MYPDF extends TCPDF { function Header() { $this->Ln(20); } }
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->Ln(20);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
$pdf->setLanguageArray($l); 
//$pdf->SetFont('freeserif', '', 12);// unicode

$pdf->AddPage();
$html = implode('',file('pdf/invoice.html'));
foreach ($a as $k=>$v) $html = str_replace("#%$k%#",$v,$html);
$pdf->writeHTML($html, true, 0, true, 0);
$pdf->lastPage();
$pdf->Output("$filename.pdf", 'D');
mysqli_close($s[db]);

exit;

##################################################################################
##################################################################################
##################################################################################

function db_connect() {
global $s;
$s[db] = mysqli_connect($s[dbhost],$s[dbusername],$s[dbpassword],$s[dbname]);
if (mysqli_connect_errno($s[db])) die("Unable to connect to the database host. Check database host, username, password.<br>". mysqli_connect_error());
}

##################################################################################

function dq($query,$check) {
global $s;
$query = str_replace('insert into','insert ignore into',$query);
$query = str_replace("update $s[pr]","update ignore $s[pr]",$query);
$q = mysqli_query($s[db],$query);
if ( ($check) AND (!$q) ) die(mysqli_error($s[db])."<br><br>$query");
return $q;
}

##################################################################################

function datum($cas,$plustime) {
global $s;
if (is_array($cas)) $cas = mktime(6,0,0,$cas[date_m],$cas[date_d],$cas[date_y]);
elseif (!$cas) $cas = $s[cas];
for ($y=1;$y<=3;$y++) if ($s['date_form_'.$y.'a']=='Space') $date_separator[$y] = ' '; elseif ($s['date_form_'.$y.'a']=='Nothing') $date_separator[$y] = ''; else $date_separator[$y] = $s['date_form_'.$y.'a'];
$x[d] = date('d',$cas); $x[m] = date('m',$cas); $x[y] = date('Y',$cas);
$datum = $x[$s[date_form_1]].$date_separator[1].$x[$s[date_form_2]].$date_separator[2].$x[$s[date_form_3]].$date_separator[3];
if ($plustime) { if ($s[time_form]=='12') $datum .= date(', g:i a',$cas); else $datum .= date(', G:i',$cas); }
return $datum;
}

##################################################################################

function get_order_variables($what,$n) {
global $s;
$q = dq("select * from $s[pr]orders where n = '$n'",1);
return mysqli_fetch_assoc($q);
}

##################################################################################

function get_user_variables($n) {
global $s;
$q = dq("select * from $s[pr]users where n = '$n'",1);
return mysqli_fetch_assoc($q);
}

##################################################################################

function get_messages() {
global $s,$m;
if ($s[GC_style]) $style = $s[GC_style]; else $style = $s[def_style];
if (file_exists($s[phppath].'/styles/'.$style.'/messages/common.php')) $x = $style;
else $x = '_common';
include("$s[phppath]/styles/$x/messages/user.php");
include("$s[phppath]/styles/$x/messages/common.php");
}

################################################################################

function check_logged_user() {
global $s;
if ((!$_SESSION[GC_u_n]) AND (!$_COOKIE[GC_u_n]) AND (!$s[GC_u_n])) exit;
if ($_SESSION[GC_u_n]) $user = get_user_variables($_SESSION[GC_u_n]);
elseif ($_COOKIE[GC_u_n]) $user = get_user_variables($_COOKIE[GC_u_n]);
elseif ($s[GC_u_n]) $user = get_user_variables($s[GC_u_n]);
$s[GC_u_password] = $user[password];
$s[GC_u_name] = $user[name];
$s[GC_u_email] = $user[email];
$s[GC_u_n] = $user[n];
$s[GC_u_style] = $s[GC_style] = $user[style];
if (($_POST) AND (!$s['no_test'])) check_field("$user[email]$user[password]$user[n]");
return $user;
}

##################################################################################
##################################################################################
##################################################################################

?>
