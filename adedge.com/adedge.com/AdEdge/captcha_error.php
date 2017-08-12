<?php include('/var/www/vhosts/95/243968/webspace/httpdocs/adedge.com/Connections/AdEdgeMySql.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AdEdge - Your page was not found</title>
<link href="/main_styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="mainBody">
<!-- Header File Include -->
<?php include('/var/www/vhosts/95/243968/webspace/httpdocs/adedge.com/AdEdge/header_inc.php'); ?>
<!-- /Header File Include -->
    <div id="mainContentContainer">
    	<div id="column1ContentPage">
        	<div class="ContentPageHeader">
            	<h2>Captcha Error</h2>
                <h1>You have entered the wrong captcha.</h1>
            </div>

<?php
if ($_GET['ref']) {
	    echo "<p>You will be redirected to the reference page.</p>";
		header("refresh: 3; url=" . $_GET['ref']);
}
else {
	    header('Location: ' . '/AdEdge/not_found.php');
}
?>
      </div>
        <div id="column2ContentPage">
<!-- 2 Column right copntent File Include -->
<?php include('/var/www/vhosts/95/243968/webspace/httpdocs/adedge.com/AdEdge/leNav_1_Colun_Content_Page_inc.php'); ?>
<!-- /2 Column right copntent File Include -->
        </div>      
    </div>
<!-- Footer File Include -->
<?php include('/var/www/vhosts/95/243968/webspace/httpdocs/adedge.com/AdEdge/footer_inc.php'); ?>
<!-- /Footer File Include -->
</div>
</body>
</html>