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
	if (false == ($bo = BiomarkerOrganDataFactory::Retrieve($_GET['id']))) {
		XPressPage::httpRedirect("../notfound.php");
	} 
	$b = $bo->getBiomarker();
	$o = $bo->getOrgan();
	
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
			$jsPhase = generateAjaxSelectBoxJS('phase',
				$bo->PhaseEnumValues,$bo->_getType(),$bo->getObjId(),BiomarkerVars::PHASE,"../../xpress/js/AjaxHandler.php");
			$jsQAState = generateAjaxSelectBoxJS('qastate',
				$bo->QAStateEnumValues,$bo->_getType(),$bo->getObjId(),BiomarkerVars::QASTATE,"../../xpress/js/AjaxHandler.php");
		}
		if ($view == "studies") {
			$studyDatas = $bo->getStudies();
			foreach ($studyDatas as $s) {$s->getStudy();} // populate study data
			$p->view()->MergeBlock("studyData",$studyDatas);
			$p->view()->MergeBlock("studyDataJs",$studyDatas);
		}
		if ($view == "publications") {
			$pubs = $bo->getPublications();
			$p->view()->MergeBlock("pub",$pubs);
		}
		if ($view == "resources") {
			$resources = $bo->getResources();
			$p->view()->MergeBlock("res",$resources);
		}

		
		$p->view()->Show();
	}
	$p->close();
?>