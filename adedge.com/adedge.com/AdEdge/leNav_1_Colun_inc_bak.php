        	<div id="mainNavCategories">
				<div class="box1">        
                 	<div class="box1TopGreen">
                    	<h1>Browse all ads</h1>
                    	<p>Use the links below to search for Ads that match your needs.</p>
                    
                    </div>
                    <div class="box1Contents">   
                        <h1>Browse by industry</h1>
                        <ul>
                            <?php do { ?>
                            <li><a href="list_category.php?category=<?php echo $row_RSTestNavList['tid']; ?>"><?php echo $row_RSTestNavList['name']; ?></a></li>
							<?php } while ($row_RSTestNavList = mysql_fetch_assoc($RSTestNavList)); ?>
                        </ul>
<?php if (!empty($row_SubCategoryData)) { ?>
                        <h1>Browse by sub-category</h1>
                        <ul>
                            <?php do { ?>
                            <li><a href="list_category.php?subcat=<?php echo $row_SubCategoryData['tid']; ?>&category=<?php echo $_GET['category']; ?>"><?php echo $row_SubCategoryData['name']; ?></a></li>
							<?php } while ($row_SubCategoryData = mysql_fetch_assoc($SubCategoryData)); ?>
                        </ul>
<?php } ?> 


						<h1>Browse by type</h1>
                    	<ul>
                             <?php do { ?>
                            <li><?php if ($_GET['category'] != $row_RSCategoryList['tid']) {?><a href="list_category.php?category=<?php echo $row_RSCategoryList['tid']; ?>"><?php } ?><?php echo $row_RSCategoryList['name']; ?><?php if ($_GET['category'] != $row_RSCategoryList['tid']) {?></a><?php }?></li>
							<?php } while ($row_RSCategoryList = mysql_fetch_assoc($RSCategoryList)); ?>
                        </ul>
                     <h1>Browse by tag</h1>
                             <p><?php do { ?>
                            <?php if ($_GET['category'] != $row_RSTagList['tid']) {?><a href="list_category.php?category=<?php echo $row_RSTagList['tid']; ?>"><?php } ?><?php echo $row_RSTagList['name']; ?><?php if ($_GET['category'] != $row_RSTagList['tid']) {?></a>&nbsp;&nbsp;<?php }?>
							<?php } while ($row_RSTagList = mysql_fetch_assoc($RSTagList)); ?>
                     </p>
                   
                    </div>
                    <div class="box1Bottom">&nbsp;</div>
                </div>
                
				<div class="box1">        
                 	<div class="box1TopRed">
                    	<h1>What our clients are saying...</h1>
                    </div>
                    <div class="box1Contents">   
                    	<div class="clientQuote"><p><?php echo $row_RSGetTestimonial['field_testimonial_copy_value']; ?></p>
                   	  </div>
                    </div>
                    <div class="box1Bottom">&nbsp;</div>
                </div>
            </div>
            <div class="LeftBorderlessBox">
            	<h1>Need help finding the right ad?</h1>
                <p>Contact us. <b>800-558-8237</b></p>
            </div>
<?php mysql_free_result($RSGetTestimonial); ?>