<?php
/**
 * 	EDRN Biomarker Database
 *  Curation Webapp
 * 
 *  Author: Andrew F. Hart (andrew.f.hart@jpl.nasa.gov)
 *  
 *  Copyright (c) 2008, California Institute of Technology. 
 *  ALL RIGHTS RESERVED. U.S. Government sponsorship acknowledged.
 * 
 */
	require_once("../../xpress/app.php");
	
	if (isset($_POST['createStudy'])) {
		if (isset($_POST['title']) && $_POST['title'] != '') {
			$s = StudyFactory::Create($_POST['title']);
			$s->setIsEDRN(0); // This is NOT an EDRN study
			XPressPage::httpRedirect("../../edit/study/?id={$s->getObjId()}");
			exit();
		} else {
			$error = "Please provide a title first...";
		}
		
	}
	
	
	$p = new XPressPage(App::NAME." ".App::VERSION,"text/html","UTF-8");
	$p->includeCSS("../../static/css/frozen.css");
	
	$p->open();
	$p->view()->LoadTemplate("view/create.html");
	$p->view()->Show();
	$p->close();
?>