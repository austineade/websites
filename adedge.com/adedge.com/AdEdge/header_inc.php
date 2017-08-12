<?php
// Set active state of global nav
$activeClass = ' class="selected"';
// Define global nav labels and links
$globalNavigation = array("HOME" => "/AdEdge/list_category.php","HOW IT WORKS" => "/AdEdge/how_it_works.php", "FAQ" => "/AdEdge/faqs.php", "ABOUT US" => "/AdEdge/about_adedge.php", "CONTACT" => "/AdEdge/contact.php", "MY SELECTED ADS" => "/AdEdge/my_favorites.php");
?>
<div id="header">
    <div class="HeaderNav">
        <ul class="basictab">
<?php foreach ($globalNavigation as $i => $value) { ?>	
        <li<?php if ($value == $_SERVER['SCRIPT_NAME']) { echo $activeClass; } ?>><a href="<?php echo $value; ?>"><?php echo $i; ?><?php if ($i == "My Favorites") { echo " (".$totalRows_RSGetMyReelList.")"; }?></a></li>
        <?php } ?>
        </ul>
    </div>
    <div class="HeaderSub"></div>
</div>