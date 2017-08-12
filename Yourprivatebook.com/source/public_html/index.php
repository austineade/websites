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
if ((isset($_GET[c])) AND (isset($_GET[a]))) bottom_redirect();
get_messages('index.php');
set_time_limit(600);
if ($s[rebuild_auto])
{ include('./administration/rebuild_functions.php');
  load_times();
  if (($s[times_d]+86400) < $s[cas]) daily_job(0);
}
if (isset($s[this_cat])) category_page($s[this_cat]);
else home_page();

#############################################################################
#############################################################################
#############################################################################

function home_page() {
global $s,$m;
//$_SESSION[log_country]='GB';

/*
if ($s[visit]) { $a[show_simple] = 'none'; $a[show_complete] = $a[show_complete_header] = $a[show_complete_footer] = 'block'; }
elseif ($_SESSION[log_country])
{ $q = dq("select * from $s[pr]cats where skip_country = '$_SESSION[log_country]'",1);
  $category_vars = mysqli_fetch_assoc($q);
  if ($category_vars[n]) header("Location: ".category_url($category_vars[use_for],$category_vars[n],$category_vars[alias_of],$category_vars[name],1,$category_vars[pagename],$category_vars[rewrite_url],'',''));

  $a[show_complete] = $a[show_complete_header] = $a[show_complete_footer] = 'none'; $a[show_simple] = 'block';
}
*/
if (!$_SESSION[log_country]) $new_visitor = 1;
log_country();
if ($_SESSION[log_country])
{ $q = dq("select flag,name from $s[pr]countries where code = '$_SESSION[log_country]'",1);
  $x = mysqli_fetch_assoc($q);
  if ($x[name]) { $a[this_country] = $x[name]; $a[this_flag] = "$s[site_url]/images/flags/large/$x[flag]"; }
  if ($new_visitor)
  { $q = dq("select * from $s[pr]areas where country_skip = '$_SESSION[log_country]'",1);
    $area_vars = mysqli_fetch_assoc($q);
    if ($area_vars[n])
    { $url = "$s[site_url]/$s[ARfold_l_cat]-0-$area_vars[n]/$area_vars[rewrite_url].html";
      if ($s[A_option]!='rewrite') $url = str_replace("$s[site_url]/$s[ARfold_l_cat]-","$s[site_url]/index.php?vars=/$s[ARfold_l_cat]-",$url);
      header ("Location: $url"); exit;
    }
  }
}
if (!$a[this_country]) { $a[hide_country_begin] = '<!--'; $a[hide_country_end] = '-->'; }


$q = dq("select * from $s[pr]cats_home where list_type = 'ads' order by rank,title",1);
while ($c=mysqli_fetch_assoc($q))
{ if (!$c[parent]) $first_level[] = $c[n];
  $cats[$c[parent]][$c[n]] = $c;
}


$q = dq("select n,image2,cat_group from $s[pr]cats where level = 1",1);
while ($x=mysqli_fetch_assoc($q)) { $cat_groups[$x[n]] = $x[cat_group]; if ($x[image2]) $icons[$x[n]] = $x[image2]; }

if ($s[record_numbers]) $items = get_item_numbers_cats($s[this_area],$first_level,$s[this_offer_wanted]);
else { $a[hide_annotations_begin] = '<!--'; $a[hide_annotations_end] = '-->'; }
foreach ($cats[0] as $k=>$c1)
{ $c1[url] = category_url('ad',$c1[n],$c1[alias_of],1,$c1[rewrite_url]);
  if ($s[this_area]) $c1[url] = str_replace('area_n',$s[this_area],$c1[url]);
  if ($c1[alias_of]) { $c1[title] = $s[alias_pref].$c1[title].$s[alias_after]; $c1[n] = $c1[alias_of]; }
  if ($s[record_numbers]) $c1[items] = '('.$items[$c1[n]][items].')';
  if ($items[$c1[n]][item_created]) $c1[item_created] = datum($items[$c1[n]][item_created],0); else $c1[item_created] = $m[na];
  if ($items[$c1[n]][item_edited]) $c1[item_edited] = datum($items[$c1[n]][item_edited],0); else $c1[item_edited] = $m[na];
  $c1[folder_icon] = folder_icon($items[$c1[n]][item_created],$icons[$c1[n]]);
  foreach ($cats[$c1[n]] as $k1=>$c2)
  { $url = category_url('ad',$c2[n],$c2[alias_of],1,$c2[rewrite_url]);
    if ($s[this_area]) $url = str_replace('area_n',$s[this_area],$url);
    $subcats[] = '<a class="link10" href="'.$url.'">'.$c2[title].'</a>';
//    $subcats[] = '<a class="link10" href="'.$url.'">'.$c2[title].' ('.$items1[$c2[n]][items].')</a>';
  }
  $c1[subcats] = implode($s[ind_sep_subc],$subcats);
  $c1[width] = floor(100/$s[index_column_cats]);
  $categories[] = parse_part('index_category.txt',$c1);
  //if ($cat_groups[$c1[n]]) $categories_columns[$cat_groups[$c1[n]]][] = parse_part('index_category.txt',$c1);
  unset($subcats);
  $pocet++;
}
$a[index_categories] = implode('',$categories);
//foreach ($categories_columns as $k => $v) $a["index_categories"] .= '<div style="width:33%;float:left;">'.implode('',$v).'</div>';
/*
index_category.txt:
<div style="width:100%;padding:10px;text-align:left;line-height:20px;">
<img src="#%folder_icon%#">&nbsp;<a href="#%url%#" style="font-size:18px;line-height:27px;">#%title%# #%items%#</a><br>
#%subcats%# 
</div>
*/

unset($categories,$pocet);
$q = dq("select * from $s[pr]cats_home where list_type != 'ads' order by rank,title",1);
while ($c=mysqli_fetch_assoc($q)) { $cats_list[] = $c[n]; $groups[$c[list_type]][] = $c; }

if ($s[record_numbers]) $items = get_item_numbers_cats($s[this_area],$cats_list,$s[this_offer_wanted]);
for ($group=1;$group<=10;$group++) 
{ if (!$groups[$group]) continue;
  unset($categories,$pocet);
  foreach ($groups[$group] as $k=>$c1)
  { $c1[url] = category_url('ad',$c1[n],$c1[alias_of],1,$c1[rewrite_url]);
    if ($c1[alias_of]) { $c1[title] = $s[alias_pref].$c1[title].$s[alias_after]; $c1[n] = $c1[alias_of]; }
    if ($s[record_numbers]) $c1[items] = '('.$items[$c1[n]][items].')';
    if ($items[$c1[n]][item_created]) $c1[item_created] = datum($items[$c1[n]][item_created],0); else $c1[item_created] = $m[na];
    if ($items[$c1[n]][item_edited]) $c1[item_edited] = datum($items[$c1[n]][item_edited],0); else $c1[item_edited] = $m[na];
    $c1[folder_icon] = folder_icon($items[$c1[n]][item_created],$icons[$c1[n]]);
    $c1[width] = floor(100/$s[index_column_cats]);
    $categories[] = parse_part('index_category_in_group.txt',$c1);
    $pocet++;
  }
  $a["categories_group_$group"] = implode('',$categories);
}

$a[hide_categories_begin] = '<!--'; $a[hide_categories_end] = '-->'; 

$a[first_cats_select] = select_list_first_cats('_common',1);

get_messages('index.php'); $a = array_merge((array)$m,(array)$a);
unset($a[submit_ad]);
$s[show_left_offer_wanted] = 1;
if ($s[this_area]) { $s[offer_wanted_base] = category_url('ad',0); $s[offer_wanted_base] = add_this_area($s[offer_wanted_base]); }
else $s[offer_wanted_base] = "$s[site_url]/extra_commands.html";

$a[home_featured_ads] = home_featured_ads();
$a[home_new_ads] = home_new_ads();
for ($x=1;$x<=4;$x++) $a["icon_folder_t$x"] = $s["icon_folder_t$x"];

$a[this_url] = "$s[site_url]/";
$a[title] = $s[site_name];
$a[share_it] = parse_part('share_it.txt',$a);

page_from_template('index.html',$a);
}


