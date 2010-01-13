<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="<?php echo PROJROOT?>/edrn-skin/css/edrn-informatics.css"/>
	<?php echo $html->css('bmdb-core.css');?>
	<?php echo $html->css('ajax-editor.css');?>
	<title>EDRN Biomarker Database: <?php echo $title_for_layout;?></title>
</head>
<body>
<div id="page">
	<div id="edrninformatics">
		<div id="edrnlogo">
		    <strong>Early Detection Research Network</strong><br/>
		    <span class="smaller">Division of Cancer Prevention</span>
	    </div>
		<div id="edrn-dna"><!-- dna graphic --></div>
		<h2 class="app-title">EDRN Biomarker Database</h2>
		<div class="userdetails">
			<?php if (isset($_SESSION['username'])) {
				echo "Logged in as: {$_SESSION['username']}. &nbsp;";
				echo "<a href=\"/".PROJROOT."/users/logout\">Log Out</a>";
			} else {
				echo "Not Logged In. &nbsp; ";
				echo "<a href=\"/".PROJROOT."/users/login\">Log In</a>";
			}?>
		</div>
	</div>
	<?php echo $content_for_layout;?>
</div>
<div id="footer">
	A Service of the National Cancer Institute<br/><br/>
	<a href="http://hhs.gov" >
		<img src="<?php echo PROJROOT?>/edrn-skin/img/footer_hhs.gif" alt="Department of Health and Human Services"/>
	</a>
	<a href="http://nih.gov" >
		<img src="<?php echo PROJROOT?>/edrn-skin/img/footer_nih.gif" style="margin-left:12px;" alt="National Institutes of Health"/>
	</a>
	<a href="http://usa.gov">
		<img src="<?php echo PROJROOT?>/edrn-skin/img/footer_usagov.gif"/>
	</a>
</div>
</body>
</html>