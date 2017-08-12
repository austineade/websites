<?php include('/vservers/c27464-h224072/htdocs/Connections/AdEdgeMySql.php'); ?>
<?php 

$quoterequest = "<a href='http://208.109.247.235/AdEdge/view.php?v=".$_POST['nid']."'>".$_POST['title']."</a>";			      
// Contact mail script
// multiple recipients
$to  = $_POST['femail'];
// subject
$subject = $_POST['fname'].' sent you this great ad';

// message
$message = '
<html>
<head>
  <title>'.$_POST['fname'].' Sent This Great Ad from AdEdge.com</title>
</head>
<body>
  <p>'.$_POST['fname'].' sent you this ad from AdEdge.com.</p>
  <p>View the ad by clicking here: '.$quoterequest.'</p>
  <p>Message from '.$_POST['fname'].'</p>
  <p>Message: '.$_POST['message'].'</p>
</body>
</html>
';

// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
$headers .= 'To: '.$_POST['ffname'].' <'.$_POST['femail'].'>' . "\r\n";
$headers .= 'From: '.$_POST['fname'].' <'.$_POST['email'].'>' . "\r\n";

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
            	<h2>Send to Friend</h2>
                <h1>Thanks! Your friend will receive this ad soon.</h1>
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