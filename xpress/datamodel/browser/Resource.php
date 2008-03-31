<?php


	/* PHP Controller code for Resource object browser */
	require_once('../../../xpress/app.php');


	if (isset($_POST['createObject'])) {
		$o = ResourceFactory::create();
		$o->setName($_POST['Name'],false);
		$o->setURL($_POST['URL'],false);
		$o->save();
	}

	if (isset($_POST['deleteObject'])) {
		if (false !== ($o = ResourceFactory::Retrieve($_POST['id']))) {
			$o->delete();
		}
	}

	if (isset($_GET['id'])) {
		// Working with an individual object
		if (!false == ($o = ResourceFactory::retrieve($_GET['id']))) {
			$attr['objId'] = $o->getobjId();
			$attr['Name'] = $o->getName();
			$attr['URL'] = $o->getURL();
			$oBiomarkers = $o->getBiomarkers();
			$oBiomarkerOrgans = $o->getBiomarkerOrgans();
			$oBiomarkerOrganStudies = $o->getBiomarkerOrganStudies();
			$oBiomarkerStudies = $o->getBiomarkerStudies();
			$oStudies = $o->getStudies();
			$oBiomarkers = $o->getBiomarkers();
			$oBiomarkerOrgans = $o->getBiomarkerOrgans();
			$oBiomarkerOrganStudies = $o->getBiomarkerOrganStudies();
			$oBiomarkerStudies = $o->getBiomarkerStudies();
			$oStudies = $o->getStudies();
			
		} else {
			XPressPage::httpRedirect('../../../notfound.php');
		}

		$p = new XPressPage("Model Browser: Resource ({$_GET['id']})");
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/lib/prototype.js");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/src/scriptaculous.js");
		$p->open();
		$p->view()->LoadTemplate('view/EditResource.html');
		$p->view()->MergeBlock('oBiomarkers',$oBiomarkers);
		$p->view()->MergeBlock('oBiomarkerOrgans',$oBiomarkerOrgans);
		$p->view()->MergeBlock('oBiomarkerOrganStudies',$oBiomarkerOrganStudies);
		$p->view()->MergeBlock('oBiomarkerStudies',$oBiomarkerStudies);
		$p->view()->MergeBlock('oStudies',$oStudies);
		$p->view()->MergeBlock('oBiomarkers',$oBiomarkers);
		$p->view()->MergeBlock('oBiomarkerOrgans',$oBiomarkerOrgans);
		$p->view()->MergeBlock('oBiomarkerOrganStudies',$oBiomarkerOrganStudies);
		$p->view()->MergeBlock('oBiomarkerStudies',$oBiomarkerStudies);
		$p->view()->MergeBlock('oStudies',$oStudies);
		$p->view()->Show();
		$p->close();
	} else {
		// Displaying a list of all objects
		$data = $xpress->db()->getAll(
			"SELECT objId FROM `Resource` ORDER BY objId DESC");
		$o = array();
		foreach ($data as $d) {
			$a  = array();
			$oe = ResourceFactory::retrieve($d['objId']);
			$a['objId'] = $oe->getobjId();
			$a['Name'] = $oe->getName();
			$a['URL'] = $oe->getURL();
			$o[] = $a;
		}


		$p = new XPressPage('Model Browser: Resource');
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->open();
		$p->view()->LoadTemplate('view/Resource.html');
		$p->view()->MergeBlock('obj',$o);
		$p->view()->Show();
		$p->close();
	}

?>