<script src="../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css">
<div id="footerContainer">
    <div id="CollapsiblePanel1" class="CollapsiblePanel">
        <div class="CollapsiblePanelTab" tabindex="0">
            <div class="MyReelHeader">
                <span class="MyReelHeaderTitle"><a href="/AdEdge/my_favorites.php">My Favorites</a> (<?php echo $totalRows_RSGetMyReelList; ?>)</span>
                <a href="#" class="tip">What's this?<span><p>Use "My Favorites" to build a list of Ads that you would like to keep track of. This list of Ads appears down here and can be accessed anywhere on the site.</p><p>Add a video by clicking on the "Add this video to My Favorites" link while viewing a video.</p></span></a>
            </div>
      </div>
        <div class="CollapsiblePanelContent">        
            <div id="MyReelContainer">
            <?php 
			if (!empty($row_RSGetMyReelList)) {
				do { ?>
					<div class="MyReelItem">
						<div class="MyReelItemThumb"><a href="/AdEdge/view.php?v=<?php echo $row_RSGetMyReelList['nid']; ?>"><img src="/<?php echo $row_RSGetMyReelList['filepath']; ?>" width=115/></a></div>
						<div class="MyReelItemTitle"><a href="/AdEdge/view.php?v=<?php echo $row_RSGetMyReelList['nid']; ?>"><?php echo $row_RSGetMyReelList['title']; ?></a></div>
					</div>
				<?php } while ($row_RSGetMyReelList = mysql_fetch_assoc($RSGetMyReelList)); 
					} else {?>
					<div class="MyReelItem">
                    	<div class="emptyReelMessage">You have not added any videos to your favorites yet. <a href="/AdEdge/list_category.php">Start browsing our ads</a> to begin building a favorites list.</div>
					</div>
                  <?php } ?>              
            </div>  
        </div>
    </div>
	<div class="footerLinks">
    	<ul>
        	<li><a href="/AdEdge/index.php">Home</a></li>
          <li><a href="/AdEdge/faqs.php">FAQs</a></li>
          <li><a href="/AdEdge/about_adedge.php">About Us</a></li>
          <li><a href="/AdEdge/contact.php">Contact Us</a></li>
          <li><a href="/AdEdge/how_it_works.php">How It Works</a></li>
      </ul>
</div>
<?php
if (!empty($MReelContents)) {
	mysql_free_result($RSGetMyReelList);
}
?>
<script type="text/javascript">
<!--
var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1");
//-->
</script>
