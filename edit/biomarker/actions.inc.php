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
	
	// Biomarker::Associate Publication
	if (isset($_POST['associate_publication'])) {
		$bId = $_POST['biomarker_id'];
		$pId = $_POST['publication_id'];
		try {
			$b = BiomarkerFactory::Retrieve($bId);
			$b->link(BiomarkerVars::PUBLICATIONS,$pId,PublicationVars::BIOMARKERS);
			XPressPage::httpRedirect("./?view=publications&id={$bId}");
		} catch (XPressException $e) {
			echo $e->getFormattedMessage();
		}
	}

	// Biomarker::Dissociate Publication
	if (isset($_GET['remove_publication'])) {
		$bId  = $_GET['id'];
		$pId  = $_GET['remove_publication'];
		try {
			$b = BiomarkerFactory::Retrieve($bId);
			$b->unlink(BiomarkerVars::PUBLICATIONS,$pId);
			XPressPage::httpRedirect("./?view=publications&id={$bId}");
		} catch (XPressException $e) {
			echo $e->getFormattedMessage();
		}
	}
	
	// Biomarker::Associate Resource 
	if (isset($_POST['associate_resource'])) {
		$bId = $_POST['biomarker_id'];
		try {
			$b = BiomarkerFactory::Retrieve($bId);
			$r = ResourceFactory::Create();
			$r->setURL($_POST['url']);
			$r->setName($_POST['description']);
			$b->link(BiomarkerVars::RESOURCES,$r->getObjId(),ResourceVars::BIOMARKERS);
			XPressPage::httpRedirect("./?view=resources&id={$bId}");
		} catch (XPressException $e) {
			echo $e->getFormattedMessage();
		}
	}
	
	// Biomarker::Remove Resource 
	if (isset($_GET['remove_resource'])) {
		$bId = $_GET['id'];
		$rId = $_GET['remove_resource'];
		try {
			$r = ResourceFactory::Retrieve($rId);
			$r->delete();
			XPressPage::httpRedirect("./?view=resources&id={$bId}");
		} catch (XPressException $e) {
			echo $e->getFormattedMessage();
		}
	}
	
	// Special actions for the studies page
	if (isset($_POST['action'])) {
	
		// Associate a publication with a biomarker-study-data object
		if($_POST['action'] == 'study_associate_publication') {
			$bsdId= $_POST['bsdId'];
			$pubId = $_POST['pubId'];
			if (false == ($bsd = BiomarkerStudyDataFactory::Retrieve($bsdId))) {
				// bsd did not exist;
				echo "error";
				exit();
			}
			if (false == ($pub = PublicationFactory::Retrieve($pubId))) {
				// publication did not exist
				echo "error";
				exit();
			}
			$bsd->link(BiomarkerStudyDataVars::PUBLICATIONS,
				$pubId,PublicationVars::BIOMARKERSTUDIES);
				
			// echo an HTML representation of the entry
			echo <<<END
<li id="s{$bsdId}p{$pubId}" style="margin-bottom:15px;"><a href="../publication/?id={$pubId}">{$pub->getTitle()}</a><br/>
<span class="hint" style="color:#888;">Author: {$pub->getAuthor()}. Published in {$pub->getYear()} in: {$pub->getJournal()} (volume {$pub->getVolume()}, issue {$pub->getIssue()}</span> &nbsp;
<span class="hint">[<span class="pseudolink" onclick="if(confirm('Publication \'{$pub->getTitle()}\' will no longer be associated here. Proceed?')){dissocPub({$bsdId},{$pubId},'s{$bsdId}p{$pubId}');}">Remove Association</span>]</span><br/>
</li>
END;
			exit();
		}
		if ($_POST['action'] == 'study_dissociate_publication') {
			$bsdId= $_POST['bsdId'];
			$pubId = $_POST['pubId'];
			if (false == ($bsd = BiomarkerStudyDataFactory::Retrieve($bsdId))) {
				// bsd did not exist;
				echo "error";
				exit();
			}
			if (false == ($pub = PublicationFactory::Retrieve($pubId))) {
				// publication did not exist
				echo "error";
				exit();
			}
			$bsd->unlink(BiomarkerStudyDataVars::PUBLICATIONS,
				$pubId);
			$pub->unlink(PublicationVars::BIOMARKERSTUDIES,$bsdId);
			
			echo "ok";
			exit();
		}
		
		if ($_POST['action'] == 'study_associate_resource') {
			$bsdId = $_POST['bsdId'];
			$url    = $_POST['rurl'];
			$desc   = $_POST['rdesc'];
			$r = ResourceFactory::Create();
			$r->setURL($url);
			$r->setName($desc);
			// Link to biomarker-organ-study-data
			if (false == ($bsd = BiomarkerStudyDataFactory::Retrieve($bsdId))) {
				// bsd did not exist
				echo "error";
				exit();
			}
			$bsd->link(BiomarkerStudyDataVars::RESOURCES,
				$r->getObjId(),ResourceVars::BIOMARKERSTUDIES);
			
			echo <<<END
<li id="s{$bsdId}r{$r->getObjId()}" style="margin-bottom:15px;"><a href="{$url}">{$desc}</a><br/>
<span class="hint" style="color:#888;">{$url}</span>
<span class="hint">[<span class="pseudolink" onclick="if(confirm('Resource \'{$url}\' will be deleted. Proceed?')){dissocRes({$bsdId},{$r->getObjId()},'s{$bsdId}r{$r->getObjId()}');}">Remove Resource</a>]</span></li>
END;
			exit();
		}
		
		if ($_POST['action'] == 'study_dissociate_resource') {
			$bsdId = $_POST['bsdId'];
			$resId  = $_POST['resId'];
			// Unlink if both pieces exist
			if (false == ($bsd = BiomarkerStudyDataFactory::Retrieve($bsdId))) {
				// bsd did not exist
				echo "error";
				exit();
			}
			if (false == ($res = ResourceFactory::Retrieve($resId))) {
				// resource did not exist
				echo "error";
				exit();
			}
			$res->delete();
			echo "ok";
			exit();
		}
	}
?>