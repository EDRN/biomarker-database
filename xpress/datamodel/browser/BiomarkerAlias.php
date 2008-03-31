<?php


	/* PHP Controller code for BiomarkerAlias object browser */
	require_once('../../../xpress/app.php');


	if (isset($_POST['createObject'])) {
		$o = BiomarkerAliasFactory::create($_POST['BiomarkerId'],$_POST['BiomarkerId']);
		$o->setAlias($_POST['Alias'],false);
		$o->save();
	}

	if (isset($_POST['deleteObject'])) {
		if (false !== ($o = BiomarkerAliasFactory::Retrieve($_POST['id']))) {
			$o->delete();
		}
	}

	if (isset($_GET['id'])) {
		// Working with an individual object
		if (!false == ($o = BiomarkerAliasFactory::retrieve($_GET['id']))) {
			$attr['objId'] = $o->getobjId();
			$attr['Alias'] = $o->getAlias();
			$oBiomarker = $o->getBiomarker();
			$oBiomarker = $o->getBiomarker();
			
		} else {
			XPressPage::httpRedirect('../../../notfound.php');
		}

		$p = new XPressPage("Model Browser: BiomarkerAlias ({$_GET['id']})");
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/lib/prototype.js");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/src/scriptaculous.js");
		$p->open();
		$p->view()->LoadTemplate('view/EditBiomarkerAlias.html');
		$p->view()->Show();
		$p->close();
	} else {
		// Displaying a list of all objects
		$data = $xpress->db()->getAll(
			"SELECT objId FROM `BiomarkerAlias` ORDER BY objId DESC");
		$o = array();
		foreach ($data as $d) {
			$a  = array();
			$oe = BiomarkerAliasFactory::retrieve($d['objId']);
			$a['objId'] = $oe->getobjId();
			$a['Alias'] = $oe->getAlias();
			$o[] = $a;
		}


		$p = new XPressPage('Model Browser: BiomarkerAlias');
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->open();
		$p->view()->LoadTemplate('view/BiomarkerAlias.html');
		$p->view()->MergeBlock('obj',$o);
		$p->view()->Show();
		$p->close();
	}

?>