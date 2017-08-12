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
check_admin('configuration');

switch ($_GET[action]) {
case 'ip_country_home'				: ip_country_home();
case 'ip_country_auto'				: ip_country_auto();
case 'ip_city_home'					: ip_city_home();
case 'ip_city_auto'					: ip_city_auto();
case 'countries'					: countries();
}
switch ($_POST[action]) {
case 'countries_edited'				: countries_edited($_POST);
case 'ip_city_uploaded'				: ip_city_uploaded($_POST);
case 'ip_country_uploaded'			: ip_country_uploaded($_POST);
}
ip_country_home();

#################################################################################

function ip_country_auto() {
global $s;
ih();
$file_url = 'http://geolite.maxmind.com/download/geoip/database/GeoIPCountryCSV.zip';
$file = fopen($file_url,'r') or problem("Unable to download file $file_url");
$openfile = fopen("$s[phppath]/data/GeoIPCountryCSV.zip",'w') or problem("Unable to write to file $s[phppath]/data/GeoIPCountryCSV.zip");
while ($data = fread($file,1000))
{ increase_print_time(5,1);
  fwrite($openfile,$data) or problem("Unable to write to file $s[phppath]/data/GeoIPCountryCSV.zip");
}
fclose ($file);
fclose($openfile);
echo "<br><br><b>File downloaded, starting to unpack it</b><br>";
$zip = new ZipArchive();   
if ($zip->open("$s[phppath]/data/GeoIPCountryCSV.zip")!==TRUE) problem("Could not read archive file $s[phppath]/data/GeoIPCountryCSV.zip");
$zip->extractTo("$s[phppath]/data");
$zip->close();
unlink("$s[phppath]/data/GeoIPCountryCSV.zip");
echo "<br><br><b>File unpacked, starting import it to your database</b><br><br>";
ip_country_uploaded();
}

#################################################################################

function ip_city_auto() {
global $s;
ih();
/*
$file_url = 'http://geolite.maxmind.com/download/geoip/database/GeoLiteCity_CSV/GeoLiteCity-latest.zip';
$file = fopen($file_url,'r') or problem("Unable to download file $file_url");
$openfile = fopen("$s[phppath]/data/GeoLiteCity-latest.zip",'w') or problem("Unable to write to file $s[phppath]/data/GeoLiteCity-latest.zip");
while ($data = fread($file,1000))
{ increase_print_time(5,1);
  fwrite($openfile,$data) or problem("Unable to write to file $s[phppath]/data/GeoLiteCity-latest.zip");
}
fclose ($file);
fclose($openfile);
echo "<br><br><b>File downloaded, starting to unpack it</b><br>";
*/
$zip = new ZipArchive();   
if ($zip->open("$s[phppath]/data/GeoLiteCity-latest.zip")!==TRUE) problem("Could not read archive file $s[phppath]/data/GeoLiteCity-latest.zip");
$pocet = $zip->numFiles;
for ($i = 0; $i < $pocet; $i++) { $filenames[] = $zip->getNameIndex($i); }
$zip->extractTo("$s[phppath]/data");
$zip->close();
foreach ($filenames as $k => $v) { $basename = basename("$s[phppath]/data/$v"); rename("$s[phppath]/data/$v","$s[phppath]/data/$basename"); }
unlink("$s[phppath]/data/GeoLiteCity-latest.zip");
echo "<br><br><b>File unpacked, starting import it to your database</b><br><br>";
ip_city_uploaded();
}

#################################################################################

