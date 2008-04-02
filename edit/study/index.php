<?php
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
	$p = new XPressPage("EDRN - Biomarker Database 0.3.0 Beta","text/html","UTF-8");
	$p->includeCSS('../../static/css/frozen.css');
	$p->includeJS('../../static/js/scriptaculous-js-1.7.0/lib/prototype.js');
	$p->includeJS('../../static/js/scriptaculous-js-1.7.0/src/scriptaculous.js');
	$p->includeJS('../../static/js/textInputs.js');
	$p->open();
	// Load and display the menu
	$p->view()->LoadTemplate('view/menu.html');
	$p->view()->Show();
	// Try to load and display the view
	if ($p->view()->LoadTemplate("view/{$view}.html") ) {
		// View-specific Processing
		if ($view == "basics") {
			$jsStatus = generateAjaxSelectBoxJS('status',
				$s->BiomarkerStudyTypeEnumValues,$s->_getType(),$s->getObjId(),StudyVars::BIOMARKERSTUDYTYPE,"../../xpress/js/AjaxHandler.php");
			$jsBPCCat = generateAjaxSelectBoxJS('bpc',
				$s->BiomarkerPopulationCharacteristicsEnumValues,$s->_getType(),$s->getObjId(),StudyVars::BIOMARKERPOPULATIONCHARACTERISTICS,"../../xpress/js/AjaxHandler.php");
			$jsDesign = generateAjaxSelectBoxJS('design',
				$s->DesignEnumValues,$s->_getType(),$s->getObjId(),StudyVars::DESIGN,"../../xpress/js/AjaxHandler.php");

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
			$pubs = $s->getPublications();
			$p->view()->MergeBlock("pub",$pubs);
		}
		if ($view == "resources") {
			$resources = $s->getResources();
			$p->view()->MergeBlock("res",$resources);
		}

		
		$p->view()->Show();
	}
	$p->close();
?>