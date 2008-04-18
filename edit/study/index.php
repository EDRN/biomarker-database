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
	include_once("actions.inc.php");
	
	// Retrieve the desired object from the database
	if (false == ($s = StudyFactory::Retrieve($_GET['id']))) {
		XPressPage::httpRedirect("../notfound.php");
	} 
	
	// Display the page
	$p = new XPressPage(App::NAME." ".App::VERSION,"text/html","UTF-8");
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
			$p->includeJS("view/basics.js");
			$statusOpts    = str_replace(" ","_",implode("|",$s->BiomarkerStudyTypeEnumValues));
			$bpcOpts  = str_replace(" ","_",implode("|",$s->BiomarkerPopulationCharacteristicsEnumValues));
			$designOpts  = str_replace(" ","_",implode("|",$s->DesignEnumValues));
		}
		if ($view == "biomarkerorgans") {
			$bosd = $s->getBiomarkerOrganDatas();
			foreach ($bosd as $abosd) {
				$abosd->getBiomarkerOrganData()->getBiomarker();
				$abosd->getBiomarkerOrganData()->getOrgan();
			}
			$p->view()->MergeBlock("bosd",$bosd); 
		}
		if ($view == "publications") {
			$p->includeJS("view/publications.js");
			$pubs = $s->getPublications();
			$p->view()->MergeBlock("pub",$pubs);
		}
		if ($view == "resources") {
			$p->includeJS("view/resources.js");
			$resources = $s->getResources();
			$p->view()->MergeBlock("res",$resources);
		}

		$p->open();
		$p->view()->Show();
		$p->close();
	}
?>