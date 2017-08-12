<div id="footerContainer">
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