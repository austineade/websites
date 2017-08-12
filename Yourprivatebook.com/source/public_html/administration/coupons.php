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
check_admin('prices');

switch ($_GET[action]) {
case 'coupons_home'			: coupons_home();
}

switch ($_POST[action]) {
case 'coupons_created'		: coupons_created($_POST);
}
coupons_home();

##################################################################################
##################################################################################
##################################################################################

function coupons_created($in) {
global $s;

if ($in[to_do]=='display')
{ $coupons = $in[coupons];
  ih();
  echo info_line('Coupons Created');
  echo '<table border="0" width="750" cellspacing="0" cellpadding="2" class="common_table">
  <tr><td nowrap class="common_table_top_cell">Copy coupons from this box</td></tr>
  <tr><td align="center"><textarea class="form-control" name="x" style="width:650px;height:500px;">';
}
else
{ $emails_array = explode("\n",trim($in[emails])); $coupons = count($emails_array); 
  $in[emailtext] = stripslashes($in[emailtext]);
}

$valid_by = 86400 * $in[days] + $s[cas];
if ($in[what]=='percent') $discount_percent = $in[amount]; else $discount_money = $in[amount];

for ($x=1;$x<=$coupons;$x++)
{ $coupon_code = get_random_coupon($in[coupon_length]);
  dq("insert into $s[pr]coupons values (NULL,'$coupon_code','$valid_by','$discount_percent','$discount_money','$in[minimal_amount]','$in[max_use]','0')",1);
  $coupon_code = $coupon_code;
  if ($in[to_do]=='display') echo "$coupon_code\n";
  elseif ($in[to_do]=='email')
  { $email_n = $x - 1;
    $email_address = $emails_array[$email_n];
    $emailtext = str_replace('#%coupon_code%#',$coupon_code,$in[emailtext]);
    my_send_mail('','',$email_address,$in[htmlmail],$in[emailsubject],$emailtext,0);
    increase_print_time(2,1);
  }
}
if ($in[to_do]=='display') { echo '</textarea>'; ift(); }
increase_print_time(1,'end');
$s[info] = info_line("$coupons Coupons Created");
coupons_home();
}

##################################################################################

function get_random_coupon($coupon_length) {
list($usec,$sec) = explode(' ',microtime());
$x = $sec+($usec*1000000);
return substr(md5($x.$s[cas]),0,$coupon_length);
}

##################################################################################
##################################################################################
##################################################################################

function coupons_home() {
global $s;
ih();
echo $s[info];
echo '<form action="coupons.php" method="post">'.check_field_create('admin').'
<input type="hidden" name="action" value="coupons_created">
<table border="0" width="750" cellspacing="0" cellpadding="0" class="common_table">
<tr><td nowrap class="common_table_top_cell" colspan="2">Create New Coupon(s)</td></tr>
<tr><td align="center">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="inside_table">
<tr><td align="left" colspan="2">Users who use one of these coupons to pay for extra features or for their membership get a discount, depending on the configuration above.</td></tr>
<tr>
<td align="left" valign="top">Users who use this coupon receive discount </td>
<td align="left" valign="top"><input class="form-control" size=10 name="amount" maxlength=255 value="'.$in[title].'">&nbsp;<select class="form-control"  name="what"><option value="percent">%</option><option value="money">'.$s[currency].'</option></select></td>
</tr>
<tr>
<td align="left" valign="top">Can be used only if the order price is </td>
<td align="left" valign="top">'.$s[currency].'<input class="form-control" size=10 name="minimal_amount" maxlength=255> or more</td>
</tr>
<tr>
<td align="left" valign="top">Coupons will be valid for </td>
<td align="left" valign="top"><input class="form-control" size=10 name="days" maxlength=255> days</td>
</tr>
<tr>
<td align="left" valign="top">Each coupon can be used </td>
<td align="left" valign="top"><input class="form-control" size=10 name="max_use" maxlength=255> times</td>
</tr>
<tr>
<td align="left" valign="top">Number of characters of  coupon code </td>
<td align="left" valign="top"><select class="form-control"  name="coupon_length">';
for ($x=1;$x<=30;$x++) echo '<option value="'.$x.'">'.$x.'</option>';
echo '</select></td>
</tr>
<tr>
<td align="left" valign="top" nowrap>Number of coupons to create </td>
<td align="left" valign="top"><input class="form-control" size=10 name="coupons" maxlength=255><span class="text10"><br>Enter this value only if coupons will not be send to emails</span></td>
</tr>
<tr>
<td align="left" valign="top">Action </td>
<td align="left" valign="top" nowrap>
<input type="radio" name="to_do" value="display" checked> Create coupons and display them, I will use them for my needs<br>
<input type="radio" name="to_do" value="email"> Create coupons and send them by email<span class="text10"><br>If checked, enter also values to the fields below</span><br>
</td>
</tr>
<tr>
<td align="left" valign="top">Send coupons to these emails<br><span class="text10"><br>One email address per line.</span></td>
<td align="left" valign="top"><textarea class="form-control" name="emails" style="width:650px;height:200px;"></textarea></td>
</tr>';
echo '<tr>
<td align="left" valign="top">Email subject </td>
<td align="left" valign="top"><input class="form-control" type="text" size="70" style="width:650px;" name="emailsubject" value="" id="email_subject"></td>
</tr>
<tr>
<td align="left" valign="top">Email text <br><span class="text10"><br>Use the variable #%coupon_code%# in this text. This variable will be replaced by the generated coupon URL.</span></td>
<td align="left" valign="top"><textarea class="form-control" style="width:650px;height:200px;" name="emailtext" id="email_text"></textarea></td>
</tr>
<tr>
<td align="left" valign="top">Email format </td>
<td align="left" valign="top"><input type="radio" name="htmlmail" value="0" checked> Text &nbsp;&nbsp;<input type="radio" name="htmlmail" value="1"> HTML</td>
</tr>
<tr><td align="center" colspan="2"><input type="submit" name="A1" value="Submit" class="button10"></td></tr>
</table></td></tr></table></form>';
ift();
}

#########################################################################
#########################################################################
#########################################################################

?>