<?php
/**
 * 	EDRN Biomarker Database
 *  Curation Webapp
 * 
 *  Author: Andrew F. Hart (andrew.f.hart@jpl.nasa.gov)
 *  
 *  Copyright (c) 2008, California Institute of Technology. 
 *  ALL RIGHTS RESERVED. U.S. Government sponsorship acknowledged.
 * 
 */
	// Study::Associate Publication
	if (isset($_POST['associate_publication'])) {
		$sId = $_POST['study_id'];
		$pId = $_POST['publication_id'];
		try {
			$s = StudyFactory::Retrieve($sId);
			$s->link(StudyVars::PUBLICATIONS,$pId,PublicationVars::STUDIES);
			XPressPage::httpRedirect("./?view=publications&id={$sId}");
		} catch (XPressException $e) {
			echo $e->getFormattedMessage();
		}
	}

	// Study::Dissociate Publication
	if (isset($_GET['remove_publication'])) {
		$sId  = $_GET['id'];
		$pId  = $_GET['remove_publication'];
		try {
			$s = StudyFactory::Retrieve($sId);
			$s->unlink(StudyVars::PUBLICATIONS,$pId);
			XPressPage::httpRedirect("./?view=publications&id={$sId}");
		} catch (XPressException $e) {
			echo $e->getFormattedMessage();
		}
	}
	
	// Study::Associate Resource 
	if (isset($_POST['associate_resource'])) {
		$sId = $_POST['study_id'];
		try {
			$s = StudyFactory::Retrieve($sId);
			$r = ResourceFactory::Create();
			$r->setURL($_POST['url']);
			$r->setName($_POST['description']);
			$s->link(StudyVars::RESOURCES,$r->getObjId(),ResourceVars::STUDIES);
			XPressPage::httpRedirect("./?view=resources&id={$sId}");
		} catch (XPressException $e) {
			echo $e->getFormattedMessage();
		}
	}
	
	// Study::Remove Resource 
	if (isset($_GET['remove_resource'])) {
		$sId = $_GET['id'];
		$rId = $_GET['remove_resource'];
		try {
			$r = ResourceFactory::Retrieve($rId);
			$r->delete();
			XPressPage::httpRedirect("./?view=resources&id={$sId}");
		} catch (XPressException $e) {
			echo $e->getFormattedMessage();
		}
	}
?>