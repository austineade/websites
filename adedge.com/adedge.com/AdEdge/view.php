<?php include('/var/www/vhosts/95/243968/webspace/httpdocs/adedge.com/Connections/AdEdgeMySql.php'); ?>
<?php



$colname_CategoryData = "-1";
if (isset($_GET['category'])){
	$colname_CategoryData = $_GET['category'];
}
if (isset($_GET['subcat'])) {
  	$colname_CategoryData = $_GET['subcat'];
} elseif (isset($_GET['subcat'])) {
	if ($_GET['subcat'] == "") {
		$colname_CategoryData = $_GET['category'];
		}
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
if (isset($_GET['v'])) {
  $colname_RSGetNodeList = $_GET['v'];
}
mysql_select_db($database_AdEdgeMySql, $AdEdgeMySql);
$query_RSGetNodeList = sprintf("SELECT node.nid, files.filename, files.filepath, node.title, content_type_flashnode.field_length_value, content_type_flashnode.field_long_description_value FROM node LEFT JOIN content_type_flashnode ON node.nid = content_type_flashnode.nid LEFT JOIN files ON node.nid = files.nid WHERE node.nid = %s", GetSQLValueString($colname_RSGetNodeList, "int"));
$RSGetNodeList = mysql_query($query_RSGetNodeList, $AdEdgeMySql) or die(mysql_error());
$row_RSGetNodeList = mysql_fetch_assoc($RSGetNodeList);
$totalRows_RSGetNodeList = mysql_num_rows($RSGetNodeList);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-5" />
<title>AdEdge - Viewing Television Ad: <?php echo $row_RSGetNodeList['title']; ?></title>
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
      
            <div id="detailListing">
              <div class="detailStyleBox">
                <div class="vidresultVideo">
					<script type="text/javascript" src="/vplay/swfobject.js"></script>
                    <div id="player">Loading flash media player...</div>
                    <script type="text/javascript">
                    var so = new SWFObject('/vplay/player.swf','mpl','470','350','9');
                    so.addParam('allowscriptaccess','always');
                    so.addParam('allowfullscreen','true');
                    so.addParam('flashvars','&file=/files/<?php echo $row_RSGetNodeList['filepath']; ?>&skin=/snel/snel.swf&bufferlength=10&autostart=true&fullscreen=true');
                    so.write('player');
                    </script>
                </div>
                <div class="vidresultTitle"><h1><?php echo $row_RSGetNodeList['title']; ?></h1></div>
                <div class="vidresultLength">Length: <?php echo $row_RSGetNodeList['field_length_value']; ?></div>
                <div class="vidresultDescription"><?php echo $row_RSGetNodeList['field_long_description_value']; ?></div>
                <div class="vidresultLinkBox">
                  <div class="linktoDetail"></div>
                  <div class="addtoReel">
                    <p><a href="<?php echo $AddToMyReelLabelLink; ?>"><?php echo $AddToMyReelLabel; ?></a>
                    <p><a href="/AdEdge/get_quote.php?v=<?php echo $row_RSGetNodeList['nid']; ?>&title=<?php echo urlencode($row_RSGetNodeList['title']); ?>"><img src="/files/images/get_quote.gif" />Get a quote on this Ad now</a></p>
					<p></p>
					<p><br /><a href="/AdEdge/send_friend.php?v=<?php echo $row_RSGetNodeList['nid']; ?>&title=<?php echo urlencode($row_RSGetNodeList['title']); ?>">Send this ad to a friend</a></p>
				  </div>
                </div>
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
mysql_free_result($RSGetNodeList);
mysql_free_result($RSTagList);
mysql_free_result($RSCategoryList);
?>