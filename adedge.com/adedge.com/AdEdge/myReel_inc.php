<script src="../SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css">
<div id="footerContainer">
    <div id="CollapsiblePanel1" class="CollapsiblePanel">
        <div class="CollapsiblePanelTab" tabindex="0">
            <div class="MyReelHeader">
                <span class="MyReelHeaderTitle">My Reel</span>
                <a href="#" class="tip">What's this?<span><p>Use "My Reel" to build a list of Ads that you would like to keep track of. This list of Ads appears down here and can be accessed anywhere on the site.</p><p>Add a video by clicking on the "Add this video to My Reel" link while viewing a video.</p></span></a>
            </div>
        </div>
        <div class="CollapsiblePanelContent">        
            <div id="MyReelContainer">
            <?php do { ?>
                <div class="MyReelItem">
                    <div class="MyReelItemThumb"><a href="/AdEdge/view.php?v=<?php echo $row_RSGetMyReelList['nid']; ?>"><img src="/<?php echo $row_RSGetMyReelList['filepath']; ?>" width=115/></a></div>
                    <div class="MyReelItemTitle"><a href="/AdEdge/view.php?v=<?php echo $row_RSGetMyReelList['nid']; ?>"><?php echo $row_RSGetMyReelList['title']; ?></a></div>
                </div>
            <?php } while ($row_RSGetMyReelList = mysql_fetch_assoc($RSGetMyReelList)); ?>            
            </div>  
        </div>
    </div>
</div>
<script type="text/javascript">
<!--
var CollapsiblePanel1 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel1");
//-->
</script>