#############################################################################

function home_featured_ads() {
global $s;
$where = get_where_fixed_part(0,0,0,$s[this_area],$s[cas],$s[this_offer_wanted]);
$q = dq("select *,MD5(RAND()) AS m from $s[pr]ads where $where and x_home_page_by > '$s[cas]' order by m limit $s[per_page_index]",1);
while ($x = mysqli_fetch_assoc($q)) { $item[] = $x; $numbers[] = $x[n]; }
$ads = get_complete_ads_simple($item,$numbers,'ad_simple.txt');
return $s[ad_slideshow];
}

#############################################################################

function home_new_ads() {
global $s;
$where = get_where_fixed_part(0,0,0,$s[this_area],$s[cas],$s[this_offer_wanted]);
$q = dq("select *,MD5(RAND()) AS m from $s[pr]ads where $where order by created desc limit $s[per_page_index]",1);
while ($x = mysqli_fetch_assoc($q)) { $item[] = $x; $numbers[] = $x[n]; }
$ads = get_complete_ads_simple($item,$numbers,'ad_simple.txt');
return $s[ad_slideshow];
}

#############################################################################
#############################################################################
#############################################################################

function category_page($n) {
global $s,$m;

//$s[adult_cats] = array(8,2,3,4,5);

if (strstr($s[this_area_url],'http://')) { header("Location: $s[this_area_url]"); exit; }

if ($n)
{ $n = replace_once_text($n);
  $a = get_category_variables($n);
  if ($a[alias_of]) $a = get_category_variables($a[alias_of]);
  if (!$a[title]) problem ($m[no_exists]);
  if ($a[image1]) $a[image] = '<img border="0" src="'.$a[image1].'"><br><br>'; else { $a[image] = ''; $a[hide_image_begin] = '<!--'; $a[hide_image_end] = '-->'; }
  $a[similar] = get_more_categories('similar','ad',$a); if (!$a[similar]) { $a[hide_similar_begin] = '<!--'; $a[hide_similar_end] = '-->'; }
  if ($a[level]==1) $bigboss_vars = $a; else $bigboss_vars = get_category_variables($a[bigboss]);
  $a["cat_selected_$bigboss_vars[n]"] = ' selected';

  /*if ($bigboss_vars[announcements])
  { $b[calendar1] = date_select($t,'calendar_from');
    $b[calendar2] = date_select($t,'calendar_to');
    $a[calendars] = parse_part('category_calendars.txt',$b);
  }*/
}
else { $a[title] = $m[All_Categories]; $a[hide_similar_begin] = '<!--'; $a[hide_similar_end] = '-->'; }
$a["area_selected_".$s[this_area_vars][bigboss]] = ' selected';
/*

echo getenv('HTTP_REFERER');
foreach ($_GET as $k=>$v) echo "$k - $v<br>";

$a[category_link] = category_url('ad',$a[n],$a[alias_of],1,$a[rewrite_url]);
if ((in_array($n,$s[adult_cats])) AND (!$_SESSION[adult_ok]))
{ if (getenv('HTTP_REFERER')==$a[category_link]) { $_SESSION[adult_ok] = 1; echo hhhhh; }
  else { echo '<a href="http://localhost/ZKUSEBNI/GoldClassifieds/mojecats-8-0-0/area_rewrite/11111111111111111.html?neco=hhhhh">ggg</a>'; page_from_template('adult_category.html',$a); }
}*/

if (!$a[tmpl_cat]) $a[tmpl_cat] = 'category.html';
if (!$a[tmpl_one]) $a[tmpl_one] = 'ad_a.txt';
if (!file_exists("$s[phppath]/styles/_common/templates/$a[tmpl_cat]")) $a[tmpl_cat] = 'category.html';;
if (!file_exists("$s[phppath]/styles/_common/templates/$a[tmpl_one]")) $a[tmpl_one] = 'ad_a.txt';;
$sortby = find_order_by_ads($s[this_sort],$s[this_direction]);
$_SESSION[this_sort] = $s[this_sort];
$_SESSION[this_direction] = $s[this_direction];
$_SESSION[this_page] = $s[this_page];

list($a[category_title],$a[category_arrow]) = get_arrow_categories($a[n]);
if (!$a[category_arrow]) { $a[hide_category_info_begin] = '<!--'; $a[hide_category_info_end] = '-->'; }
if (!$a[n]) { $a[hide_for_no_user_begin1] = '<!--'; $a[hide_for_no_user_end1] = '-->'; }
$s[this_cat_n] = $a[n];

$where = 'where '.get_where_fixed_part(0,$a[n],0,$s[this_area],$s[cas],$s[this_offer_wanted]);




$q = dq("select count(*) from $s[pr]ads $where",1);
$total = mysqli_fetch_row($q); $a[total] = $total[0];
check_recount($a[n],$s[this_area],$s[this_offer_wanted],$a[total]);
$from = get_page_from($s[this_page],$s[per_page]);
//echo "select * from $s[pr]ads $where order by $sortby limit $from,$s[per_page]";
$q = dq("select * from $s[pr]ads $where order by $sortby limit $from,$s[per_page]",1);
while ($x = mysqli_fetch_assoc($q)) { $item[] = $x; $numbers[] = $x[n]; }

if ($numbers)
{ foreach ($item as $k => $d) $item[$k][category] = $a[n];
  $a[ads] = get_complete_ads($item,$numbers,$a[tmpl_one]);
  if ($a[alias_of]) $category_n = $a[alias_of]; else $category_n = $a[n];
  $a[pages] = category_pages_list('ad',$category_n,$a[rewrite_url],$s[this_area],$a[total],$s[this_page]);
}
else $a[links] = $a[pages] = $a[pages1] = '';
$a[pages1] = $s[pages_list_numbers];

if ($s[GC_u_email])
{ $bookmarks = get_favorites_status('c',$a[n]);
  $a[add_delete_favorites] = get_favorite_line('c',$a[n],$bookmarks[$a[n]]);
  $notes = get_private_notes_for_items('c',$a[n]);
  if ($notes[$a[n]]) { $a[notes] = $notes[$a[n]]; $a[notes_style_display] = 'block'; } else $a[notes_style_display] = 'none';
  $s[current_notes] = $a[notes]; $a[notes_edit_box] = notes_edit_box('c',$a[n],'');
}

$a[search_form] = get_category_search_form($a[bigboss],$a[n],$s[this_area],'');
$a[category_url] = category_url('ad',$a[n],$a[alias_of],1,$a[rewrite_url]);

if (($s[this_area_vars][level]==1) AND ($s[this_area_vars][latitude]!=0.0000000) AND ($s[this_area_vars][longitude]!=0.0000000))
{ $a[area_n] = $s[this_area];
  $a[div_display_map] = 'block'; 
}
else $a[div_display_map] = 'none'; 

if ($s[this_offer_wanted]) setcookie(GC_offer_wanted,$s[this_offer_wanted],$s[cas]+3600,'/');
if ($s[this_area]) setcookie(GC_area,$s[this_area],$s[cas]+3600,'/');
setcookie(GC_category,$a[n],$s[cas]+3600,'/');

$s[this_cat_n] = $a[n];
$s[category_title] = $a[title];
if (!$a[description]) { $a[hide_description_begin] = '<!--'; $a[hide_description_end] = '-->'; }
if ($a[offer_wanted]) { $s[show_left_offer_wanted] = 1; $s[offer_wanted_base] = category_url('ad',$a[n],$a[alias_of],1,$a[rewrite_url]); $s[offer_wanted_base] = add_this_area($s[offer_wanted_base]); }

$s[show_rss_category] = 1;
if ($a[submit_here]) $a[submit_ad_c] = "&c=$a[n]";

$a[this_url] = $a[category_url];
$a[meta_description] = $a[m_desc];
$a[meta_keywords] = $a[m_keyword];

if ($a[path_text]) $meta_title[] = stripslashes(str_replace('_',' ',str_replace('<%','',str_replace('%>',' >> ',$a[path_text])))); else $meta_title[] = $s[site_name];
if ($s[this_area_title]) $meta_title[] = $s[this_area_title];
$a[meta_title] = implode(', ',$meta_title);
$a[share_it] = parse_part('share_it.txt',$a);

if (($a[n]) AND (check_admin_rights('categories'))) $a[edit_link] = '<a target="_blank" href="'.$s[site_url].'/administration/categories.php?action=category_edit&n='.$a[n].'">Edit this category</a> (this link is available for admin only)';

page_from_template($a[tmpl_cat],$a);
}

