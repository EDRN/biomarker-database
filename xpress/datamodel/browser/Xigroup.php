<?php


	/* PHP Controller code for Xigroup object browser */
	require_once('../../../xpress/app.php');


	if (isset($_POST['createObject'])) {
		$o = XigroupFactory::create($_POST['Name']);
		$o->setName($_POST['Name'],false);
		$o->setObjectClass($_POST['ObjectClass'],false);
		$o->save();
	}

	if (isset($_POST['deleteObject'])) {
		if (false !== ($o = XigroupFactory::Retrieve($_POST['id']))) {
			$o->delete();
		}
	}

	if (isset($_GET['id'])) {
		// Working with an individual object
		if (!false == ($o = XigroupFactory::retrieve($_GET['id']))) {
			$attr['objId'] = $o->getobjId();
			$attr['Name'] = $o->getName();
			$attr['ObjectClass'] = $o->getObjectClass();
			$oUsers = $o->getUsers();
			
		} else {
			XPressPage::httpRedirect('../../../notfound.php');
		}

		$p = new XPressPage("Model Browser: Xigroup ({$_GET['id']})");
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/lib/prototype.js");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/src/scriptaculous.js");
		$p->open();
		$p->view()->LoadTemplate('view/EditXigroup.html');
		$p->view()->MergeBlock('oUsers',$oUsers);
		$p->view()->Show();
		$p->close();
	} else {
		// Displaying a list of all objects
		$data = $xpress->db()->getAll(
			"SELECT objId FROM `Xigroup` ORDER BY objId DESC");
		$o = array();
		foreach ($data as $d) {
			$a  = array();
			$oe = XigroupFactory::retrieve($d['objId']);
			$a['objId'] = $oe->getobjId();
			$a['Name'] = $oe->getName();
			$a['ObjectClass'] = $oe->getObjectClass();
			$o[] = $a;
		}


		$p = new XPressPage('Model Browser: Xigroup');
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->open();
		$p->view()->LoadTemplate('view/Xigroup.html');
		$p->view()->MergeBlock('obj',$o);
		$p->view()->Show();
		$p->close();
	}

?>