<?php


	/* PHP Controller code for Person object browser */
	require_once('../../../xpress/app.php');


	if (isset($_POST['createObject'])) {
		$o = PersonFactory::create();
		$o->setFirstName($_POST['FirstName'],false);
		$o->setLastName($_POST['LastName'],false);
		$o->setTitle($_POST['Title'],false);
		$o->setSpecialty($_POST['Specialty'],false);
		$o->setPhone($_POST['Phone'],false);
		$o->setFax($_POST['Fax'],false);
		$o->setEmail($_POST['Email'],false);
		$o->save();
	}

	if (isset($_POST['deleteObject'])) {
		if (false !== ($o = PersonFactory::Retrieve($_POST['id']))) {
			$o->delete();
		}
	}

	if (isset($_GET['id'])) {
		// Working with an individual object
		if (!false == ($o = PersonFactory::retrieve($_GET['id']))) {
			$attr['objId'] = $o->getobjId();
			$attr['FirstName'] = $o->getFirstName();
			$attr['LastName'] = $o->getLastName();
			$attr['Title'] = $o->getTitle();
			$attr['Specialty'] = $o->getSpecialty();
			$attr['Phone'] = $o->getPhone();
			$attr['Fax'] = $o->getFax();
			$attr['Email'] = $o->getEmail();
			$oSite = $o->getSite();
			$oSite = $o->getSite();
			
		} else {
			XPressPage::httpRedirect('../../../notfound.php');
		}

		$p = new XPressPage("Model Browser: Person ({$_GET['id']})");
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/lib/prototype.js");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/src/scriptaculous.js");
		$p->open();
		$p->view()->LoadTemplate('view/EditPerson.html');
		$p->view()->MergeBlock('oSite',$oSite);
		$p->view()->MergeBlock('oSite',$oSite);
		$p->view()->Show();
		$p->close();
	} else {
		// Displaying a list of all objects
		$data = $xpress->db()->getAll(
			"SELECT objId FROM `Person` ORDER BY objId DESC");
		$o = array();
		foreach ($data as $d) {
			$a  = array();
			$oe = PersonFactory::retrieve($d['objId']);
			$a['objId'] = $oe->getobjId();
			$a['FirstName'] = $oe->getFirstName();
			$a['LastName'] = $oe->getLastName();
			$a['Title'] = $oe->getTitle();
			$a['Specialty'] = $oe->getSpecialty();
			$a['Phone'] = $oe->getPhone();
			$a['Fax'] = $oe->getFax();
			$a['Email'] = $oe->getEmail();
			$o[] = $a;
		}


		$p = new XPressPage('Model Browser: Person');
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->open();
		$p->view()->LoadTemplate('view/Person.html');
		$p->view()->MergeBlock('obj',$o);
		$p->view()->Show();
		$p->close();
	}

?>