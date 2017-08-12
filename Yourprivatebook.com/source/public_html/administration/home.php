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

if ($_GET[action]=='left_frame') left_frame();
if ($_GET[action]=='home') home();
if ($_GET[action]=='statistic') statistic();
if ($_GET[action]=='categories_list') categories_list($_GET[what]);
header ("Location: index.php");
exit;

#########################################################################
#########################################################################
#########################################################################

function left_frame() {
global $s;
if (($_SESSION[GC_admin_user]) AND ($_SESSION[GC_admin_password]))
{ $username = $_SESSION[GC_admin_user]; $password = $_SESSION[GC_admin_password]; }
else { $username = $_COOKIE[GC_admin_user]; $password = $_COOKIE[GC_admin_password]; }
$q = dq("select $s[pr]admins_rights.* from $s[pr]admins,$s[pr]admins_rights where $s[pr]admins.username = '$username' and $s[pr]admins.password = '$password' and $s[pr]admins.n = $s[pr]admins_rights.admin",1);
foreach ($s as $k=>$v) if (!is_object($v))  { $my_data .= "&$k=".urlencode($v); $pocet++; if ($pocet>20) break; }
while ($x = mysqli_fetch_assoc($q)) $rights[] = $x[action];

$left = '<a target="right" href="'; $right = '</a><br>';
$homepage = 'index.php';
ih();

echo '<table border="0" width="100%" cellspacing="0" cellpadding="0" class="common_table"><tr><td align="left" colspan="2">';

echo '<div class="common_table_top_cell" style="text-align:left;padding-left:3px;padding-top:7px;">&nbsp;Gold Classifieds</div>
<div style="padding:3px;padding-bottom:15px;display:block;" id="div0">';
echo '</div>';

echo '<div class="common_table_top_cell" style="text-align:left;" onClick="open_close(\'div1\')";><img border="0" src="images/menu_classifieds.png" style="margin-top:6px;margin-left:4px;margin-right:6px;">Classified&nbsp;Ads</div>
<div style="padding:3px;padding-bottom:15px;display:block;" id="div1">';
if (in_array('ads',$rights)) echo $left."ads_list.php?action=ads_queue_home\">Queue".$right;
if (in_array('ads',$rights)) echo $left."ad_details.php?action=ad_create_step_1\">Create".$right;
if (in_array('ads',$rights)) echo $left."ads_list.php?action=ads_import_form\">Import ads".$right;
if (in_array('ads',$rights)) echo $left."ads_reports.php?action=abuse_reports\">Abuse reports".$right;
if (in_array('ads',$rights)) echo $left."ads_list.php?action=ads_search\">Search".$right;
if (in_array('search_log',$rights)) echo $left."search_log.php?action=search_log_info\">Search log".$right;
if (in_array('prices',$rights)) echo $left."prices.php?action=ads_prices_home\">Prices".$right;
if (in_array('users',$rights)) echo $left."orders_payments.php?action=orders_search\">Orders & Payments".$right;
echo '</div>';

echo '<div class="common_table_top_cell" style="text-align:left;" onClick="open_close(\'div2\')";><img border="0" src="images/menu_categories.png" style="margin-top:6px;margin-left:4px;margin-right:6px;">Categories</div>
<div style="padding:3px;padding-bottom:15px;display:block;" id="div2">';
if (in_array('categories',$rights)) echo $left."categories.php?action=categories_home\">Create & Edit".$right;
if (in_array('categories',$rights)) echo $left."categories.php?action=categories_tree\">List".$right;
if (in_array('categories',$rights)) echo $left."categories.php?action=categories_import_form\">Import".$right;
if (in_array('categories',$rights)) echo $left."import_oodle.php?action=oodle_categories_steps\">Import from Oodle.com".$right;
echo '</div>';

echo '<div class="common_table_top_cell" style="text-align:left;" onClick="open_close(\'div3\')";><img border="0" src="images/menu_areas.png" style="margin-top:6px;margin-left:4px;margin-right:6px;">Areas</div>
<div style="padding:3px;padding-bottom:15px;display:block;" id="div3">';
if (in_array('areas',$rights)) echo $left."areas.php?action=areas_home\">Create & Edit".$right;
if (in_array('areas',$rights)) echo $left."areas.php?action=areas_tree_admin\">List".$right;
if (in_array('areas',$rights)) echo $left."areas.php?action=areas_import_form\">Import".$right;
echo '</div>';

echo '<div class="common_table_top_cell" style="text-align:left;" onClick="open_close(\'div4\')";><img border="0" src="images/menu_tools.gif" style="margin-top:6px;margin-left:4px;margin-right:6px;">Tools</div>
<div style="padding:3px;padding-bottom:15px;display:block;" id="div4">';
if (in_array('polls',$rights)) echo $left."polls.php?action=polls_home\">Polls".$right;
if (in_array('board_comments',$rights)) echo $left."board.php?action=board\">Message board".$right;
if (in_array('blacklist',$rights)) echo $left."blacklist.php?action=blacklist_home\">Blacklist".$right;
echo '</div>';

echo '<div class="common_table_top_cell" style="text-align:left;" onClick="open_close(\'div5\')";><img border="0" src="images/menu_users.png" style="margin-top:6px;margin-left:4px;margin-right:6px;">Users</div>
<div style="padding:3px;padding-bottom:15px;display:block;" id="div5">';
if (in_array('users',$rights)) echo $left."users.php?action=users_home\">Search".$right;
if (in_array('users',$rights)) echo $left."users.php?action=user_create\">Create".$right;
if (in_array('email_users',$rights)) echo $left."users.php?action=email_users\">Send email".$right;
if (in_array('email_users',$rights)) echo $left."users.php?action=newsletter\">Newsletter".$right;
if (in_array('prices',$rights)) echo $left."prices.php?action=users_prices_home\">Prices".$right;
if (in_array('users',$rights)) echo $left."orders_payments.php?action=orders_search&what=u\">Orders & Payments".$right;
if (in_array('prices',$rights)) echo $left."coupons.php\">Coupons".$right;
echo '</div>';

echo '<div class="common_table_top_cell" style="text-align:left;" onClick="open_close(\'div6\')";><img border="0" src="images/menu_system.png" style="margin-top:6px;margin-left:4px;margin-right:6px;">System</div>
<div style="padding:3px;padding-bottom:15px;display:block;" id="div6">';
echo $left.'home.php?action=statistic">Statistic'.$right;
if (in_array('reset_rebuild',$rights)) echo $left."rebuild.php?action=reset_rebuild_home\">Reset/rebuild".$right;
if (in_array('database_tools',$rights)) echo $left."database_tools.php?action=database_home\">Database tools".$right;
if (in_array('admins',$rights)) echo $left."administrators.php?action=admins_home\">Administrators".$right;
if (in_array('configuration',$rights)) echo $left."database_tools.php?action=uninstall\">Uninstall".$right;
echo '</div>';

echo '<div class="common_table_top_cell" style="text-align:left;" onClick="open_close(\'div7\')";><img border="0" src="images/menu_configuration.gif" style="margin-top:6px;margin-left:4px;margin-right:6px;">Configuration</div>
<div style="padding:3px;padding-bottom:15px;display:block;" id="div7">';
if (in_array('configuration',$rights)) echo $left."configuration_main.php\">Main configuration".$right;
if (in_array('configuration',$rights)) echo $left."configuration_forms.php?action=configuration_edit_submit_forms\">Public forms".$right;
if (in_array('configuration',$rights)) echo $left."configuration_formats.php\">File formats".$right;
if (in_array('templates',$rights)) echo $left."templates.php?action=templates_home\">Templates".$right;
if (in_array('messages',$rights)) echo $left."messages.php?action=messages_home\">Messages".$right;
echo '</div>';

echo '<div class="common_table_top_cell" style="text-align:left;" onClick="open_close(\'div8\')";><img border="0" src="images/menu_locations.png" style="margin-top:6px;margin-left:4px;margin-right:6px;">Locations</div>
<div style="padding:3px;padding-bottom:15px;display:block;" id="div8">';
if (in_array('configuration',$rights)) echo $left.'ip_country.php?action=countries">Countries'.$right;
if (in_array('configuration',$rights)) echo $left.'ip_country.php">IP/country data'.$right;
//if (in_array('configuration',$rights)) echo $left.'ip_country.php?action=ip_city_home">IP/city data'.$right;
if (in_array('configuration',$rights)) echo $left.'latitudes.php">Latitudes & longitudes'.$right;
echo '</div>';

echo '<div class="common_table_top_cell" style="text-align:left;" onClick="open_close(\'div7\')";><img border="0" src="images/menu_other.png" style="margin-top:6px;margin-left:4px;margin-right:6px;">Other&nbsp;Options</div>
<div style="padding:3px;padding-bottom:15px;display:block;" id="div7">';
echo $left."../$homepage\">Your Home Page".$right;
echo '<a target="right" href="http://www.abscripts.com/">AbScripts.com'.$right;
echo '<a target="_top" href="login.php?action=log_off">Log off'.$right;
echo '</div>';


echo '</td></tr></table></td></tr></table>'.mc_test();
?>
<script>
for (x=1;x<=13;x++) { if (get_cookie('div'+x)==1) show_hide_div_id(0,'div'+x); }
</script>
<?PHP

if (file_exists($s[phppath].'/setup.php')) popup_security_window("You did not delete file \"setup.php\" in main directory. It\'s a security risk. Please delete it as soon as possible.");
elseif (file_exists($s[phppath].'/administration/create_admin.php')) popup_security_window("You did not delete file \"create_admin.php\" in administration directory. It\'s a security risk. Please delete it as soon as possible.");
elseif (file_exists($s[phppath].'/update.php')) popup_security_window("You did not delete file \"update.php\" in main directory. It\'s a security risk. Please delete it as soon as possible.");
elseif (file_exists($s[phppath].'/data/uninstall')) popup_security_window("You have file \"uninstall\" in data directory. It\'s a security risk. Please delete it if you don\'t plan to uninstall the script from this server in the near future.");
exit;
}

######################################################################################

function popup_security_window($text) {
echo '<script language="Javascript">
  <!--
  alert(\''.$text.'\');
  -->
  </script>';
}

#########################################################################
#########################################################################
#########################################################################

function home() {
global $s;
ih();
echo page_title('Gold Classifieds Administration');
get_news();
statistic_table();
ift(); 
}

#########################################################################

function get_news() {
global $s;
$news = stripslashes(implode('',file("http://www.abscripts.com/scripts/info_for_users.php?sc=$s[cs]")));
if ($news)
{ echo '<table border=0 width=500 cellspacing=0 cellpadding=2 class="common_table">
  <tr><td class="common_table_top_cell">Lates News</td></tr>
  <tr><td align="center">
  <table border=0 width=100% cellspacing=10 cellpadding=0>
  <tr><td align="left">'.$news.'</td></tr>
  <tr><td align="center"><a target="_blank" href="http://www.abscripts.com/">www.abscripts.com</a></td></tr>
  </table>
  </td></tr></table><br>';
}
}

#########################################################################

function statistic() {
global $s;
check_admin(0);
ih();
statistic_table();
ift(); 
}

#########################################################################
#########################################################################
#########################################################################

?>