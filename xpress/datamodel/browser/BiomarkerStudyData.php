<?php


	/* PHP Controller code for BiomarkerStudyData object browser */
	require_once('../../../xpress/app.php');


	if (isset($_POST['createObject'])) {
		$o = BiomarkerStudyDataFactory::create($_POST['StudyId'],$_POST['BiomarkerId'],$_POST['StudyId'],$_POST['BiomarkerId']);
		$o->setSensitivity($_POST['Sensitivity'],false);
		$o->setSpecificity($_POST['Specificity'],false);
		$o->setPPV($_POST['PPV'],false);
		$o->setNPV($_POST['NPV'],false);
		$o->setAssay($_POST['Assay'],false);
		$o->setTechnology($_POST['Technology'],false);
		$o->save();
	}

	if (isset($_POST['deleteObject'])) {
		if (false !== ($o = BiomarkerStudyDataFactory::Retrieve($_POST['id']))) {
			$o->delete();
		}
	}

	if (isset($_GET['id'])) {
		// Working with an individual object
		if (!false == ($o = BiomarkerStudyDataFactory::retrieve($_GET['id']))) {
			$attr['objId'] = $o->getobjId();
			$attr['Sensitivity'] = $o->getSensitivity();
			$attr['Specificity'] = $o->getSpecificity();
			$attr['PPV'] = $o->getPPV();
			$attr['NPV'] = $o->getNPV();
			$attr['Assay'] = $o->getAssay();
			$attr['Technology'] = $o->getTechnology();
			$oStudy = $o->getStudy();
			$oBiomarker = $o->getBiomarker();
			$oPublications = $o->getPublications();
			$oResources = $o->getResources();
			$oStudy = $o->getStudy();
			$oBiomarker = $o->getBiomarker();
			$oPublications = $o->getPublications();
			$oResources = $o->getResources();
			
		} else {
			XPressPage::httpRedirect('../../../notfound.php');
		}

		$p = new XPressPage("Model Browser: BiomarkerStudyData ({$_GET['id']})");
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/lib/prototype.js");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/src/scriptaculous.js");
		$p->open();
		$p->view()->LoadTemplate('view/EditBiomarkerStudyData.html');
		$p->view()->MergeBlock('oPublications',$oPublications);
		$p->view()->MergeBlock('oResources',$oResources);
		$p->view()->MergeBlock('oPublications',$oPublications);
		$p->view()->MergeBlock('oResources',$oResources);
		$p->view()->Show();
		$p->close();
	} else {
		// Displaying a list of all objects
		$data = $xpress->db()->getAll(
			"SELECT objId FROM `BiomarkerStudyData` ORDER BY objId DESC");
		$o = array();
		foreach ($data as $d) {
			$a  = array();
			$oe = BiomarkerStudyDataFactory::retrieve($d['objId']);
			$a['objId'] = $oe->getobjId();
			$a['Sensitivity'] = $oe->getSensitivity();
			$a['Specificity'] = $oe->getSpecificity();
			$a['PPV'] = $oe->getPPV();
			$a['NPV'] = $oe->getNPV();
			$a['Assay'] = $oe->getAssay();
			$a['Technology'] = $oe->getTechnology();
			$o[] = $a;
		}


		$p = new XPressPage('Model Browser: BiomarkerStudyData');
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->open();
		$p->view()->LoadTemplate('view/BiomarkerStudyData.html');
		$p->view()->MergeBlock('obj',$o);
		$p->view()->Show();
		$p->close();
	}

?>