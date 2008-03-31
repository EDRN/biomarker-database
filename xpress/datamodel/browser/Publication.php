<?php


	/* PHP Controller code for Publication object browser */
	require_once('../../../xpress/app.php');


	if (isset($_POST['createObject'])) {
		$o = PublicationFactory::create($_POST['PubMedID']);
		$o->setPubMedID($_POST['PubMedID'],false);
		$o->setTitle($_POST['Title'],false);
		$o->setAuthor($_POST['Author'],false);
		$o->setJournal($_POST['Journal'],false);
		$o->setVolume($_POST['Volume'],false);
		$o->setIssue($_POST['Issue'],false);
		$o->setYear($_POST['Year'],false);
		$o->save();
	}

	if (isset($_POST['deleteObject'])) {
		if (false !== ($o = PublicationFactory::Retrieve($_POST['id']))) {
			$o->delete();
		}
	}

	if (isset($_GET['id'])) {
		// Working with an individual object
		if (!false == ($o = PublicationFactory::retrieve($_GET['id']))) {
			$attr['objId'] = $o->getobjId();
			$attr['PubMedID'] = $o->getPubMedID();
			$attr['Title'] = $o->getTitle();
			$attr['Author'] = $o->getAuthor();
			$attr['Journal'] = $o->getJournal();
			$attr['Volume'] = $o->getVolume();
			$attr['Issue'] = $o->getIssue();
			$attr['Year'] = $o->getYear();
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

		$p = new XPressPage("Model Browser: Publication ({$_GET['id']})");
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/lib/prototype.js");
		$p->includeJS("../../static/js/lib/scriptaculous-js-1.7.0/src/scriptaculous.js");
		$p->open();
		$p->view()->LoadTemplate('view/EditPublication.html');
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
			"SELECT objId FROM `Publication` ORDER BY objId DESC");
		$o = array();
		foreach ($data as $d) {
			$a  = array();
			$oe = PublicationFactory::retrieve($d['objId']);
			$a['objId'] = $oe->getobjId();
			$a['PubMedID'] = $oe->getPubMedID();
			$a['Title'] = $oe->getTitle();
			$a['Author'] = $oe->getAuthor();
			$a['Journal'] = $oe->getJournal();
			$a['Volume'] = $oe->getVolume();
			$a['Issue'] = $oe->getIssue();
			$a['Year'] = $oe->getYear();
			$o[] = $a;
		}


		$p = new XPressPage('Model Browser: Publication');
		$p->includeCSS("../../static/css/xpress.css");
		$p->includeCSS("../../static/css/browser.css");
		$p->open();
		$p->view()->LoadTemplate('view/Publication.html');
		$p->view()->MergeBlock('obj',$o);
		$p->view()->Show();
		$p->close();
	}

?>