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
	
	if (isset($_POST['createBiomarker'])) {
		if (isset($_POST['title']) && $_POST['title'] != '') {
			
			if (isset($_POST['ispanel'])) {
				if (!isset($_POST['members']) || sizeof($_POST['members']) == 0) {
					$error = "A Panel must contain at least one Biomarker...";
				} else {
					// Create a Biomarker Panel
					$b = BiomarkerFactory::Create($_POST['title']);
					$b->setIsPanel("1");
					$b->setBiomarkerId("urn:edrn:biomarker:".str_replace(" ","",$b->getTitle()));
					// Link the new panel to its Biomarker members
					foreach($_POST['members'] as $bId) {
						$b->link(BiomarkerVars::PANELMEMBERS,$bId);
					}
					XPressPage::httpRedirect("../../edit/biomarker/?id={$b->getObjId()}");
				}
			} else {
				// Create a Biomarker
				$b = BiomarkerFactory::Create($_POST['title']);
				$b->setBiomarkerId("urn:edrn:biomarker:".str_replace(" ","",$b->getTitle()));
				XPressPage::httpRedirect("../../edit/biomarker/?id={$b->getObjId()}"); 
			}
		} else {
			$error = "Please provide a title first...";
		}
		
	}
	
	// Load list of biomarkers (for Panel creation)
	$q = "SELECT `objId`,`Title` from Biomarker WHERE 1";
	$biomarkers = $xpress->db()->getAll($q);
	
	
	$p = new XPressPage(App::NAME." ".App::VERSION,"text/html","UTF-8");
	$p->includeCSS("../../static/css/frozen.css");
	$p->includeJS("../../static/js/mootools/mootools-release-1.11.js");
	$p->includeJS("view/create.js");
	
	$p->open();
	$p->view()->LoadTemplate("view/create.html");
	$p->view()->MergeBlock("biomarkers",$biomarkers);
	$p->view()->Show();
	$p->close();
?>