<?php
	// BiomarkerOrgan::Associate Publication
	if (isset($_POST['associate_publication'])) {
		$boId = $_POST['biomarkerorgan_id'];
		$pId = $_POST['publication_id'];
		try {
			$bo = BiomarkerOrganDataFactory::Retrieve($boId);
			$bo->link(BiomarkerOrganDataVars::PUBLICATIONS,$pId,PublicationVars::BIOMARKERORGANS);
			XPressPage::httpRedirect("./?view=publications&id={$boId}");
		} catch (XPressException $e) {
			echo $e->getFormattedMessage();
		}
	}

	// BiomarkerOrgan::Dissociate Publication
	if (isset($_GET['remove_publication'])) {
		$boId  = $_GET['id'];
		$pId  = $_GET['remove_publication'];
		try {
			$bo = BiomarkerOrganDataFactory::Retrieve($boId);
			$bo->unlink(BiomarkerOrganDataVars::PUBLICATIONS,$pId);
			XPressPage::httpRedirect("./?view=publications&id={$boId}");
		} catch (XPressException $e) {
			echo $e->getFormattedMessage();
		}
	}
	
	// BiomarkerOrgan::Associate Resource 
	if (isset($_POST['associate_resource'])) {
		$boId = $_POST['biomarkerorgan_id'];
		try {
			$bo = BiomarkerOrganDataFactory::Retrieve($boId);
			$r = ResourceFactory::Create();
			$r->setURL($_POST['url']);
			$r->setName($_POST['description']);
			$bo->link(BiomarkerOrganDataVars::RESOURCES,$r->getObjId(),ResourceVars::BIOMARKERORGANS);
			XPressPage::httpRedirect("./?view=resources&id={$boId}");
		} catch (XPressException $e) {
			echo $e->getFormattedMessage();
		}
	}
	
	// BiomarkerOrgan::Remove Resource 
	if (isset($_GET['remove_resource'])) {
		$boId = $_GET['id'];
		$rId = $_GET['remove_resource'];
		try {
			$r = ResourceFactory::Retrieve($rId);
			$r->delete();
			XPressPage::httpRedirect("./?view=resources&id={$boId}");
		} catch (XPressException $e) {
			echo $e->getFormattedMessage();
		}
	}

?>