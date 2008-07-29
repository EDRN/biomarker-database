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
	
	// Determine the desired view
	$view = (isset($_GET['view']))
		? strtolower($_GET['view'])
		: 'basics';	// Default view is the 'basics' view

	// Determine whether the page contents are editable
	$editable = 1;

	// Process any POST or GET actions
	include_once("actions.inc.php");
	
	// Retrieve the desired object from the database
	if (false == ($b = BiomarkerFactory::Retrieve($_GET['id']))) {
		XPressPage::httpRedirect("../../error/?e=notfound&target=".urlencode($_SERVER['REQUEST_URI']));
	} 
	
	// Display the page
	$p = new XPressPage(App::NAME." ".App::VERSION,"text/html","UTF-8");
	$p->includeCSS('../../static/css/frozen.css');
	$p->includeCSS("../../static/css/eip.css");
	$p->includeCSS("../../static/css/autocomplete.css");
	$p->includeJS("../../static/js/mootools/mootools-release-1.11.js");
	$p->includeJS("../../static/js/eip.js");
	$p->includeJS("../../static/js/autocomplete/Observer.js");
	$p->includeJS("../../static/js/autocomplete/Autocompleter.js");

	
	$p->view()->LoadTemplate("view/{$view}.html");
	// View-specific Processing
	switch ($view) {
		case "basics":
			$p->includeJS("view/basics.js");
			$securityOpts = str_replace(" ","_",implode("|",$b->SecurityEnumValues));
			$qastateOpts  = str_replace(" ","_",implode("|",$b->QAStateEnumValues));
			$typeOpts     = str_replace(" ","_",implode("|",$b->TypeEnumValues));
			$p->view()->MergeBlock("panelMember",$b->getPanelMembers());
			break;
		case "organs":
			$p->includeJS("view/organs.js");
			$organDatas = $b->getOrganDatas();
			foreach ($organDatas as $o) {
				$o->getOrgan();			// Load organ data
			}
			$p->view()->MergeBlock("organData",$organDatas);
			$p->view()->MergeBlock("organs","mysql","SELECT objId,Name FROM Organ"); 
			break;
		case "studies":
			$p->includeJS("view/studies.js");
			$studyDatas = $b->getStudies();
			foreach ($studyDatas as $s) {
				$s->getStudy();			// Load study data
				$s->getPublications();	// Load referenced publications
				$s->getResources();		// Load referenced resources
			}
			$p->view()->MergeBlock("studyData",'array','studyDatas');
			$p->view()->MergeBlock("studyPublications",'array','studyDatas[%p1%][Publications]');
			$p->view()->MergeBlock("studyResources",'array','studyDatas[%p1%][Resources]');
			break;
		case "publications":
			$p->includeJS("view/publications.js");
			$p->view()->MergeBlock("pub",$b->getPublications());
			break;
		case "resources":
			$p->includeJS("view/resources.js");
			$p->view()->MergeBlock("res",$b->getResources());
			break;
	}

	$p->open();
	$p->view()->Show();
	$p->close();
?>