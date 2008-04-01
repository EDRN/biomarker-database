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
	if (false == ($b = BiomarkerFactory::Retrieve($_GET['id']))) {
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
			$jsSecurity = generateAjaxSelectBoxJS('security',
				$b->SecurityEnumValues,$b->_getType(),$b->getObjId(),BiomarkerVars::SECURITY,"../../xpress/js/AjaxHandler.php");
			$jsQAState  = generateAjaxSelectBoxJS('qastate',
				$b->QAStateEnumValues,$b->_getType(),$b->getObjId(),BiomarkerVars::QASTATE,"../../xpress/js/AjaxHandler.php");
			$jsType     = generateAjaxSelectBoxJS('type',
				$b->TypeEnumValues,$b->_getType(),$b->getObjId(),BiomarkerVars::TYPE,"../../xpress/js/AjaxHandler.php");
		}
		if ($view == "organs") {
			$organDatas = $b->getOrganDatas();
			foreach ($organDatas as $o) {$o->getOrgan();} // populate organ data
			$p->view()->MergeBlock("organData",$organDatas);
			$p->view()->MergeBlock("organs","mysql","SELECT objId,Name FROM Organ WHERE 1"); 
		}
		if ($view == "studies") {
			$studyDatas = $b->getStudies();
			foreach ($studyDatas as $s) {$s->getStudy();} // populate study data
			$p->view()->MergeBlock("studyData",$studyDatas);
			$p->view()->MergeBlock("studyDataJs",$studyDatas);
		}
		if ($view == "publications") {
			$pubs = $b->getPublications();
			$p->view()->MergeBlock("pub",$pubs);
		}
		if ($view == "resources") {
			$resources = $b->getResources();
			$p->view()->MergeBlock("res",$resources);
		}

		
		$p->view()->Show();
	}
	$p->close();
?>