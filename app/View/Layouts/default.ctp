<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="/edrn-skin/css/edrn-informatics.css"/>
	<?php echo $this->Html->css('bmdb-core.css');?>
	<?php echo $this->Html->css('ajax-editor.css');?>
	<title>EDRN Biomarker Database: <?php echo $title_for_layout;?></title>
</head>
<body>
<div id="page">
	<div id="ncibanner">
		<div id="ncibanner-inner">
		  <a href="http://www.cancer.gov/"><h2 class="ncilogo">National Cancer Institute</h2></a>
		  <a href="http://www.cancer.gov/"><h2 class="cdglogo">www.cancer.gov</h2></a>
		  <a href="http://www.nih.gov/"><h2 class="nihlogo">National Institutes of Health</h2></a>
		</div>
	</div>
	<br class="clr"/>
	<div id="edrnlogo">
		<h1><img id="edrnlogo-logo" src="/edrn-skin/img/edrn-logo.png"/>Early Detection Research Network</h1>
		<h2>Research and development of biomarkers and technologies for the clinical application of early cancer detection strategies</h2>
	</div>
	<div id="dcplogo">
		<a href="http://prevention.cancer.gov"><h2 class="dcplogo">Division of Cancer Prevention</h2></a>
	</div>
	<div class="userdetails">
		<?php if (isset($_SESSION['username'])) {
			echo "Logged in as: {$_SESSION['username']}. &nbsp;";
			echo "<a href=\"/users/logout\">Log Out</a>";
		} else {
			echo "Not Logged In. &nbsp; ";
			echo "<a href=\"/users/login\">Log In</a>";
		}?>
	</div>
	<h2 class="app-title" style="margin-top:146px;">EDRN Biomarker Database</h2>
	<?php echo $content_for_layout;?>
</div>
<div id="footer">
	A Service of the National Cancer Institute<br/><br/>
	<a href="http://hhs.gov" >
		<img src="/edrn-skin/img/footer_hhs.gif" alt="Department of Health and Human Services"/>
	</a>
	<a href="http://nih.gov" >
		<img src="/edrn-skin/img/footer_nih.gif" style="margin-left:12px;" alt="National Institutes of Health"/>
	</a>
	<a href="http://usa.gov">
		<img src="/edrn-skin/img/footer_usagov.gif"/>
	</a>
</div>
</body>
</html>