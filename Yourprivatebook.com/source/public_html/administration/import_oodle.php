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
set_time_limit(0); 
check_admin('categories');

switch ($_GET[action]) {
case 'oodle_categories_steps'			: oodle_categories_steps($_GET);
case 'categories_from_oodle_download'	: categories_from_oodle_download($_GET[filename]);
case 'categories_temp_import'			: categories_temp_import();
case 'categories_temp_show'				: categories_temp_show($_GET[n]);
case 'categories_to_gold_classifieds'	: categories_to_gold_classifieds($_GET);
}

########################################################################################

function oodle_categories_steps() {
global $s;
ih();
echo '<form action="import_oodle.php" method="get">
<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">
<tr><td align="center" width="100%" class="common_table_top_cell">Import categories from Oodle.com</td></tr>
<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
<tr><td align="left">Pick one of these steps.<br>Each of these steps may take a long time to finish. Some servers may be set to not to allow scripts so much time for their work. If the script stops to response, it\'s probably this case and the import will not work properly on your server.</td></tr>
<!--<tr><td align="left"><input type="radio" name="action" value="categories_from_oodle_download">&nbsp;Download the file oodle_categories.xml<br><input class="form-control" name="filename" value="http://developer.oodle.com/files/xml/oodle_categories.xml" size="100"><br><span class="text10">It downloads the categories data file and saves it to your data directory. You also can download it manually upload it to your server to data directory.<br>Skip this step if the file has been downloaded in the past.<br></span></td></tr>-->
<tr><td align="left"><input type="radio" name="action" value="categories_temp_import">&nbsp;Load the file oodle_categories.xml and import all categories in a temporary table in the database<br><span class="text10">Skip this step if your already finished it in the past.<br></span></td></tr>
<tr><td align="left"><input type="radio" name="action" value="categories_temp_show">&nbsp;Show imported categories and optionally import them to Gold Classifieds</td></tr>
<tr><td align="center"><input type="submit" name="co" value="Submit" class="button10"></td></tr>
</table></td></tr></table>
</form>';
ift();
}

########################################################################################

function categories_from_oodle_download($file_url) {
global $s;
ih();
$file = fopen($file_url,'r') or die("Unable to read file $file_url");
$openfile = fopen("$s[phppath]/data/oodle_categories.xml",'w') or die("Unable to write to file $s[phppath]/data/oodle_categories.xml");
while ($data = fread($file,1000))
{ increase_print_time(5,1);
  fwrite($openfile,$data) or die("Unable to write to file $s[phppath]/data/oodle_categories.xml");
}
fclose ($file);
fclose($openfile);
increase_print_time(5,'end');
echo info_line('All done');
echo '<a href="import_oodle.php?action=oodle_categories_steps">Show list of available steps</a><br><br>';
ift();
}

########################################################################################

function categories_temp_import() {
global $s;
ih();

$xml = simplexml_load_string(implode('',file("$s[phppath]/data/oodle_categories.xml")));
$json = json_encode($xml);
$array = json_decode($json,TRUE);
$b = $array[root];

dq("truncate table $s[pr]cats_oodle",1);

foreach ($b as $first_level_n => $v)
{
  $first_level_name = $v['@attributes'][name];
  $cats_array = $v[category];
  foreach ($cats_array as $k => $cat_array) foreach ($cat_array as $k2 => $v2)
  { //echo "$v2[url]-$v2[name]<br>";
  $category_path = $v2[url];
  $category_title = $v2[name];
  increase_print_time(5,1);
  $category_path = trim($category_path);
  $category_array = explode('/',$category_path);
  $level = count($category_array); if (!$level) continue;
  $x = array_pop($category_array);
  $parent_path = implode('/',$category_array);
  $category_title = str_replace("'",'',$category_title);
  $parent_path = str_replace("'",'',$parent_path);
  $category_path = str_replace("'",'',$category_path);
  dq("insert into $s[pr]cats_oodle values(NULL,'$category_title','$level','$parent_path','$category_path')",1);
  if (!($pocet%1000)) echo "<b>$pocet</b> ";
  $pocet++; 
  //if ($pocet==100) exit;
}
}
increase_print_time(5,'end');
echo info_line('All done');
echo '<a href="import_oodle.php?action=oodle_categories_steps">Show list of available steps</a><br><br>';
ift();
}

