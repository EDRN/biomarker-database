<?php


	/* PHP Controller code for Site object browser */
	require_once('../../../xpress/app.php');


	if (isset($_POST['createObject'])) {
		$o = SiteFactory::create();
		$o->setName($_POST['Name'],false);
		$o->save();
	}

	if (isset($_POST['deleteObject'])) {
		if (false !== ($o = SiteFactory::Retrieve($_POST['id']))) {
			$o->delete();
		}
	}

	if (isset($_GET['id'])) {
		// Working with an individual object
		if (!false == ($o = SiteFactory::retrieve($_GET['id']))) {
			$attr['objId'] = $o->getobjId();
			$attr['Name'] = $o->getName();
			$oStaff = $o->getStaff();
			$oStaff = $o->getStaff();
			
		} else {
			XPressPage::httpRedirect('../../../notfound.php');
		}

		$p = new XPressPage("Model Browser: Site ({$_GET['id']})");
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/lib/prototype.js");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/src/scriptaculous.js");
		$p->open();
		$p->view()->LoadTemplate('view/EditSite.html');
		$p->view()->MergeBlock('oStaff',$oStaff);
		$p->view()->MergeBlock('oStaff',$oStaff);
		$p->view()->Show();
		$p->close();
	} else {
		// Displaying a list of all objects
		$data = $xpress->db()->getAll(
			"SELECT objId FROM `Site` ORDER BY objId DESC");
		$o = array();
		foreach ($data as $d) {
			$a  = array();
			$oe = SiteFactory::retrieve($d['objId']);
			$a['objId'] = $oe->getobjId();
			$a['Name'] = $oe->getName();
			$o[] = $a;
		}


		$p = new XPressPage('Model Browser: Site');
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->open();
		$p->view()->LoadTemplate('view/Site.html');
		$p->view()->MergeBlock('obj',$o);
		$p->view()->Show();
		$p->close();
	}

?>