<?php include('/vservers/c27464-h224072/htdocs/Connections/AdEdgeMySql.php'); ?>
<?php 

$reelInclude = "<p>The user had the following videos in their favorites list:</p>";
			if (!empty($row_RSGetMyReelList)) {
				do { 
					$reelInclude = $reelInclude."<p><a href='http://208.109.247.235/AdEdge/view.php?v=".$row_RSGetMyReelList["nid"]."'>".htmlentities($row_RSGetMyReelList["title"])."</a></p>";
				 } while ($row_RSGetMyReelList = mysql_fetch_assoc($RSGetMyReelList)); 
					} else {
                   $reelInclude = $reelInclude."<p>No videos were added to their favorites list.</p>";
				   }               
// Contact mail script
// multiple recipients
$to  = 'rh@adedge.com';

// subject
$subject = 'Contact Form Submission';

// message
$message = '
<html>
<head>
  <title>Contact Form Submission from AdEdge.com</title>
</head>
<body>
  <p>The following was submitted on AdEdge.com through the contact us form.</p>
  <p>Name: '.$_POST['fname'].' '.$_POST['lname'].'</p>
  <p>Address 1: '.$_POST['address1'].'</p>
  <p>Address 2: '.$_POST['address2'].'</p>
  <p>City: '.$_POST['city'].'State: '.$_POST['state'].' Zip: '.$_POST['zip'].'</p>
  <p>Phone: '.$_POST['phone'].'</p>
  <p>Message: '.$_POST['message'].'</p>'.$reelInclude.'
  
  
</body>
</html>
';

// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
$headers .= 'To: Randy Hecht <rh@adedge.com>' . "\r\n";
$headers .= 'From: AdEdge <info@adedge.com>' . "\r\n";

// Mail it
mail($to, $subject, $message, $headers);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AdEdge - Viewing Television Ad: <?php echo $row_RSGetNodeList['title']; ?></title>
<link href="/main_styles.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {
	color: #666666;
	font-style: italic;
}
.style2 {color: #FF0000}
.style3 {
	color: #333333;
	font-style: italic;
}
-->
</style>
</head>
<body>
<div id="mainBody">
<!-- Header File Include -->
<?php include('/vservers/c27464-h224072/htdocs/AdEdge/header_inc.php'); ?>
<!-- /Header File Include -->
    <div id="mainContentContainer">
    	<div id="column1ContentPage">
        	<div class="ContentPageHeader">
            	<h2>Contact Us</h2>
                <h1>Thanks!</h1>
                <p>Your message is on its way to us right now. Thanks for taking the time to contact us.</p>
            </div>
                </div>
    	<div id="column2ContentPage">
<!-- 2 Column right copntent File Include -->
<?php include('/vservers/c27464-h224072/htdocs/AdEdge/leNav_1_Colun_Content_Page_inc.php'); ?>
<!-- /2 Column right copntent File Include -->
        </div>      
    </div>
<!-- Footer File Include -->
<?php include('/vservers/c27464-h224072/htdocs/AdEdge/footer__noReel_inc.php'); ?>
<!-- /Footer File Include -->
</div>
</body>
</html>