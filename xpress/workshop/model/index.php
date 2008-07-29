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
require_once("../../framework/page/XPressPage.class.php");
require_once("../../framework/page/tbs/tbs_3.3.0b12_php5.php");

	class App {
		const SHOW_PAGE_STATS = false;
	}

	$p = new XPressPage("XPress::Workshop::Model: Home",'text-html','UTF-8');
	$p->includeCSS("static/css/model.css");
	$p->includeJS("view/home.js");
	$p->open();
	$p->view()->LoadTemplate("view/home.html");
	$p->view()->Show();
	$p->close();
?>