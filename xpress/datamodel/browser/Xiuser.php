<?php


	/* PHP Controller code for Xiuser object browser */
	require_once('../../../xpress/app.php');


	if (isset($_POST['createObject'])) {
		$o = XiuserFactory::create($_POST['UserName'],$_POST['EmailAddress']);
		$o->setObjectClass($_POST['ObjectClass'],false);
		$o->setObjectId($_POST['ObjectId'],false);
		$o->setUserName($_POST['UserName'],false);
		$o->setPassword($_POST['Password'],false);
		$o->setEmailAddress($_POST['EmailAddress'],false);
		$o->setCreated($_POST['Created'],false);
		$o->setStatus($_POST['Status'],false);
		$o->setLastLogin($_POST['LastLogin'],false);
		$o->save();
	}

	if (isset($_POST['deleteObject'])) {
		if (false !== ($o = XiuserFactory::Retrieve($_POST['id']))) {
			$o->delete();
		}
	}

	if (isset($_GET['id'])) {
		// Working with an individual object
		if (!false == ($o = XiuserFactory::retrieve($_GET['id']))) {
			$attr['objId'] = $o->getobjId();
			$attr['ObjectClass'] = $o->getObjectClass();
			$attr['ObjectId'] = $o->getObjectId();
			$attr['UserName'] = $o->getUserName();
			$attr['Password'] = $o->getPassword();
			$attr['EmailAddress'] = $o->getEmailAddress();
			$attr['Created'] = $o->getCreated();
			$attr['Status'] = $o->getStatus();
			$attr['LastLogin'] = $o->getLastLogin();
			$oGroups = $o->getGroups();
			$oRoles = $o->getRoles();
			
		} else {
			XPressPage::httpRedirect('../../../notfound.php');
		}

		$p = new XPressPage("Model Browser: Xiuser ({$_GET['id']})");
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/lib/prototype.js");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/src/scriptaculous.js");
		$p->open();
		$p->view()->LoadTemplate('view/EditXiuser.html');
		$p->view()->MergeBlock('oGroups',$oGroups);
		$p->view()->MergeBlock('oRoles',$oRoles);
		$p->view()->Show();
		$p->close();
	} else {
		// Displaying a list of all objects
		$data = $xpress->db()->getAll(
			"SELECT objId FROM `Xiuser` ORDER BY objId DESC");
		$o = array();
		foreach ($data as $d) {
			$a  = array();
			$oe = XiuserFactory::retrieve($d['objId']);
			$a['objId'] = $oe->getobjId();
			$a['ObjectClass'] = $oe->getObjectClass();
			$a['ObjectId'] = $oe->getObjectId();
			$a['UserName'] = $oe->getUserName();
			$a['Password'] = $oe->getPassword();
			$a['EmailAddress'] = $oe->getEmailAddress();
			$a['Created'] = $oe->getCreated();
			$a['Status'] = $oe->getStatus();
			$a['LastLogin'] = $oe->getLastLogin();
			$o[] = $a;
		}


		$p = new XPressPage('Model Browser: Xiuser');
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->open();
		$p->view()->LoadTemplate('view/Xiuser.html');
		$p->view()->MergeBlock('obj',$o);
		$p->view()->Show();
		$p->close();
	}

?>