function ip_country_home() {
global $s;
ih();
echo $s[info];

echo '
<table border="0" width="900" cellspacing="0" cellpadding="2" class="common_table">
<tr><td class="common_table_top_cell">IP-Country Configuration - Info</td></tr>
<tr><td align="left">
This database is used to show the message "Welcome to COUNTRY NAME" on your home page. This message is not available if you did not upload the IP / country database.
</td></tr><table>
<br>
<form method="POST" action="ip_country.php">'.check_field_create('admin').'
<input type="hidden" name="action" value="ip_country_uploaded">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Import Records to IP-Country Database Automatically</td></tr>
<tr><td align="center">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="inside_table">
<tr><td align="left" colspan="2">This script can automatically download the IP country database and unpack it to your server. This process may take a few minutes. This function needs <a target="_blank" href="http://pecl.php.net/package/zip">ZIP extension in PHP</a>. If you receive the message "Cannot instantiate non-existent class: ziparchive", this extension is not available on your server.<br>
 Once it ended its work, you should see a message that it was successful. If you can\'t see this message, your server is unable to use this automatic way. In this case please use the manual upload form below.<br>
</td></tr>
<tr>
<td align="center" valign="top" nowrap><a href="ip_country.php?action=ip_country_auto">Click here to download the file automatically</a></td>
</tr>
</table>
</td></tr></table>
</form>
<br>
<form method="POST" action="ip_country.php">'.check_field_create('admin').'
<input type="hidden" name="action" value="ip_country_uploaded">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Import Records to IP-Country Database Manually</td></tr>
<tr><td align="center">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="inside_table">
<tr><td align="left" colspan="2">You can download the database at <a target="_blank" href="http://www.maxmind.com/app/geolite">http://www.maxmind.com/app/geolite</a>. There is available a free list and also a commercial list which is more precise. Download the database in CSV format, unpack the zip archive to receive a CSV file. Then upload that file to your "data" directory and enter name of the file to the field below. You also should upload new data from time to time to have the statistic as accurate as possible.</td></tr>
<tr>
<td align="left" valign="top" nowrap width="50%">Upload the following file to data directory</td>
<td align="left" nowrap width="50%">GeoIPCountryWhois.csv<br></td>
</tr>
<tr><td align="center" colspan="2"><input type="submit" name="A1" value="Submit" class="button10"></td></tr>
</table>
</td></tr></table>
</form>
<br>
';

if (!$_POST[check_ip]) $_POST[check_ip] = $s[ip]; $s[have_ip] = $_POST[check_ip];
$country_data = get_country_data();
if ($country_data[name]) $country_info = "$country_data[name]"; else $country_info = "Unknown country";
if ($s[city_name]) $country_info .= ", $s[city_name]";
if ($s[region]) $country_info .= ", $s[region]";
echo '<form method="POST" action="ip_country.php">'.check_field_create('admin').'
<input type="hidden" name="action" value="ip_country_home">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">IP Country Check</td></tr>
<tr><td align="center">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="inside_table">
<tr>
<td align="left" valign="top" nowrap>'.$_POST[check_ip].'</span></td>
<td align="left" nowrap>'.$country_info.'</td>
</tr>
<tr>
<td align="left" valign="top" nowrap>Enter another IP to check</span></td>
<td align="left" nowrap><input class="form-control" style="width:550px" name="check_ip" value="'.$_POST[check_ip].'"></td>
</tr>
<tr><td align="center" colspan="2"><input type="submit" name="A1" value="Submit" class="button10"></td></tr>
</table>
</td></tr></table>
</form>
<br>
<br>';

ift();
}

#################################################################################

function ip_city_home() {
global $s;
ih();
echo $s[info];

echo '
<table border="0" width="900" cellspacing="0" cellpadding="2" class="common_table">
<tr><td class="common_table_top_cell">IP-City Configuration - Info</td></tr>
<tr><td align="left">
This database is used to show the message "Welcome to COUNTRY NAME" on your home page. This message is not available if you did not upload the IP / country database.
</td></tr><table>
<br>
<form method="POST" action="ip_country.php">'.check_field_create('admin').'
<input type="hidden" name="action" value="ip_country_uploaded">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Import Records to IP-Country Database Automatically</td></tr>
<tr><td align="center">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="inside_table">
<tr><td align="left" colspan="2">This script can automatically download the IP country database and unpack it to your server. This process may take a few minutes. This function needs <a target="_blank" href="http://pecl.php.net/package/zip">ZIP extension in PHP</a>. If you receive the message "Cannot instantiate non-existent class: ziparchive", this extension is not available on your server.<br>
 Once it ended its work, you should see a message that it was successful. If you can\'t see this message, your server is unable to use this automatic way. In this case please use the manual upload form below.<br>
</td></tr>
<tr>
<td align="center" valign="top" nowrap><a href="ip_country.php?action=ip_city_auto">Click here to download the file automatically</a></td>
</tr>
</table>
</td></tr></table>
</form>
<br>


<form method="POST" action="ip_country.php">'.check_field_create('admin').'
<input type="hidden" name="action" value="ip_city_uploaded">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Import Records to IP/Region/City Database</td></tr>
<tr><td align="center">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="inside_table">
<tr><td align="left" colspan="2">You can download the database at <a target="_blank" href="http://www.maxmind.com/app/geolite">http://www.maxmind.com/app/geolite</a><br>
</td></tr>
<tr>
<td align="left" valign="top" nowrap width="50%">Upload the following files to data directory</span></td>
<td align="left" nowrap width="50%">GeoLiteCity-Blocks.csv<br>GeoLiteCity-Location.csv<br></td>
</tr>
<tr><td align="center" colspan="2"><input type="submit" name="A1" value="Submit" class="button10"></td></tr>
</table>
</td></tr></table>
</form>
<br>

';
ift();
}

