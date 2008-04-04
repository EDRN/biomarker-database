<?php
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
		XPressPage::httpRedirect("../notfound.php");
	} 
	
	// Display the page
	$p = new XPressPage("EDRN - Biomarker Database 0.3.0 Beta","text/html","UTF-8");
	$p->includeCSS('../../static/css/frozen.css');
	$p->includeCSS("../../static/css/eip.css");
	$p->includeJS("../../static/js/mootools/mootools-release-1.11.js");
	$p->includeJS("../../static/js/eip.js");
	
	$p->includeJS("view/{$view}.js");
	$p->open();
	$p->view()->LoadTemplate("view/{$view}.html");


	// View-specific Processing
	switch ($view) {
		case "basics":
			$securityOpts = str_replace(" ","_",implode("|",$b->SecurityEnumValues));
			$qastateOpts  = str_replace(" ","_",implode("|",$b->QAStateEnumValues));
			$typeOpts     = str_replace(" ","_",implode("|",$b->TypeEnumValues));
			break;
		case "organs":
			$organDatas = $b->getOrganDatas();
			foreach ($organDatas as $o) {
				$o->getOrgan();			// Load organ data
			}
			$p->view()->MergeBlock("organData",$organDatas);
			$p->view()->MergeBlock("organs","mysql","SELECT objId,Name FROM Organ"); 
			break;
		case "studies":
			$studyDatas = $b->getStudies();
			foreach ($studyDatas as $s) {
				$s->getStudy();			// Load study data
				$s->getPublications();	// Load referenced publications
			}
			$p->view()->MergeBlock("studyData",'array','studyDatas');
			$p->view()->MergeBlock("studyPublications",'array','studyDatas[%p1%][Publications]');
			break;
		case "publications":
			$p->view()->MergeBlock("pub",$b->getPublications());
			break;
		case "resources":
			$p->view()->MergeBlock("res",$b->getResources());
			break;
	}

	
	$p->view()->Show();
	$p->close();
?>