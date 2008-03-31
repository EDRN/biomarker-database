<?php


	/* PHP Controller code for Study object browser */
	require_once('../../../xpress/app.php');


	if (isset($_POST['createObject'])) {
		$o = StudyFactory::create($_POST['Title']);
		$o->setEDRNID($_POST['EDRNID'],false);
		$o->setFHCRC_ID($_POST['FHCRC_ID'],false);
		$o->setDMCC_ID($_POST['DMCC_ID'],false);
		$o->setTitle($_POST['Title'],false);
		$o->setAbstract($_POST['Abstract'],false);
		$o->setBiomarkerPopulationCharacteristics($_POST['BiomarkerPopulationCharacteristics'],false);
		$o->setBPCDescription($_POST['BPCDescription'],false);
		$o->setDesign($_POST['Design'],false);
		$o->setDesignDescription($_POST['DesignDescription'],false);
		$o->setBiomarkerStudyType($_POST['BiomarkerStudyType'],false);
		$o->save();
	}

	if (isset($_POST['deleteObject'])) {
		if (false !== ($o = StudyFactory::Retrieve($_POST['id']))) {
			$o->delete();
		}
	}

	if (isset($_GET['id'])) {
		// Working with an individual object
		if (!false == ($o = StudyFactory::retrieve($_GET['id']))) {
			$attr['objId'] = $o->getobjId();
			$attr['EDRNID'] = $o->getEDRNID();
			$attr['FHCRC_ID'] = $o->getFHCRC_ID();
			$attr['DMCC_ID'] = $o->getDMCC_ID();
			$attr['Title'] = $o->getTitle();
			$attr['Abstract'] = $o->getAbstract();
			$attr['BiomarkerPopulationCharacteristics'] = $o->getBiomarkerPopulationCharacteristics();
			$attr['BPCDescription'] = $o->getBPCDescription();
			$attr['Design'] = $o->getDesign();
			$attr['DesignDescription'] = $o->getDesignDescription();
			$attr['BiomarkerStudyType'] = $o->getBiomarkerStudyType();
			$oBiomarkers = $o->getBiomarkers();
			$oBiomarkerOrgans = $o->getBiomarkerOrgans();
			$oBiomarkerOrganDatas = $o->getBiomarkerOrganDatas();
			$oPublications = $o->getPublications();
			$oResources = $o->getResources();
			$oBiomarkers = $o->getBiomarkers();
			$oBiomarkerOrgans = $o->getBiomarkerOrgans();
			$oBiomarkerOrganDatas = $o->getBiomarkerOrganDatas();
			$oPublications = $o->getPublications();
			$oResources = $o->getResources();
			
		} else {
			XPressPage::httpRedirect('../../../notfound.php');
		}

		$p = new XPressPage("Model Browser: Study ({$_GET['id']})");
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/lib/prototype.js");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/src/scriptaculous.js");
		$p->open();
		$p->view()->LoadTemplate('view/EditStudy.html');
		$p->view()->MergeBlock('oBiomarkers',$oBiomarkers);
		$p->view()->MergeBlock('oBiomarkerOrgans',$oBiomarkerOrgans);
		$p->view()->MergeBlock('oBiomarkerOrganDatas',$oBiomarkerOrganDatas);
		$p->view()->MergeBlock('oPublications',$oPublications);
		$p->view()->MergeBlock('oResources',$oResources);
		$p->view()->MergeBlock('oBiomarkers',$oBiomarkers);
		$p->view()->MergeBlock('oBiomarkerOrgans',$oBiomarkerOrgans);
		$p->view()->MergeBlock('oBiomarkerOrganDatas',$oBiomarkerOrganDatas);
		$p->view()->MergeBlock('oPublications',$oPublications);
		$p->view()->MergeBlock('oResources',$oResources);
		$p->view()->Show();
		$p->close();
	} else {
		// Displaying a list of all objects
		$data = $xpress->db()->getAll(
			"SELECT objId FROM `Study` ORDER BY objId DESC");
		$o = array();
		foreach ($data as $d) {
			$a  = array();
			$oe = StudyFactory::retrieve($d['objId']);
			$a['objId'] = $oe->getobjId();
			$a['EDRNID'] = $oe->getEDRNID();
			$a['FHCRC_ID'] = $oe->getFHCRC_ID();
			$a['DMCC_ID'] = $oe->getDMCC_ID();
			$a['Title'] = $oe->getTitle();
			$a['Abstract'] = $oe->getAbstract();
			$a['BiomarkerPopulationCharacteristics'] = $oe->getBiomarkerPopulationCharacteristics();
			$a['BPCDescription'] = $oe->getBPCDescription();
			$a['Design'] = $oe->getDesign();
			$a['DesignDescription'] = $oe->getDesignDescription();
			$a['BiomarkerStudyType'] = $oe->getBiomarkerStudyType();
			$o[] = $a;
		}


		$p = new XPressPage('Model Browser: Study');
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->open();
		$p->view()->LoadTemplate('view/Study.html');
		$p->view()->MergeBlock('obj',$o);
		$p->view()->Show();
		$p->close();
	}

?>