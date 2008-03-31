<?php
	// GET or POST actions for Biomarker objects


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