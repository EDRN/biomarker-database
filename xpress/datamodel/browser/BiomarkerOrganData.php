<?php


	/* PHP Controller code for BiomarkerOrganData object browser */
	require_once('../../../xpress/app.php');


	if (isset($_POST['createObject'])) {
		$o = BiomarkerOrganDataFactory::create($_POST['OrganId'],$_POST['BiomarkerId'],$_POST['OrganId'],$_POST['BiomarkerId']);
		$o->setSensitivityMin($_POST['SensitivityMin'],false);
		$o->setSensitivityMax($_POST['SensitivityMax'],false);
		$o->setSensitivityComment($_POST['SensitivityComment'],false);
		$o->setSpecificityMin($_POST['SpecificityMin'],false);
		$o->setSpecificityMax($_POST['SpecificityMax'],false);
		$o->setSpecificityComment($_POST['SpecificityComment'],false);
		$o->setPPVMin($_POST['PPVMin'],false);
		$o->setPPVMax($_POST['PPVMax'],false);
		$o->setPPVComment($_POST['PPVComment'],false);
		$o->setNPVMin($_POST['NPVMin'],false);
		$o->setNPVMax($_POST['NPVMax'],false);
		$o->setNPVComment($_POST['NPVComment'],false);
		$o->setQAState($_POST['QAState'],false);
		$o->setPhase($_POST['Phase'],false);
		$o->setDescription($_POST['Description'],false);
		$o->save();
	}

	if (isset($_POST['deleteObject'])) {
		if (false !== ($o = BiomarkerOrganDataFactory::Retrieve($_POST['id']))) {
			$o->delete();
		}
	}

	if (isset($_GET['id'])) {
		// Working with an individual object
		if (!false == ($o = BiomarkerOrganDataFactory::retrieve($_GET['id']))) {
			$attr['objId'] = $o->getobjId();
			$attr['SensitivityMin'] = $o->getSensitivityMin();
			$attr['SensitivityMax'] = $o->getSensitivityMax();
			$attr['SensitivityComment'] = $o->getSensitivityComment();
			$attr['SpecificityMin'] = $o->getSpecificityMin();
			$attr['SpecificityMax'] = $o->getSpecificityMax();
			$attr['SpecificityComment'] = $o->getSpecificityComment();
			$attr['PPVMin'] = $o->getPPVMin();
			$attr['PPVMax'] = $o->getPPVMax();
			$attr['PPVComment'] = $o->getPPVComment();
			$attr['NPVMin'] = $o->getNPVMin();
			$attr['NPVMax'] = $o->getNPVMax();
			$attr['NPVComment'] = $o->getNPVComment();
			$attr['QAState'] = $o->getQAState();
			$attr['Phase'] = $o->getPhase();
			$attr['Description'] = $o->getDescription();
			$oOrgan = $o->getOrgan();
			$oBiomarker = $o->getBiomarker();
			$oResources = $o->getResources();
			$oPublications = $o->getPublications();
			$oStudyDatas = $o->getStudyDatas();
			$oOrgan = $o->getOrgan();
			$oBiomarker = $o->getBiomarker();
			$oResources = $o->getResources();
			$oPublications = $o->getPublications();
			$oStudyDatas = $o->getStudyDatas();
			
		} else {
			XPressPage::httpRedirect('../../../notfound.php');
		}

		$p = new XPressPage("Model Browser: BiomarkerOrganData ({$_GET['id']})");
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/lib/prototype.js");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/src/scriptaculous.js");
		$p->open();
		$p->view()->LoadTemplate('view/EditBiomarkerOrganData.html');
		$p->view()->MergeBlock('oResources',$oResources);
		$p->view()->MergeBlock('oPublications',$oPublications);
		$p->view()->MergeBlock('oStudyDatas',$oStudyDatas);
		$p->view()->MergeBlock('oResources',$oResources);
		$p->view()->MergeBlock('oPublications',$oPublications);
		$p->view()->MergeBlock('oStudyDatas',$oStudyDatas);
		$p->view()->Show();
		$p->close();
	} else {
		// Displaying a list of all objects
		$data = $xpress->db()->getAll(
			"SELECT objId FROM `BiomarkerOrganData` ORDER BY objId DESC");
		$o = array();
		foreach ($data as $d) {
			$a  = array();
			$oe = BiomarkerOrganDataFactory::retrieve($d['objId']);
			$a['objId'] = $oe->getobjId();
			$a['SensitivityMin'] = $oe->getSensitivityMin();
			$a['SensitivityMax'] = $oe->getSensitivityMax();
			$a['SensitivityComment'] = $oe->getSensitivityComment();
			$a['SpecificityMin'] = $oe->getSpecificityMin();
			$a['SpecificityMax'] = $oe->getSpecificityMax();
			$a['SpecificityComment'] = $oe->getSpecificityComment();
			$a['PPVMin'] = $oe->getPPVMin();
			$a['PPVMax'] = $oe->getPPVMax();
			$a['PPVComment'] = $oe->getPPVComment();
			$a['NPVMin'] = $oe->getNPVMin();
			$a['NPVMax'] = $oe->getNPVMax();
			$a['NPVComment'] = $oe->getNPVComment();
			$a['QAState'] = $oe->getQAState();
			$a['Phase'] = $oe->getPhase();
			$a['Description'] = $oe->getDescription();
			$o[] = $a;
		}


		$p = new XPressPage('Model Browser: BiomarkerOrganData');
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->open();
		$p->view()->LoadTemplate('view/BiomarkerOrganData.html');
		$p->view()->MergeBlock('obj',$o);
		$p->view()->Show();
		$p->close();
	}

?>