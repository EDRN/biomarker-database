<?php
	// GET or POST actions for Biomarker objects

	// Biomarker::Associate Organ 
	if (isset($_POST['associate_organ'])) {
		$bId = $_POST['biomarker_id'];
		$oId = $_POST['organ_id'];
		try {
			$bo = BiomarkerOrganDataFactory::Create($oId,$bId);
			XPressPage::httpRedirect("./?view=organs&id={$bId}");
		} catch (XPressException $e) {
			echo $e->getFormattedMessage();
		}
	}
	
	// Biomarker::Dissociate Organ
	if (isset($_GET['remove_organ'])) {
		$bId  = $_GET['id'];
		$boId = $_GET['remove_organ'];
		try {
			 $bo = BiomarkerOrganDataFactory::Retrieve($boId);
			 $bo->delete();
			 XPressPage::redirect("./?view=organs&id={$bId}");
		} catch (XPressException $e) {
			echo $e->getFormattedMessage();
		}
		
	}


	// Biomarker::Associate Study
	if (isset($_POST['associate_study'])) {
		$bId = $_POST['biomarker_id'];
		$sId = $_POST['study_id'];
		try {
			$bs = BiomarkerStudyDataFactory::Create($sId,$bId);
			XPressPage::httpRedirect("./?view=studies&id={$bId}");
		} catch (XPressException $e){
			echo $e->getFormattedMessage();
		}
	}
	// Biomarker::Dissociate Study
	if (isset($_GET['remove_study'])) {
		$bId  = $_GET['id'];
		$bsId = $_GET['remove_study'];
		try {
			$bs = BiomarkerStudyDataFactory::Retrieve($bsId);
			$bs->delete();
			XPressPage::httpRedirect("./?view=studies&id={$bId}");
		} catch (XPressException $e) {
			echo $e->getFormattedMessage();
		}
	}
?>