<?php
	require_once("../../xpress/app.php");
	
	if (isset($_POST['createBiomarker'])) {
		if (isset($_POST['title']) && $_POST['title'] != '') {
			$b = BiomarkerFactory::Create($_POST['title']);
			XPressPage::httpRedirect("../../edit/biomarker/?id={$b->getObjId()}");
			exit();
		} else {
			$error = "Please provide a title first...";
		}
		
	}
	
	
	$p = new XPressPage("EDRN Biomarker Database - Create Biomarker","text/html","UTF-8");
	$p->includeCSS("../../static/css/frozen.css");
	
	$p->open();
	$p->view()->LoadTemplate("view/create.html");
	$p->view()->Show();
	$p->close();
?>