########################################################################################

function categories_temp_show($parent) {
global $s;
ih();
if ($parent)
{ $q = dq("select * from $s[pr]cats_oodle where n = '$parent'",1);
  $parent_vars = mysqli_fetch_assoc($q);
  if ((function_exists('iconv')) AND ($s[charset]!='')) $parent_vars[title] = iconv('UTF-8',$s[charset],$parent_vars[title]);
  if ($parent_vars[level]>1)
  $q = dq("select * from $s[pr]cats_oodle where path = '$parent_vars[parent_path]'",1);
  $big_parent_vars = mysqli_fetch_assoc($q);
  if ((function_exists('iconv')) AND ($s[charset]!='')) $big_parent_vars[title] = iconv('UTF-8',$s[charset],$big_parent_vars[title]);
}

echo '<table border="0" width="900" cellspacing="0" cellpadding="0" class="common_table">';
if ($parent_vars[n])
{ echo '<tr><td align="center" width="100%" class="common_table_top_cell">Category '.$parent_vars[title].'</td></tr>';
  if ($big_parent_vars[n]) echo '<tr><td align="left" width="100%"><a href="import_oodle.php?action=categories_temp_show&n='.$big_parent_vars[n].'">&nbsp;&nbsp;<<< Back to category '.$big_parent_vars[title].'</a></td></tr>';
  else echo '<tr><td align="left" width="100%"><a href="import_oodle.php?action=categories_temp_show">&nbsp;&nbsp;<<< Back to first level categories</a></td></tr>';
}
else echo '<tr><td align="center" width="100%" class="common_table_top_cell">First Level Categories</td></tr>';
echo '<tr><td align="center" width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">';

$q = dq("select * from $s[pr]cats_oodle where parent_path = '$parent_vars[path]' order by title",1);
while ($cat = mysqli_fetch_assoc($q))
{ $q1 = dq("select count(*) from $s[pr]cats_oodle where parent_path = '$cat[path]'",1); $subcategories = mysqli_fetch_row($q1);
  if ((function_exists('iconv')) AND ($s[charset]!='')) $cat[title] = iconv('UTF-8',$s[charset],$cat[title]);
  echo '<tr>
  <td align="left" nowrap><b>'.str_replace('_',' ',$cat[title]).'</b></td>';
  if ($subcategories[0])   echo '<td align="left" nowrap><a href="import_oodle.php?action=categories_temp_show&n='.$cat[n].'">Show subcategories ('.$subcategories[0].')</a></td>
  <td align="left" nowrap><a href="import_oodle.php?action=categories_to_gold_classifieds&n='.$cat[n].'">Import this category and its subcategories to Gold Classifieds</a></td>';
  else echo '<td align="left" nowrap>No subcategories</td>
  <td align="left" nowrap><a href="import_oodle.php?action=categories_to_gold_classifieds&n='.$cat[n].'">Import this category as a first level category to Gold Classifieds</a></td>';
  echo '</tr>';
}

echo '</table></td></tr></table>
';
echo '<br><a href="import_oodle.php?action=oodle_categories_steps">Show list of available steps</a><br><br>';
ift();
}

########################################################################################

