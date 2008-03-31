<?php


	/* PHP Controller code for Biomarker object browser */
	require_once('../../../xpress/app.php');


	if (isset($_POST['createObject'])) {
		$o = BiomarkerFactory::create($_POST['Title']);
		$o->setEKE_ID($_POST['EKE_ID'],false);
		$o->setBiomarkerID($_POST['BiomarkerID'],false);
		$o->setPanelID($_POST['PanelID'],false);
		$o->setTitle($_POST['Title'],false);
		$o->setShortName($_POST['ShortName'],false);
		$o->setDescription($_POST['Description'],false);
		$o->setQAState($_POST['QAState'],false);
		$o->setPhase($_POST['Phase'],false);
		$o->setSecurity($_POST['Security'],false);
		$o->setType($_POST['Type'],false);
		$o->save();
	}

	if (isset($_POST['deleteObject'])) {
		if (false !== ($o = BiomarkerFactory::Retrieve($_POST['id']))) {
			$o->delete();
		}
	}

	if (isset($_GET['id'])) {
		// Working with an individual object
		if (!false == ($o = BiomarkerFactory::retrieve($_GET['id']))) {
			$attr['objId'] = $o->getobjId();
			$attr['EKE_ID'] = $o->getEKE_ID();
			$attr['BiomarkerID'] = $o->getBiomarkerID();
			$attr['PanelID'] = $o->getPanelID();
			$attr['Title'] = $o->getTitle();
			$attr['ShortName'] = $o->getShortName();
			$attr['Description'] = $o->getDescription();
			$attr['QAState'] = $o->getQAState();
			$attr['Phase'] = $o->getPhase();
			$attr['Security'] = $o->getSecurity();
			$attr['Type'] = $o->getType();
			$oAliases = $o->getAliases();
			$oStudies = $o->getStudies();
			$oOrganDatas = $o->getOrganDatas();
			$oPublications = $o->getPublications();
			$oResources = $o->getResources();
			$oAliases = $o->getAliases();
			$oStudies = $o->getStudies();
			$oOrganDatas = $o->getOrganDatas();
			$oPublications = $o->getPublications();
			$oResources = $o->getResources();
			
		} else {
			XPressPage::httpRedirect('../../../notfound.php');
		}

		$p = new XPressPage("Model Browser: Biomarker ({$_GET['id']})");
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/lib/prototype.js");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/src/scriptaculous.js");
		$p->open();
		$p->view()->LoadTemplate('view/EditBiomarker.html');
		$p->view()->MergeBlock('oAliases',$oAliases);
		$p->view()->MergeBlock('oStudies',$oStudies);
		$p->view()->MergeBlock('oOrganDatas',$oOrganDatas);
		$p->view()->MergeBlock('oPublications',$oPublications);
		$p->view()->MergeBlock('oResources',$oResources);
		$p->view()->MergeBlock('oAliases',$oAliases);
		$p->view()->MergeBlock('oStudies',$oStudies);
		$p->view()->MergeBlock('oOrganDatas',$oOrganDatas);
		$p->view()->MergeBlock('oPublications',$oPublications);
		$p->view()->MergeBlock('oResources',$oResources);
		$p->view()->Show();
		$p->close();
	} else {
		// Displaying a list of all objects
		$data = $xpress->db()->getAll(
			"SELECT objId FROM `Biomarker` ORDER BY objId DESC");
		$o = array();
		foreach ($data as $d) {
			$a  = array();
			$oe = BiomarkerFactory::retrieve($d['objId']);
			$a['objId'] = $oe->getobjId();
			$a['EKE_ID'] = $oe->getEKE_ID();
			$a['BiomarkerID'] = $oe->getBiomarkerID();
			$a['PanelID'] = $oe->getPanelID();
			$a['Title'] = $oe->getTitle();
			$a['ShortName'] = $oe->getShortName();
			$a['Description'] = $oe->getDescription();
			$a['QAState'] = $oe->getQAState();
			$a['Phase'] = $oe->getPhase();
			$a['Security'] = $oe->getSecurity();
			$a['Type'] = $oe->getType();
			$o[] = $a;
		}


		$p = new XPressPage('Model Browser: Biomarker');
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->open();
		$p->view()->LoadTemplate('view/Biomarker.html');
		$p->view()->MergeBlock('obj',$o);
		$p->view()->Show();
		$p->close();
	}

?>