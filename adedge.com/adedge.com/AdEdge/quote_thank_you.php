<?php include('/var/www/vhosts/95/243968/webspace/httpdocs/adedge.com/Connections/AdEdgeMySql.php'); ?>
<?php 

require_once '/var/www/vhosts/95/243968/webspace/httpdocs/adedge.com/AdEdge/securimage/securimage.php';

$image = new Securimage();
if ($image->check($_POST['captcha_code']) != true) {
	header('Location: ' . '/AdEdge/captcha_error.php?ref=' . $_SERVER['HTTP_REFERER']);
	exit('Captcha Error');
}


$reelInclude = "<p>The user had the following videos in their favorites list:</p>";
			if (!empty($row_RSGetMyReelList)) {
				do { 
					$reelInclude = $reelInclude."<p><a href='http://208.109.247.235/AdEdge/view.php?v=".$row_RSGetMyReelList["nid"]."'>".htmlentities($row_RSGetMyReelList["title"])."</a></p>";
				 } while ($row_RSGetMyReelList = mysql_fetch_assoc($RSGetMyReelList)); 
					} else {
                   $reelInclude = $reelInclude."<p>No videos were added to their favorites list.</p>";
				   }          
$quoterequest = "<a href='http://208.109.247.235/AdEdge/view.php?v=".$_POST['nid']."'>.".$_POST['title']."</a>";			      


//Load PHP Mailer - Latest Code, Examples and Documentation can be taken from: https://github.com/PHPMailer/PHPMailer
require_once '/var/www/vhosts/95/243968/webspace/httpdocs/adedge.com/AdEdge/PHPMailer-master/PHPMailerAutoload.php'; 

$mail = new PHPMailer;
//$mail->SMTPDebug = 3;                               // Enable verbose debug output
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'mail24.safesecureweb.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'RH@adedge.com';                 // SMTP username
$mail->Password = 'Adedge415';                           // SMTP password
//$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 25;                                    // TCP port to connect to
$mail->From = 'info@adedge.com';
$mail->FromName = 'AdEdge';
$mail->addAddress('rh@adedge.com', 'Randy Hecht');     // Add a recipient
$mail->addReplyTo($_POST['email'], $_POST['fname'].' '.$_POST['lname']);
//$mail->addCC('cc@example.com');
//$mail->addBCC('raphaellandau@gmail.com');
$mail->isHTML(true);
$mail->Subject = 'Quote Form Submission';
// message
$mail->Body = '
<html>
<head>
  <title>Quote Form Submission from AdEdge.com</title>
</head>
<body>
  <p>The following was submitted on AdEdge.com through the request a quote form.</p>
  <p>Quote requested for: '.$quoterequest.'</p>
  <p>Email: '.$_POST['email'].'</p>
  <p>Name: '.$_POST['fname'].' '.$_POST['lname'].'</p>
  <p>Address 1: '.$_POST['address1'].'</p>
  <p>Address 2: '.$_POST['address2'].'</p>
  <p>City: '.$_POST['city'].'</p>
  <p>State: '.$_POST['state'].'</p> 
  <p>Zip: '.$_POST['zip'].'</p>
  <p>Phone: '.$_POST['phone'].'</p>
  <p>Company: '.$_POST['company'].'</p>
  <p>TV Market: '.$_POST['dma'].'</p>
  <p>Message: '.$_POST['message'].'</p>'.$reelInclude.'
</body>
</html>';

if(!$mail->send()) {
    $MailSent = false;
    $MailResponse = $mail->ErrorInfo;
} else {
    $MailSent = true;
}
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
<?php include('/var/www/vhosts/95/243968/webspace/httpdocs/adedge.com/AdEdge/header_inc.php'); ?>
<!-- /Header File Include -->
    <div id="mainContentContainer">
    	<div id="column1ContentPage">
        	<div class="ContentPageHeader">
              <?php if ($MailSent) { ?>
            	  <h2>Contact Us</h2>
                <h1>Thanks! Your quote is on its way.</h1>
                <p>One of our representatives will contact you as soon as possible with the price quotes you have requested.</p>
                <?php } else { ?>
                <h1>Well this is embarrassing!</h1>
                <p>There was an error sending out your message. Please email us directly at <a href="mailto:rh@adedge.com">rh@adedge.com</a> or give us a call at 800-558-8237.</p>
                <p>Error Info: <?php echo $mail->ErrorInfo; ?></p>
                <?php } ?>
            </div>
                </div>
    	<div id="column2ContentPage">
<!-- 2 Column right copntent File Include -->
<?php include('/var/www/vhosts/95/243968/webspace/httpdocs/adedge.com/AdEdge/leNav_1_Colun_Content_Page_inc.php'); ?>
<!-- /2 Column right copntent File Include -->
        </div>      
    </div>
<!-- Footer File Include -->
<?php include('/var/www/vhosts/95/243968/webspace/httpdocs/adedge.com/AdEdge/footer__noReel_inc.php'); ?>
<!-- /Footer File Include -->
</div>
</body>
</html>