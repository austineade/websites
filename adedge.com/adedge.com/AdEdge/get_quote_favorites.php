<?php include('/var/www/vhosts/95/243968/webspace/httpdocs/adedge.com/Connections/AdEdgeMySql.php'); ?>

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

<script type="text/javascript">
<!--
function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
      if (val) { nm=val.name; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
    } if (errors) alert('The following error(s) occurred:\n'+errors);
    document.MM_returnValue = (errors == '');
} }
//-->
</script>
</head>

<body>

<div id="mainBody">

<!-- Header File Include -->

<?php include('/var/www/vhosts/95/243968/webspace/httpdocs/adedge.com/AdEdge/header_inc.php'); ?>

<!-- /Header File Include -->

    <div id="mainContentContainer">

    	<div id="column1ContentPage">

        	<div class="ContentPageHeader">

            	<h2>Request A Quote</h2>

                <h1>You have requested a quote for the <?php echo $totalRows_RSGetMyReelList; ?> ads in your favorites list.</h1>

                <p><b>Requesting a quote only takes a minute!</b> Please provide the information below and click "Submit". One of our representatives will contact you as soon as possible with a price quote.</p>

                <p> Or, feel free to give us a call at <b>800-558-8237</b>.</p>

       	  </div>

                <form action="/AdEdge/quote_favorites_thank_you.php" method="post" name="contactus" id="contactus" onsubmit="MM_validateForm('fname','','R','lname','','R','email','','RisEmail','company','','R','phone','','R','captcha_code','','R');return document.MM_returnValue">

                <fieldset>

                <legend>About You</legend>

                <table width="100%" border="0" cellpadding="5">

                  <tr>

                    <td colspan="4"><span class="style3">Fields marked with an <span class="style2">*</span> are required</span></td>

                  </tr>

                  <tr>

                    <td width="12%"><label>First Name<span class="style2"> *</span></label>                      </td>

                    <td width="23%"><input type="text" name="fname" id="fname" /></td>

                    <td width="12%">Last Name <span class="style2">*</span></td>

                    <td width="53%"><input type="text" name="lname" id="lname" /></td>

                  </tr>

                  <tr>

                    <td>Email</td>

                    <td><input type="text" name="email" id="email" /></td>

                    <td>&nbsp;</td>

                    <td>&nbsp;</td>

                  </tr>

                  <tr>

                    <td>Address</td>

                    <td><input type="text" name="address1" id="address1" /></td>

                    <td>&nbsp;</td>

                    <td><input name="title" type="hidden" id="title" value="<?php echo $_GET['title']; ?>" />

                    <input name="nid" type="hidden" id="nid" value="<?php echo $_GET['v']; ?>" /></td>

                  </tr>

                  <tr>

                    <td>&nbsp;</td>

                    <td><input type="text" name="address2" id="address2" /></td>

                    <td>&nbsp;</td>

                    <td>&nbsp;</td>

                  </tr>

                  <tr>

                    <td>Company<span class="style2"> *</span></td>

                    <td><input type="text" name="company" id="company" /></td>

                    <td colspan="2">&nbsp;</td>

                  </tr>

                  <tr>

                    <td><label>City</label>

                        <span id="spryselect2"> </span></td>

                    <td><input type="text" name="city" id="city" /></td>

                    <td colspan="2">State&nbsp;

                      <select name="state" id="state">

                        <Option disabled>

                        <Option   value ="AK">AK - Alaska

                        <Option   value ="AL">AL - Alabama

                        <Option   value ="AR">AR - Arkansas

                        <Option   value ="AS">AS - American Samoa

                        <OPTION   value ="AZ">AZ - Arizona

                        <OPTION   value ="CA">CA - California

                        <OPTION   value = CO >CO - Colorado

                        <OPTION   value = CT >CT - Connecticut

                        <OPTION   value = DC >DC - District of Columbia

                        <OPTION   value = DE >DE - Delaware

                        <OPTION   value = FL >FL - Florida

                        <OPTION   value = GA >GA - Georgia

                        <OPTION   value = GU >GU - Guam

                        <OPTION   value = HI >HI - Hawaii

                        <OPTION   value = IA >IA - Iowa

                        <OPTION   value = ID >ID - Idaho

                        <OPTION   value = IL >IL - Illinois

                        <OPTION   value = IN >IN - Indiana

                        <OPTION   value = KS >KS - Kansas

                        <OPTION   value = KY >KY - Kentucky

                        <OPTION   value = LA >LA - Louisiana

                        <OPTION   value = MA >MA - Massachusetts

                        <OPTION   value = MD >MD - Maryland

                        <OPTION   value = ME >ME - Maine

                        <OPTION   value = MI >MI - Michigan

                        <OPTION   value = MN >MN - Minnesota

                        <OPTION   value = MO >MO - Missouri

                        <OPTION   value = MP >MP - Mariana Islands

                        <OPTION   value = MS >MS - Mississippi

                        <OPTION   value = MT >MT - Montana

                        <OPTION   value = NC >NC - North Carolina

                        <OPTION   value = ND >ND - North Dakota

                        <OPTION   value = NE >NE - Nebraska

                        <OPTION   value = NH >NH - New Hampshire

                        <OPTION   value = NJ >NJ - New Jersey

                        <OPTION   value = NM >NM - New Mexico

                        <OPTION   value = NV >NV - Nevada

                        <OPTION   value = NY >NY - New York

                        <OPTION   value = OH >OH - Ohio

                        <OPTION   value = OK >OK - Oklahoma

                        <OPTION   value = OR >OR - Oregon

                        <OPTION   value = PA >PA - Pennsylvania

                        <OPTION   value = PR >PR - Puerto Rico

                        <OPTION   value = RI >RI - Rhode Island

                        <OPTION   value = SC >SC - South Carolina

                        <OPTION   value = SD >SD - South Dakota

                        <OPTION   value = TN >TN - Tennessee

                        <OPTION   value = TX >TX - Texas

                        <OPTION   value = UT >UT - Utah

                        <OPTION   value = VA >VA - Virginia

                        <OPTION   value = VI >VI - Virgin Islands (U.S.)

                        <OPTION   value = VT >VT - Vermont

                        <OPTION   value = WA >WA - Washington

                        <OPTION   value = WI >WI - Wisconsin

                        <OPTION   value = WV >WV - West Virginia

                        <OPTION   value = WY >WY - Wyoming

                        <Option disabled>

                        <OPTION   value = CN >Canada - Border Zone:

                        <Option disabled>

                        <OPTION   value = AB >AB - Alberta

                        <OPTION   value = BC >BC - British Columbia

                        <OPTION   value = MB >MB - Manitoba

                        <OPTION   value = NB >NB - New Brunswick

                        <OPTION   value = NS >NS - Nova Scotia

                        <Option   Value = NT >NT - Northwest Territories

                        <OPTION   value = ON >ON - Ontario

                        <OPTION   value = PE >PE - Prince Edward Island

                        <OPTION   value = QC >QC - Quebec

                        <OPTION   value = SA >SA - Saskatchewan

                        <OPTION   value = YT >YT - Yukon

                        <Option disabled>

                        <OPTION   value = MX >Mexico - Border Zone:

                        <Option disabled>

                        <OPTION   value = BN >BN - Baja California (N)

                        <OPTION   value = CH >CH - Chihuahua

                        <Option   Value = CI >CI - Coahuila

                        <OPTION   value = NL >NL - Nuevo Leon

                        <OPTION   value = SO >SO - Sonora

                        <OPTION   value = TA >TA - Tamaulipas

                        <Option disabled>

                        </select>&nbsp;Zip &nbsp;

                    <input name="zip" type="text" id="zip" size="7" /></td>

                  </tr>

                  <tr>

                    <td>Phone<span class="style2"> *</span></td>

                    <td><input type="text" name="phone" id="phone" /></td>

                    <td colspan="2"><span class="style1">(212-555-1212)</span></td>

                  </tr>

                  <tr>

                    <td colspan="4">Television Market Area (DMA)

                      &nbsp;

                      <input name="dma" type="text" id="dma" size="40" />

                      <span class="style1">(ex: New York City or Dallas/Fort Worth)</span></td>

                  </tr>

				  <tr>

				    <td colspan="4">

					  <?php require_once '/var/www/vhosts/95/243968/webspace/httpdocs/adedge.com/AdEdge/securimage/securimage.php'; ?>
					  <?php echo Securimage::getCaptchaHtml() ?>

					</td>

                  </tr>

                </table>

                </fieldset>

                <fieldset><legend>Additional Information</legend>

                <table width="100%" border="0" cellpadding="5">

  <tr>

    <td width="31%"><p>Use this box to ask a question, make a request or leave a comment. It is optional.</p>

      </td>

    <td width="69%"><textarea name="message" cols="60" rows="4" id="message"></textarea></td>

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