<?php
	require_once("../../xpress/app.php");
	
	// Determine the desired view
	$view = (isset($_GET['view']))
		? $_GET['view']
		: 'basics';	// Default view is the 'basics' view

	// Determine whether the page contents are editable
	$editable = 1;

	
	if (isset($_POST['special'])){
		if ($_POST['special'] == 'createnew'){
			$obj = new objBiomarker($xp);
			$obj->create($_POST['title']);
			$titleNoSpace = preg_replace("/ /","",ucwords($_POST['title']));
			$obj->setBiomarkerID("urn:edrn:biomarker:{$titleNoSpace}");
			cwsp_page::httpRedirect("./biomarker.php?objId={$obj->getObjId()}&view=basics");
		}
		if ($_POST['special'] == 'delete'){
			$obj = new objBiomarker($xp,$_POST['objId']);
			$obj->delete();
			cwsp_page::httpRedirect("browse/biomarkers/?notice=Biomarker+deleted.");
		}
	}
	
	// Retrieve the desired object from the database
	if (false == ($b = BiomarkerFactory::Retrieve($_GET['id']))) {
		XPressPage::httpRedirect("../notfound.php");
	} 
	
	// Display the page
	$p = new XPressPage("EDRN - Biomarker Database 0.3.0 Beta","text/html","UTF-8");
	$p->includeCSS('../../static/css/frozen.css');
	$p->includeJS('../../static/js/scriptaculous-js-1.7.0/lib/prototype.js');
	$p->includeJS('../../static/js/scriptaculous-js-1.7.0/src/scriptaculous.js');
	$p->open();
	// Load and display the menu
	$p->view()->LoadTemplate('view/menu.html');
	$p->view()->Show();
	// Try to load and display the view
	if ($p->view()->LoadTemplate("view/{$view}.html") ) {
		// View-specific Processing
		if ($view == "organs") {
			$organDatas = $b->getOrganDatas();
			foreach ($organDatas as $o) {$o->getOrgan();} // populate organ data
			$p->view()->MergeBlock("organData",$organDatas);
		}

		
		$p->view()->Show();
	}
	$p->close();
?>