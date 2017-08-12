<?php include('/var/www/vhosts/95/243968/webspace/httpdocs/adedge.com/Connections/AdEdgeMySql.php'); ?>
<?php
$colname_CategoryData = "-1";
if (isset($_GET['category'])){
	$colname_CategoryData = $_GET['category'];
}
if (isset($_GET['subcat'])) {
  	$colname_CategoryData = $_GET['subcat'];
}

$colname_CategoryDataSUB = $_GET['category']; 

mysql_select_db($database_AdEdgeMySql, $AdEdgeMySql);
$query_CategoryData = sprintf("SELECT tid, name FROM term_data WHERE tid = %s", GetSQLValueString($colname_CategoryData, "int"));
$CategoryData = mysql_query($query_CategoryData, $AdEdgeMySql) or die(mysql_error());
$row_CategoryData = mysql_fetch_assoc($CategoryData);
$totalRows_CategoryData = mysql_num_rows($CategoryData);


if (isset($_GET['category'])) {
mysql_select_db($database_AdEdgeMySql, $AdEdgeMySql);
$query_SubCategoryData = sprintf("SELECT term_data.tid, term_data.name FROM term_data LEFT JOIN term_hierarchy ON term_data.tid = term_hierarchy.tid WHERE term_hierarchy.parent = %s order by term_data.name", GetSQLValueString($colname_CategoryDataSUB, "int"));
$SubCategoryData = mysql_query($query_SubCategoryData, $AdEdgeMySql) or die(mysql_error());
$row_SubCategoryData = mysql_fetch_assoc($SubCategoryData);
$totalRows_SubCategoryData = mysql_num_rows($SubCategoryData);
}

$colname_RSGetNodeList = "-1";
if (isset($_GET['subcat'])) {
  $colname_RSGetNodeList = $_GET['subcat'];
} elseif (isset($_GET['category'])){
  $colname_RSGetNodeList = $_GET['category'];
}
$imgtype_RSGetNodeList = "thumbnail";
mysql_select_db($database_AdEdgeMySql, $AdEdgeMySql);
$query_RSGetNodeList = sprintf("SELECT term_node.tid, files.filename, files.filepath, node.title, node.nid, content_type_flashnode.field_length_value, content_type_flashnode.field_long_description_value, content_type_flashnode.field_short_description_value FROM term_node LEFT JOIN node ON term_node.nid = node.nid LEFT JOIN content_type_flashnode ON term_node.nid = content_type_flashnode.nid LEFT JOIN image_attach  ON term_node.nid = image_attach.nid LEFT JOIN files ON files.nid = image_attach.iid WHERE term_node.tid = %s AND files.filename = %s AND node.status = 1 ORDER BY node.created DESC", GetSQLValueString($colname_RSGetNodeList, "int"),GetSQLValueString($imgtype_RSGetNodeList, "text"));
$RSGetNodeList = mysql_query($query_RSGetNodeList, $AdEdgeMySql) or die(mysql_error());
$row_RSGetNodeList = mysql_fetch_assoc($RSGetNodeList);
$totalRows_RSGetNodeList = mysql_num_rows($RSGetNodeList);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AdEdge - Viewing Television Advertising Category <?php echo $row_CategoryData['name']; ?></title>
<link href="/main_styles.css" rel="stylesheet" type="text/css" />

</head>
<body>

<div id="mainBody">
<!-- Header File Include -->
<?php include('/var/www/vhosts/95/243968/webspace/httpdocs/adedge.com/AdEdge/header_inc.php'); ?>
<!-- /Header File Include -->
    <div id="mainContentContainer">
    	<div id="column1">