#################################################################################

function countries() {
global $s;
ih();
echo $s[info];


echo '
<form method="POST" action="ip_country.php">'.check_field_create('admin').'
<input type="hidden" name="action" value="countries_edited">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td class="common_table_top_cell">Countries & Flags</td></tr>
<tr><td align="center">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="inside_table">
<tr><td align="left" colspan=7>
This list is used to show the message "Welcome to COUNTRY NAME" on your home page. This message is not available if you did not upload the IP / country database.
</td></tr>
<tr><td align="left" colspan="7">Do not edit the codes if you are not sure. You can receive an incorrect statistic if there will be an error.<br>
<!--<b>Allow auto-targeting</b> - It uses IP address of each visitor to determine latitude and longitude. The visitor then can search for ads in a radius around this location.<br>-->
</td></tr>
<tr>
<td align="center">Code</span></td>
<td align="center">Name</span></td>
<td align="center" colspan="2">Flag</span></td>
<!--<td align="center">Allow auto-<br>targeting</span></td>-->
</tr>';
$q = dq("select * from $s[pr]countries order by name",1);
while ($country=mysqli_fetch_assoc($q))
{ if ($country[flag]) $flag = '<img border="0" src="'.$s[site_url].'/images/flags/small/'.$country[flag].'">'; else $flag = '';
  $x++;
  $country[name] = strip_replace_once($country[name]);
  if ($country[country_target]) $country_target = ' checked'; else $country_target = '';
  if ($country[region_target]) { $region_target = ' checked'; $region_target_q_array[] = "country = '$country[code]'"; } else $region_target = '';
  if ($country[city_target]) $city_target = ' checked'; else $city_target = '';
  echo '<tr>
  <td align="center"><input class="form-control" style="width:50px" name="code['.$x.']" value="'.$country[code].'"></td>
  <td align="center"><input class="form-control" style="width:350px" name="name['.$x.']" value="'.$country[name].'"></td>
  <td align="center"><input class="form-control" style="width:350px" name="flag['.$x.']" value="'.$country[flag].'"></td>
  <td align="center">'.$flag.'</td>
  </tr>';
}
for ($y=1;$y<=3;$y++)
{ $x++;
  echo '<tr>
  <td align="center"><input class="form-control" style="width:50px" name="code['.$x.']" value="'.$country[code].'"></td>
  <td align="center"><input class="form-control" style="width:350px" name="name['.$x.']" value="'.$country[name].'"></td>
  <td align="center"><input class="form-control" style="width:350px" name="flag['.$x.']" value="'.$country[flag].'"></td>
  <td align="center">&nbsp;</td>
  </tr>';
}

echo '<tr><td align="center" colspan="5"><input type="submit" name="A1" value="Submit" class="button10"></td></tr>
</table></td></tr></table></form><br>';
ift();
}

#################################################################################

function countries_edited($in) {
global $s;
$in[name] = replace_array_text($in[name]);
dq("delete from $s[pr]countries",1);
foreach ($in[name] as $k=>$name)
{ if (!trim($name)) continue;
  $code = $in[code][$k];
  $flag = $in[flag][$k];
  $country_target = $in[country_target][$k];
  if (($s[ad_region_target]) AND ($country_target)) $region_target = $in[region_target][$k]; else $region_target = 0;
  if (($s[ad_city_target]) AND ($region_target) AND ($country_target)) $city_target = $in[city_target][$k]; else $city_target = 0;
  dq("insert into $s[pr]countries values('$code','$name','$flag','$country_target','$region_target','$city_target')",1);
}
$s[info] = info_line('Records of countries updated');
countries();
}

