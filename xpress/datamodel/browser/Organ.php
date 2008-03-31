<?php


	/* PHP Controller code for Organ object browser */
	require_once('../../../xpress/app.php');


	if (isset($_POST['createObject'])) {
		$o = OrganFactory::create($_POST['Name']);
		$o->setName($_POST['Name'],false);
		$o->save();
	}

	if (isset($_POST['deleteObject'])) {
		if (false !== ($o = OrganFactory::Retrieve($_POST['id']))) {
			$o->delete();
		}
	}

	if (isset($_GET['id'])) {
		// Working with an individual object
		if (!false == ($o = OrganFactory::retrieve($_GET['id']))) {
			$attr['objId'] = $o->getobjId();
			$attr['Name'] = $o->getName();
			$oOrganDatas = $o->getOrganDatas();
			$oOrganDatas = $o->getOrganDatas();
			
		} else {
			XPressPage::httpRedirect('../../../notfound.php');
		}

		$p = new XPressPage("Model Browser: Organ ({$_GET['id']})");
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/lib/prototype.js");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/src/scriptaculous.js");
		$p->open();
		$p->view()->LoadTemplate('view/EditOrgan.html');
		$p->view()->MergeBlock('oOrganDatas',$oOrganDatas);
		$p->view()->MergeBlock('oOrganDatas',$oOrganDatas);
		$p->view()->Show();
		$p->close();
	} else {
		// Displaying a list of all objects
		$data = $xpress->db()->getAll(
			"SELECT objId FROM `Organ` ORDER BY objId DESC");
		$o = array();
		foreach ($data as $d) {
			$a  = array();
			$oe = OrganFactory::retrieve($d['objId']);
			$a['objId'] = $oe->getobjId();
			$a['Name'] = $oe->getName();
			$o[] = $a;
		}


		$p = new XPressPage('Model Browser: Organ');
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->open();
		$p->view()->LoadTemplate('view/Organ.html');
		$p->view()->MergeBlock('obj',$o);
		$p->view()->Show();
		$p->close();
	}

?>