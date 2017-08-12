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
$s[selected_menu] = 5;
get_messages('contact.php');
include_once("$s[phppath]/data/data_forms.php");

if (!$_POST) $_POST = $_GET;//new ajax
$_POST = replace_array_text($_POST);
if (!$_POST) contact_page();

$x = form_control($_POST);
$in = $x[1];
if ($x[0])
{ if ($_POST[page]) contact_page($in);
  elseif ($_POST[suggest_form_category]) echo stripslashes(suggest_category_box($in[n],'<br>'.info_panel($m[errorsfound],implode('<br />',$x[0]))));
  elseif ($_POST[action]=='claim_listing') echo stripslashes(claim_listing_box('l',$in[n],'<br>'.info_panel($m[errorsfound],implode('<br />',$x[0]))));
  else echo stripslashes(contact_box($in[what],$in[n],'<br>'.info_panel($m[errorsfound],implode('<br />',$x[0]))));
  exit;
}

$from = $in[email]; if (!$in[to]) $in[to] = $s[mail];
if ($s[subject]) $subject = $s[subject];
else $subject = $m[subject].' '.$s[site_name];

//echo "($from,$from,$in[to],0,$subject,$in[text],1)";
my_send_mail($from,$from,$in[to],0,$subject,$in[text],1);

if ($_POST[page]) { $s[info] = info_panel($m[message_sent]); contact_page(); }

echo '<br>'.info_panel($m[message_sent]);
exit;

###############################################################################

function contact_page($a) {
global $s;
if ($s[message_to_us_captcha]) $a[field_captcha_test] = parse_part('form_captcha_test.txt',$a);
$a[info] = $s[info];
page_from_template('contact.html',$a);
}

###############################################################################

function form_control($in) {
global $s,$m;
//foreach ($in as $k=>$v) $in[$k] = utf8_decode($v);
//foreach ($in as $k=>$v) $in[$k] = iconv('UTF-8',$s[charset],$v);
$in = replace_array_text($in);

$in[message] = trim($in[message]);
if (!$in[message]) $chyba[] = $m[m_text];
$black = try_blacklist($in[message],"word");
if ($black) $chyba[] = $black;

$in[name] = trim($in[name]);
if (!$in[name]) $chyba[] = "$m[missing_field] $m[name]";

$in[email] = trim($in[email]);
if (!$in[email]) $chyba[] = "$m[missing_field] $m[email]";
elseif (!check_email($in[email])) $chyba[] = $m[w_email];

//if ($in[what]=='ad')
if (($in[n]) AND (is_numeric($in[n])))
{ $need_captcha = $s[message_owner_captcha];
  if ($in[what]=='u') $a = get_user_variables($in[n]);
  else
  { $a = get_ad_variables($in[n]);
    $in[ad_url] = get_detail_page_url('ad',$a[n],$a[rewrite_url],$a[category]);
    $in[message] .= "\n$in[ad_url]";
  }
  if ($a[email]) $in[to] = $a[email]; else info_panel($m[unable]);
  $in[text] = "$in[message]\n\n$m[email]: $in[email]\n$m[name]: $in[name]";
}
else
{ $need_captcha = $s[message_to_us_captcha];
  $in[text] = "$in[message]\nEmail: $in[email]\nName: $in[name]\nIP: $s[ip]\n\n";
  $in[to] = $s[mail];
}

if ($need_captcha) { $x = check_entered_captcha($in[image_control]); if ($x) $chyba[] = $x; }
$s[info] = info_panel($m[errorsfound],implode('<br />',$chyba));
return array ($chyba,$in);
}

###############################################################################
###############################################################################
###############################################################################

?>