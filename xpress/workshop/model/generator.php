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
require_once("../../app.php");
require_once("tools/Generator.php");
require_once("tools/ConfigParse.class.php");

	// Hide debug output
	define("DEBUGGING",false);

	// Generate the project
	$g = new Generator();
	if ($g->init(App::ROOT_DIR,App::DSN,App::PEAR_PATH)){
		$g->generate();
	}

	// Display the page
	$p = new XPressPage("XPress::Workshop::Model: Generator",'text-html','UTF-8');
	$p->includeCSS("static/css/model.css");
	$p->includeJS("view/home.js");
	$p->open();
	$p->view()->LoadTemplate('view/generator.html');
	$p->view()->Show();	
	$p->close();
?>