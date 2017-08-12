<?php include('/var/www/vhosts/95/243968/webspace/httpdocs/adedge.com/Connections/AdEdgeMySql.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AdEdge - Send to a Friend: <?php echo $row_RSGetNodeList['title']; ?></title>
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
            	<h2>Send to a friend</h2>
                <h1>You are sending &quot;<?php echo $_GET['title']; ?>" to a friend</h1>
                <p>Please provide the information below and click "Submit".</p>
       	  </div>
                <form id="contactus" name="contactus" method="post" action="/AdEdge/friend_thank_you.php">
                <fieldset>
                <legend>About You</legend>
                <table width="100%" border="0" cellpadding="5">
                  <tr>
                    <td colspan="4"><span class="style3">Fields marked with an <span class="style2">*</span> are required</span></td>
                  </tr>
                  <tr>
                    <td width="12%"><label>Your Name<span class="style2"> *</span></label>                      </td>
                    <td width="23%"><input type="text" name="fname" id="fname" /></td>
                  </tr>
                  <tr>
                    <td>Your Email<span class="style2"> *</span></td>
                    <td><input type="text" name="email" id="email" /></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
                </fieldset>
                <fieldset>
                <legend>About Your Friend</legend>
                <table width="100%" border="0" cellpadding="5">
                  <tr>
                    <td colspan="4"><span class="style3">Fields marked with an <span class="style2">*</span> are required</span></td>
                  </tr>
                  <tr>
                    <td width="12%"><label>Your Friend's Name<span class="style2"> *</span></label>                      </td>
                    <td width="23%"><input type="text" name="ffname" id="ffname" /><input name="nid" type="hidden" id="nid" value="<?php echo $_GET['v']; ?>" /><input name="title" type="hidden" id="title" value="<?php echo $_GET['title']; ?>" /></td>
                    
					</tr>
                  <tr>
                    <td>Your Friend's Email<span class="style2"> *</span></td>
                    <td><input type="text" name="femail" id="femail" /></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
                </fieldset>

                <fieldset><legend>Message</legend>
                <table width="100%" border="0" cellpadding="5">
  <tr>
    <td width="31%"><p>Use this box to send a personalized message to your friend.</p>
      </td>
    <td width="69%"><textarea name="message" cols="60" rows="4" id="message">Hi there, I found this great ad at http://www.adedge.com that I thought you would like. Check it out here: http://www.adedge.com/AdEdge/view.php?v=<?php echo $_GET['v']; ?></textarea></td>
  </tr>
  <tr>
    <td colspan="2">
      <?php require_once '/var/www/vhosts/95/243968/webspace/httpdocs/adedge.com/AdEdge/securimage/securimage.php'; ?>
      <?php echo Securimage::getCaptchaHtml() ?>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div style="text-align:right">
      <input type="submit" name="submit" id="submit" value="Submit" />
    </div></td>
  </tr>
</table>
                </fieldset>
                </form>
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