<!-- 1 Column left nav File Include -->
<?php include('/var/www/vhosts/95/243968/webspace/httpdocs/adedge.com/AdEdge/leNav_1_Colun_inc.php'); ?>
<!-- /1 Column left nav File Include -->
        </div>
        <div id="column2">
            <div id="categoryListing">              
			<?php if (!empty($row_RSGetNodeList)) { ?>
                <div class="box2">        
                    <div class="box2TopBlue">
                        <h1><?php echo $row_CategoryData['name']; ?></h1>
                    </div>
                    <div class="box2Contents">   
					  <?php do { ?>
                        <div class="ListStyleBox">
                          <div class="vidDetailsListing">
                              <div class="vidresultThumb"><img src="/<?php echo $row_RSGetNodeList['filepath']; ?>" width=180/></div>
                              <div class="vidresultTitle"><a href="/AdEdge/view.php?v=<?php echo $row_RSGetNodeList['nid']; ?>&category=<?php echo $_GET['category'];?><?php if (isset($_GET['subcat'])) {?>&subcat=<?php echo $_GET['subcat'];?><?php } ?>"><?php echo $row_RSGetNodeList['title']; ?></a></div>
                              <div class="vidresultLength">Length: <?php echo $row_RSGetNodeList['field_length_value']; ?></div>
                              <div class="vidresultDescription"><?php echo $row_RSGetNodeList['field_short_description_value']; ?></div>
                          </div>
                          <div class="vidresultLinkBox">
                            <div class="linktoDetail">
                              <ul>
                                <li><a href="/AdEdge/view.php?v=<?php echo $row_RSGetNodeList['nid']; ?>&category=<?php echo $_GET['category'];?><?php if (isset($_GET['subcat'])) {?>&subcat=<?php echo $_GET['subcat'];?><?php } ?>"><img src="/files/images/get_more_info.gif" />Play this video</a></li>
                                  <li>
<?php
if (!empty($MReelContents)) {

	if (in_array($row_RSGetNodeList['nid'], $MReelContents)) {
		$AddToMyReelLabel = $AddToMyReelLabelAdded;
		$AddToMyReelLabelLink = $_SERVER['SCRIPT_NAME']."?category=".$_GET['category']."&del=".$row_RSGetNodeList['nid'];
	} else {
		$AddToMyReelLabel = $AddToMyReelLabelNotAdded;
		$AddToMyReelLabelLink = $_SERVER['SCRIPT_NAME']."?category=".$_GET['category']."&add=".$row_RSGetNodeList['nid'];
		}

} else {
		$AddToMyReelLabel = $AddToMyReelLabelNotAdded;
		$AddToMyReelLabelLink = $_SERVER['SCRIPT_NAME']."?category=".$_GET['category']."&add=".$row_RSGetNodeList['nid'];
}	
?>

                                  
                                  
                                <a href="<?php echo $AddToMyReelLabelLink; ?>"><?php echo $AddToMyReelLabel; ?></li>
                                  <li><a href="/AdEdge/get_quote.php?v=<?php echo $row_RSGetNodeList['nid']; ?>&title=<?php echo urlencode($row_RSGetNodeList['title']); ?>"><img src="/files/images/get_quote.gif" />Get a quote on this Ad now</a></li>
                              </ul>
                            </div>
                          </div>
                        </div>
                        <?php } while ($row_RSGetNodeList = mysql_fetch_assoc($RSGetNodeList)); ?>
                    </div>
					<?php } else if (empty($row_RSGetNodeList) && !isset($_GET['category'])){ ?>

                	<div class="box2">        
                    <div class="box2TopWhite">
                        <h1>NOW PLAYING: AdEdge Highlight Reel</h1>
                    </div>
                    <div class="box2Contents">

                <div class="vidresultVideo">
					<script type="text/javascript" src="/vplay/swfobject.js"></script>
                    <div id="player">Loading flash media player...</div>
              <script type="text/javascript">
                    var so = new SWFObject('/vplay/player.swf','mpl','470','350','9');
                    so.addParam('allowscriptaccess','always');
                    so.addParam('allowfullscreen','true');
                    so.addParam('flashvars','&file=/files/flash/opening+program.flv&skin=/snel/snel.swf&bufferlength=10&autostart=true&fullscreen=true');
                    so.write('player');
                    </script>
                </div>


					
					</div> <div class="box2Bottom">&nbsp;</div>    </div>



                	<div class="box2">        
                    <div class="box2TopWhite">
                    </div>
                    <div class="box2Contents">
<p>For the first time ever, you get to see exactly what you'll get for your money, before you buy. The process is pretty simple.</p>

<h1>Here's how to get started:</h1>
<ol>
	<li>Browse our expansive selection of ads by using the navigation on the left</li>
	<li>Preview the ads you like by clikcing on the <img src="/files/images/get_more_info.gif"> button.</li>
    <li>Click <img src="/files/images/get_quote.gif" /> to get a quote for an individual ad or click <b>add to favorites</b> and build a list of ads to get quoted.</li>
</ol>
    
					</div>
                    <?php } else { ?>
                	<div class="box2">        
                    <div class="box2TopBlue">
                        <h1>No results for that search</h1>
                    </div>
                    <div class="box2Contents">
                    <p>Sorry, your search returned no results. Please use the navigation to the left to browse other videos.</p>
					</div>					
					<?php } ?>
                    <div class="box2Bottom">&nbsp;</div>    
                </div>
            </div>
        </div>    
    </div>

<!-- Footer File Include -->
<?php include('/var/www/vhosts/95/243968/webspace/httpdocs/adedge.com/AdEdge/footer_inc.php'); ?>
<!-- /Footer File Include -->
</div>
</body>
</html>
<?php
mysql_free_result($CategoryData);
mysql_free_result($RSGetNodeList);
mysql_free_result($RSSubNavList);
mysql_free_result($RSTestNavList);
mysql_free_result($RSTagList);
mysql_free_result($RSCategoryList);
mysql_free_result($SubCategoryData);
?>