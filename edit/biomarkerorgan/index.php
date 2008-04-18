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
	require_once("../../xpress/extensions/ajax_selectbox.php");
	
	// Determine the desired view
	$view = (isset($_GET['view']))
		? $_GET['view']
		: 'basics';	// Default view is the 'basics' view

	// Determine whether the page contents are editable
	$editable = 1;

	// Process any POST or GET actions
	require_once("actions.inc.php");
	
	// Retrieve the desired object from the database
	if (false == ($bo = BiomarkerOrganDataFactory::Retrieve($_GET['id']))) {
		XPressPage::httpRedirect("../notfound.php");
	} 
	$b = $bo->getBiomarker();
	$o = $bo->getOrgan();
	
	// Display the page
	$p = new XPressPage("EDRN - Biomarker Database 0.3.0 Beta","text/html","UTF-8");
	$p->includeCSS('../../static/css/frozen.css');
	$p->includeCSS('../../static/css/eip.css');
	$p->includeCSS("../../static/css/autocomplete.css");
	$p->includeJS('../../static/js/mootools/mootools-release-1.11.js');
	$p->includeJS('../../static/js/eip.js');
	$p->includeJS("../../static/js/autocomplete/Observer.js");
	$p->includeJS("../../static/js/autocomplete/Autocompleter.js");
	// Try to load and display the view
	if ($p->view()->LoadTemplate("view/{$view}.html") ) {
		// View-specific Processing
		if ($view == "basics") {
			$p->includeJS('view/basics.js');
			$phaseOpts    = str_replace(" ","_",implode("|",$bo->PhaseEnumValues));
			$qastateOpts  = str_replace(" ","_",implode("|",$bo->QAStateEnumValues));
		}
		if ($view == "studies") {
			$p->includeJS("view/studies.js");
			$studyDatas = $bo->getStudyDatas();
			foreach ($studyDatas as $s) {
				$s->getStudy(); // Load study data
				$s->getPublications();	// Load referenced publications 
				$s->getResources();		// Load referenced resources
			} 
			$p->view()->MergeBlock("studyData",$studyDatas);
			$p->view()->MergeBlock("studyDataJs",$studyDatas);
			$p->view()->MergeBlock("studyPublications",'array','studyDatas[%p1%][Publications]');
			$p->view()->MergeBlock("studyResources",'array','studyDatas[%p1%][Resources]');
		}
		if ($view == "publications") {
			$p->includeJS("view/publications.js");
			$pubs = $bo->getPublications();
			$p->view()->MergeBlock("pub",$pubs);
		}
		if ($view == "resources") {
			$p->includeJS("view/resources.js");
			$resources = $bo->getResources();
			$p->view()->MergeBlock("res",$resources);
		}

		$p->open();
		$p->view()->Show();
		$p->close();
	}
?>