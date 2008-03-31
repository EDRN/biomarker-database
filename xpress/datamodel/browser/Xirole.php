<?php


	/* PHP Controller code for Xirole object browser */
	require_once('../../../xpress/app.php');


	if (isset($_POST['createObject'])) {
		$o = XiroleFactory::create($_POST['Name']);
		$o->setName($_POST['Name'],false);
		$o->save();
	}

	if (isset($_POST['deleteObject'])) {
		if (false !== ($o = XiroleFactory::Retrieve($_POST['id']))) {
			$o->delete();
		}
	}

	if (isset($_GET['id'])) {
		// Working with an individual object
		if (!false == ($o = XiroleFactory::retrieve($_GET['id']))) {
			$attr['objId'] = $o->getobjId();
			$attr['Name'] = $o->getName();
			$oUsers = $o->getUsers();
			
		} else {
			XPressPage::httpRedirect('../../../notfound.php');
		}

		$p = new XPressPage("Model Browser: Xirole ({$_GET['id']})");
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/lib/prototype.js");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/src/scriptaculous.js");
		$p->open();
		$p->view()->LoadTemplate('view/EditXirole.html');
		$p->view()->MergeBlock('oUsers',$oUsers);
		$p->view()->Show();
		$p->close();
	} else {
		// Displaying a list of all objects
		$data = $xpress->db()->getAll(
			"SELECT objId FROM `Xirole` ORDER BY objId DESC");
		$o = array();
		foreach ($data as $d) {
			$a  = array();
			$oe = XiroleFactory::retrieve($d['objId']);
			$a['objId'] = $oe->getobjId();
			$a['Name'] = $oe->getName();
			$o[] = $a;
		}


		$p = new XPressPage('Model Browser: Xirole');
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->open();
		$p->view()->LoadTemplate('view/Xirole.html');
		$p->view()->MergeBlock('obj',$o);
		$p->view()->Show();
		$p->close();
	}

?>