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
	
	if (false == ($pub = PublicationFactory::Retrieve($_GET['id']))) {
		XPressPage::httpRedirect("../../notfound.php");
	}
	
	$p = new XPressPage(App::NAME." ".App::VERSION,"text/html","UTF-8");
	$p->includeCSS("../../static/css/frozen.css");
	$p->open();
	$p->view()->LoadTemplate("view/goto.html");
	$p->view()->Show();
	$p->close();
?>