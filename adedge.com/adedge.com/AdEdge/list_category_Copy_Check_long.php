<?php include('/vservers/c27464-h224072/htdocs/Connections/AdEdgeMySql.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_CategoryData = "-1";
if (isset($_GET['category'])) {
  $colname_CategoryData = $_GET['category'];
}
mysql_select_db($database_AdEdgeMySql, $AdEdgeMySql);
$query_CategoryData = sprintf("SELECT tid, name FROM term_data WHERE tid = %s", GetSQLValueString($colname_CategoryData, "int"));
$CategoryData = mysql_query($query_CategoryData, $AdEdgeMySql) or die(mysql_error());
$row_CategoryData = mysql_fetch_assoc($CategoryData);
$totalRows_CategoryData = mysql_num_rows($CategoryData);

$colname_RSGetNodeList = "";
if (isset($_GET['category'])) {
  $colname_RSGetNodeList = $_GET['category'];
}
$imgtype_RSGetNodeList = "thumbnail";
mysql_select_db($database_AdEdgeMySql, $AdEdgeMySql);
$query_RSGetNodeList = sprintf("SELECT node.title, node.nid, files.filename, files.filepath,  content_type_flashnode.field_length_value, content_type_flashnode.field_long_description_value, content_type_flashnode.field_short_description_value FROM node LEFT JOIN content_type_flashnode ON node.nid = content_type_flashnode.nid LEFT JOIN image_attach ON node.nid = image_attach.nid LEFT JOIN files ON files.nid = image_attach.iid WHERE files.filename = %s", GetSQLValueString($imgtype_RSGetNodeList, "text"));
$RSGetNodeList = mysql_query($query_RSGetNodeList, $AdEdgeMySql) or die(mysql_error());
$row_RSGetNodeList = mysql_fetch_assoc($RSGetNodeList);
$totalRows_RSGetNodeList = mysql_num_rows($RSGetNodeList);

mysql_select_db($database_AdEdgeMySql, $AdEdgeMySql);
$query_RSTestNavList = "SELECT tid, name FROM term_data ORDER BY name ASC";
$RSTestNavList = mysql_query($query_RSTestNavList, $AdEdgeMySql) or die(mysql_error());
$row_RSTestNavList = mysql_fetch_assoc($RSTestNavList);
$totalRows_RSTestNavList = mysql_num_rows($RSTestNavList);
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
    <div id="mainContentContainer">
        <div id="column2">
            <div id="categoryListing">
              <?php do { ?>
                <div class="ListStyleBox" <?php if ($row_RSGetNodeList['field_long_description_value'] != "") {?>style="display:none;"<?php } ?>>
                  <div class="vidresultThumb"><img src="/<?php echo $row_RSGetNodeList['filepath']; ?>" width=180/></div>
                  <div class="vidresultTitle"><a href="/AdEdge/view.php?v=<?php echo $row_RSGetNodeList['nid']; ?>"><?php echo $row_RSGetNodeList['title']; ?>							</a></div>
                  <div class="vidresultLength">Length: <?php echo $row_RSGetNodeList['field_length_value']; ?></div>
                  <div class="vidresultDescription">Short description:<br><?php echo $row_RSGetNodeList['field_short_description_value']; ?></div>
                  <div class="vidresultDescription">Long description:<br><?php echo $row_RSGetNodeList['field_long_description_value']; ?></div>
                  <div class="vidresultLinkBox">
                    <div class="linktoDetail"></div>
                      <div class="addtoReel"></div>
                  </div>
                </div>
                <?php } while ($row_RSGetNodeList = mysql_fetch_assoc($RSGetNodeList)); ?>
            </div>


        </div>    
    </div>
    <div id="footerContainer">
		<?php include('/vservers/c27464-h224072/htdocs/AdEdge/myReel_inc.php'); ?>
    </div>
</div>
</body>
</html>
<?php
mysql_free_result($CategoryData);
mysql_free_result($RSGetNodeList);
mysql_free_result($RSGetMyReelList);
?>
