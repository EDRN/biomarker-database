<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php echo $html->css('edrn-informatics');?>
<?php echo $html->css('bmdb-core.css');?>
<?php echo $html->css('ajax-editor.css');?>
<title>EDRN Biomarker Database: <?php echo $title_for_layout;?></title>
<style type="text/css">
	div#edrninformatics {
		/* custom location for the background gradient */
		background:url(/<?php echo PROJROOT?>/img/blackfade-syncd.jpg) scroll 0px 0px repeat-x;
		background-color:#000;
	}
	
	div#edrnlogo {
		/* custom location for the NCI/EDRN logo */
		background:url(/<?php echo PROJROOT?>/img/edrnlogo-bigger-red.gif) scroll -7px 1px no-repeat;
	}
	
	div#smalllinks {
		/* custom location for the background image */
		background:url(/<?php echo PROJROOT?>/img/grayfade.gif) scroll left top repeat-x;
	}
</style>
</head>
<body>
<div id="page">
	<div id="edrninformatics">
		<div id="edrnlogo"><!-- nci logo --></div>
		<img src="/<?php echo PROJROOT?>/img/edrn_dna-bigger.jpg" style="height:55px;margin-top:-3px;margin-right:-5px;"/>
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
</body>
</html>