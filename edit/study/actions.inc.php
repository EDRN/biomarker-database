<?php
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
?>