#############################################################################

function get_more_categories($action,$what,$category,$sort,$direction) {
global $s,$m;
//if ($s[A_option]=='static') $parse_part = 'A_parse_part'; else $parse_part = 'parse_part'; 
$columns = $s[subc_column];
$columns = 2;

if ($action=='subcategories')
{ $q = dq("select * from $s[pr]cats where use_for = '$what' AND visible = '1' AND parent = '$category' order by name",1);
  $a[title] = $m[subcats];
}
elseif ($action=='similar') 
{ $numbers = explode(' ',str_replace('_','',$category[similar]));
  if ($numbers)
  { $x = my_implode('n','OR',$numbers);
    $q = dq("select * from $s[pr]cats where visible = '1' AND $x order by path_text",1);
  }
  $a[title] = $m[similar_cats];
}
if (!mysqli_num_rows($q)) return false;

while ($c = mysqli_fetch_assoc($q))
{ $c[url] = category_url('ad',$c[n],$c[alias_of],1,$c[rewrite_url]);
  $c[folder_icon] = folder_icon($c[item_created],$c[image2]);
  if ($c[alias_of]) $c[name] = $s[alias_pref].$c[name].$s[alias_after];
  //$c[items] = "$c[items] ".$m[$s[items_types_words][$c[use_for]].'1'];
  $c[width] = floor(100/$columns);
  //$q1 = dq("select * from $s[pr]cats where parent = '$c[n]' and visible = '1'",1);
  //while ($x=mysqli_fetch_assoc($q1)) $subcats_array[] = '<a class="link10" href="'.category_url($x[use_for],$x[n],$x[alias_of],$x[name],1,$x[pagename],$x[rewrite_url],$sort,$direction).'">'.$x[name].'</a>';
  //$c[subcategories] = implode(', ',$subcats_array); unset($subcats_array);
  $subcategories[] = parse_part('categories_category.txt',$c);
  $pocet++;
}
$categories = implode('',$subcategories);
if (!$pocet) return false;
return $categories;
}