function categories_to_gold_classifieds($in) {
global $s;

ih();
if (!$in[n]) exit;

$q = dq("select * from $s[pr]cats_oodle where n = '$in[n]'",1);
$parent_vars = mysqli_fetch_assoc($q);
if ((function_exists('iconv')) AND ($s[charset]!='')) $parent_vars[title] = iconv('UTF-8',$s[charset],$parent_vars[title]);
$parent_vars[title] = str_replace('_',' ',$parent_vars[title]);

$q = dq("select max(level) from $s[pr]cats_oodle where parent_path like '$parent_vars[path]%'",1); $level = mysqli_fetch_row($q);
$q = dq("select * from $s[pr]cats_oodle where parent_path = '$parent_vars[path]' OR parent_path like '$parent_vars[path]/%' order by level",1);

if (($num_subcats=mysqli_num_rows($q)) AND (!$in[confirmed]))
{ echo info_line('You have selected to import the category '.$parent_vars[title].' and its subcategories','This category has <b>'.$num_subcats.' subcategories</b>.<br>This category will be imported as a first level category to Gold Classifieds.<br>Also its subcategories in levels 1 - '.($level[0]-$parent_vars[level]).' will be imported.');
  echo '<table border="0" width="750" cellspacing="0" cellpadding="0" class="common_table">
  <tr><td class="common_table_top_cell">Choose one from the following options</td></tr>
  <tr><td align="center"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="inside_table">
';
  if ($level[0]-$parent_vars[level])
  { for ($x=1;$x<=($level[0]-$parent_vars[level]);$x++)
    if ($x==1)   echo '<tr><td align="center"><a href="import_oodle.php?action=categories_to_gold_classifieds&n='.$in[n].'&confirmed=1&levels='.$x.'">Click here to import the category '.$parent_vars[title].' and its subcategories in level 1</a></td></tr>';
    else echo '<tr><td align="center"><a href="import_oodle.php?action=categories_to_gold_classifieds&n='.$in[n].'&confirmed=1&levels='.$x.'">Click here to import the category '.$parent_vars[title].' and its subcategories in levels 1 - '.$x.'</a></td></tr>';
  }
  echo '</table></td></tr></table>';
  echo '<br><br><br><a href="import_oodle.php?action=categories_temp_show">Cancel this import</a><br><br>';
  ift();
}

increase_print_time(5,1);
$m_keywords = str_replace(' ',', ',$parent_vars[title]);
dq("insert into $s[pr]cats values (NULL,'0','1','0','0','$parent_vars[title]','$in[description]','','','$m_keywords','$in[m_desc]','0','','','1','0','','','category.html','ad_a.txt','ad_details.html','','1','0','1','0','1','1','0','0')",1);
$cislo = mysqli_insert_id($s[db]);
update_category_area_paths('c',$cislo);
$parents[$cislo] = $parent_vars[path];
echo 'Category "'.$parent_vars[title].'" has been created<br>';
increase_print_time(5,1);

while ($c = mysqli_fetch_assoc($q))
{ if (($in[levels]) AND (($c[level]-$parent_vars[level])>$in[levels])) break;
  $current_parent = array_search($c[parent_path],$parents);
  if ((function_exists('iconv')) AND ($s[charset]!='')) $c[title] = iconv('UTF-8',$s[charset],$c[title]);
  $c[title] = str_replace('_',' ',$c[title]);
  $m_keywords = str_replace(' ',', ',$c[title]); 
  dq("insert into $s[pr]cats values (NULL,'$current_parent','$level','0','0','$c[title]','$in[description]','','','$m_keywords','$in[m_desc]','0','','','1','0','','','category.html','ad_a.txt','ad_details.html','','1','0','$in[in_menu]','0','1','1','0','0')",1);
  $cislo = mysqli_insert_id($s[db]);
  update_category_area_paths('c',$cislo);
  $parents[$cislo] = $c[path];
  echo 'Category "'.$c[title].'" has been created<br>';
  increase_print_time(5,1);
}
echo '</span></span>';
echo '<br><br><br><a href="import_oodle.php?action=oodle_categories_steps">Show list of available steps</a><br><br>';
ift();
}

########################################################################################
########################################################################################
########################################################################################

?>