#################################################################################

function ip_country_uploaded() {
global $s;
if (!file_exists("$s[phppath]/data/GeoIPCountryWhois.csv")) { $s[upload_error] = 1; ip_country_home(); }
dq("delete from $s[pr]ip_country where city = 0 and region = ''",1);
$fd = fopen("$s[phppath]/data/GeoIPCountryWhois.csv",'r');
while (!feof ($fd))
{ $line = fgets($fd,10000);
  if (!trim($line)) continue;
  list($ip1,$ip2,$ip_code1,$ip_code2,$cc,$c_name) = explode(',',str_replace('"','',trim(stripslashes($line))));
  //if ($cc!='UK') continue;
  //echo "($ip1,$ip2,$ip_code1,$ip_code2,$cc,$c_name)";exit;
  set_time_limit(300);
  dq("insert into $s[pr]ip_country values('$ip_code1','$ip_code2','$cc','',0,'')",1);
  $pocet++;
  increase_print_time(2,1);
}
fclose ($fd);
increase_print_time(2,'end');
$s[info] = info_line("IP records imported: $pocet");
ip_country_home();
}

#################################################################################

function ip_city_uploaded() {
global $s;
if (!file_exists("$s[phppath]/data/GeoLiteCity-Blocks.csv")) { $s[upload_error] = 1; ip_country_home(); }

$q = dq("select * from $s[pr]countries where region_target = 1",1);
while ($x = mysqli_fetch_assoc($q)) $alowed_countries[$x[code]] = $x;

dq("truncate table $s[pr]cities");
$fd = fopen("$s[phppath]/data/GeoLiteCity-Location.csv",'r');
while (!feof ($fd))
{ $line = fgets($fd,10000);
  set_time_limit(300);
  if (!trim($line)) continue;
  list($n,$country,$region,$city_name) = explode(',',str_replace('"','',trim(stripslashes($line))));
  if (!$alowed_countries[$country]) continue;
  if (!$alowed_countries[$country][city_target]) continue;
  if (!$city_name) continue;
  $city_name = addslashes($city_name);
  $q = dq("select * from $s[pr]cities where country = '$country' and region = '$region' and name = '$city_name'",1);
  $existing = mysqli_fetch_assoc($q);
  if ($existing[n]) $numbers[$n] = $existing[n];
  else
  { dq("insert into $s[pr]cities values('$n','$country','$region','$city_name')",1);
    $numbers[$n] = $n;
  }
  increase_print_time(2,1);
  $pocet++;
}
  echo "<b>$pocet</b>";
fclose ($fd);
foreach ($numbers as $k => $v) echo "$k - $v<br>\n";
exit;

$q = dq("select * from $s[pr]cities",1); while ($x = mysqli_fetch_assoc($q)) $cities[$x[n]] = $x;
dq("delete from $s[pr]ip_country where city > 0 or region != ''",1);
$fd = fopen("$s[phppath]/data/GeoLiteCity-Blocks.csv",'r');
while (!feof ($fd))
{ $line = fgets($fd,10000);
  set_time_limit(300);
  if (!trim($line)) continue;
  list($ip_code1,$ip_code2,$city) = explode(',',str_replace('"','',trim(stripslashes($line))));
  if (!$numbers[$city]) continue;
  $city_number = $numbers[$city]; $city_data = $cities[$city_number];
  //foreach ($city_data as $k => $v) echo "$k - $v<br>\n";exit;
  $city_data[name] = addslashes($city_data[name]);
  dq("insert into $s[pr]ip_country values('$ip_code1','$ip_code2','$city_data[country]','$city_data[region]','$city_number','$city_data[name]')",1);
  $pocet++;
  //echo "$pocet ";
  increase_print_time(2,1);
}
fclose ($fd);

increase_print_time(2,'end');
$s[info] = info_line("IP records imported: $pocet");
ip_country_home();
}

#################################################################################

?>