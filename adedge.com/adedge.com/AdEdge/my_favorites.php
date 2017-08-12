<?php include('/var/www/vhosts/95/243968/webspace/httpdocs/adedge.com/Connections/AdEdgeMySql.php'); ?>
<?php
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AdEdge - Viewing your favorites.</title>
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
                <div class="getQuoteText"><a href="/AdEdge/get_quote_favorites.php"><img src="/files/images/get_quotes_favorites.gif" />Get a quote on all the ads in your favorites now</a></div>
                <div class="box2">        
                    <div class="box2TopWhite">
                        <h1>These are all of the videos you have added to your favorites.</h1>
                    </div>
					<?php if (!empty($row_RSGetMyReelList)) { ?>
                    <div class="box2Contents">   
					  <?php do { ?>
                        <div class="ListStyleBox">
                          <div class="vidDetailsListing">
                              <div class="vidresultThumb"><img src="/<?php echo $row_RSGetMyReelList['filepath']; ?>" width=180/></div>
                              <div class="vidresultTitle"><a href="/AdEdge/view.php?v=<?php echo $row_RSGetMyReelList['nid']; ?>"><?php echo $row_RSGetMyReelList['title']; ?></a></div>
                          </div>
                          <div class="vidresultLinkBox">
                            <div class="linktoDetail">
                              <ul>
                                <li><a href="/AdEdge/view.php?v=<?php echo $row_RSGetMyReelList['nid']; ?>"><img src="/files/images/get_more_info.gif" />Play this video</a></li>
                                  <li>
<?php
if (!empty($MReelContents)) {

	if (in_array($row_RSGetMyReelList['nid'], $MReelContents)) {
		$AddToMyReelLabel = $AddToMyReelLabelAdded;
		$AddToMyReelLabelLink = $_SERVER['SCRIPT_NAME']."?del=".$row_RSGetMyReelList['nid'];
	} else {
		$AddToMyReelLabel = $AddToMyReelLabelNotAdded;
		$AddToMyReelLabelLink = $_SERVER['SCRIPT_NAME']."?add=".$row_RSGetMyReelList['nid'];
		}

} else {
		$AddToMyReelLabel = $AddToMyReelLabelNotAdded;
		$AddToMyReelLabelLink = $_SERVER['SCRIPT_NAME']."?add=".$row_RSGetMyReelList['nid'];
}	
?>

                                  
                                  
                                <a href="<?php echo $AddToMyReelLabelLink; ?>"><?php echo $AddToMyReelLabel; ?></li>
                                  <li><a href="/AdEdge/get_quote.php?v=<?php echo $row_RSGetMyReelList['nid']; ?>&title=<?php echo urlencode($row_RSGetMyReelList['title']); ?>"><img src="/files/images/get_quote.gif" />Get a quote on this Ad now</a></li>
                              </ul>
                            </div>
                          </div>
                        </div>
                        <?php } while ($row_RSGetMyReelList = mysql_fetch_assoc($RSGetMyReelList)); ?>
                    </div>
					<?php } else { ?>
                    <div class="box2Contents">
                    <p>You have not added any videos to your favorites yet. <a href="/AdEdge/list_category.php">Start browsing our ads</a> to begin building a favorites list.</p>
					</div>
                    <?php } ?>
                    <div class="box2Bottom">&nbsp;</div>    
                </div>
            </div>
        </div>    
    </div>
<!-- Footer File Include -->
<?php include('/var/www/vhosts/95/243968/webspace/httpdocs/adedge.com/AdEdge/footer__noReel_inc.php'); ?>
<!-- /Footer File Include -->
</div>
</body>
</html>
<?php
mysql_free_result($RSTestNavList);
mysql_free_result($RSTagList);
mysql_free_result($RSCategoryList);

?>