#############################################################################

function get_arrow_categories($n) {
global $s,$m;
if (!$n) return false;
$category = get_category_variables($n);
$y = explode(' ',trim(str_replace('_',' ',$category[path_n])));
foreach ($y as $k=>$v)
{ $x = get_category_variables($v);
  if ($x[n]!=$n)
  { $url = add_this_area(category_url('ad',$x[n],0,1,$x[rewrite_url]));
    $categories .= '<a href="'.$url.'">'.$x[title].'</a> >> ';
  }
  else $category_title = $x[title];
}
if ($s[this_area]) $categories = '<a href="'.add_this_area(category_url('ad',0)).'">'.$m[all_categories].'</a> >> '.$categories;
else $categories = '<a href="'.$s[site_url].'/">'.$m[home].'</a> >> '.$categories;
return array($category_title,$categories);
}

#############################################################################

function get_page_from($page,$per_page) {
global $s;
if (!$page) return 0;
else return $per_page * ($page-1);
}

##################################################################################

function bottom_redirect() {
global $s;
if ((is_numeric($_GET[c])) AND ($_GET[c]))
{ $url = category_url('ad',$_GET[c],0,1,'');
  if ((is_numeric($_GET[a])) AND ($_GET[a]))
  { $area_vars = get_area_variables($_GET[a]);
    $url = str_replace('-page_n','',str_replace('-extra_commands','',str_replace('area_n',$area_vars[n],str_replace('area_rewrite',$area_vars[rewrite_url],$url))));
  }
  else $url = str_replace('-page_n/','',str_replace('-extra_commands','',str_replace('area_n',0,str_replace('area_rewrite',$area_vars[rewrite_url],$url))));
  
}
elseif ((is_numeric($_GET[a])) AND ($_GET[a]))
{ $area_vars = get_area_variables($_GET[a]);
  $url = "$s[site_url]/$s[ARfold_l_cat]-0-$area_vars[n]/$area_vars[rewrite_url].html";
}
if (!$url) $url = "$s[site_url]/";
if ($s[A_option]!='rewrite') $url = str_replace("$s[site_url]/$s[ARfold_l_cat]-","$s[site_url]/index.php?vars=/$s[ARfold_l_cat]-",$url);
header("HTTP/1.1 301 Moved Permanently"); header ("Location: $url"); exit;
}

##################################################################################
##################################################################################
##################################################################################

?>