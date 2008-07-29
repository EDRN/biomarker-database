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
	
	// BiomarkerOrgan::Associate Study
	if (isset($_POST['associate_study'])) {
		$bId = $_POST['biomarkerorgan_id'];
		$sId = $_POST['study_id'];
		try {
			$bs = BiomarkerOrganStudyDataFactory::Create($sId,$bId);
			XPressPage::httpRedirect("./?view=studies&id={$bId}");
		} catch (XPressException $e){
			echo $e->getFormattedMessage();
		}
	}
	
	// BiomarkerOrgan::Dissociate Study
	if (isset($_GET['remove_study'])) {
		$bId  = $_GET['id'];
		$bsId = $_GET['remove_study'];
		try {
			$bs = BiomarkerOrganStudyDataFactory::Retrieve($bsId);
			$bs->delete();
			XPressPage::httpRedirect("./?view=studies&id={$bId}");
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
	
	// Special actions for the studies page
	if (isset($_POST['action'])) {
	
		// Associate a publication with a biomarker-organ-study-data object
		if($_POST['action'] == 'study_associate_publication') {
			$bosdId= $_POST['bosdId'];
			$pubId = $_POST['pubId'];
			if (false == ($bosd = BiomarkerOrganStudyDataFactory::Retrieve($bosdId))) {
				// bosd did not exist;
				echo "error";
				exit();
			}
			if (false == ($pub = PublicationFactory::Retrieve($pubId))) {
				// publication did not exist
				echo "error";
				exit();
			}
			$bosd->link(BiomarkerOrganStudyDataVars::PUBLICATIONS,
				$pubId,PublicationVars::BIOMARKERORGANSTUDIES);
				
			// echo an HTML representation of the entry
			echo <<<END
<li id="s{$bosdId}p{$pubId}" style="margin-bottom:15px;"><a href="../../goto/publication/?id={$pubId}">{$pub->getTitle()}</a><br/>
<span class="hint" style="color:#888;">Author: {$pub->getAuthor()}. Published in {$pub->getYear()} in: {$pub->getJournal()} (volume {$pub->getVolume()}, issue {$pub->getIssue()}</span> &nbsp;
<span class="hint">[<span class="pseudolink" onclick="if(confirm('Publication \'{$pub->getTitle()}\' will no longer be associated here. Proceed?')){dissocPub({$bosdId},{$pubId},'s{$bosdId}p{$pubId}');}">Remove Association</span>]</span><br/>
</li>
END;
			exit();
		}
		if ($_POST['action'] == 'study_dissociate_publication') {
			$bosdId= $_POST['bosdId'];
			$pubId = $_POST['pubId'];
			if (false == ($bosd = BiomarkerOrganStudyDataFactory::Retrieve($bosdId))) {
				// bosd did not exist;
				echo "error";
				exit();
			}
			if (false == ($pub = PublicationFactory::Retrieve($pubId))) {
				// publication did not exist
				echo "error";
				exit();
			}
			$bosd->unlink(BiomarkerOrganStudyDataVars::PUBLICATIONS,
				$pubId);
			$pub->unlink(PublicationVars::BIOMARKERORGANSTUDIES,$bosdId);
			
			echo "ok";
			exit();
		}
		
		if ($_POST['action'] == 'study_associate_resource') {
			$bosdId = $_POST['bosdId'];
			$url    = $_POST['rurl'];
			$desc   = $_POST['rdesc'];
			$r = ResourceFactory::Create();
			$r->setURL($url);
			$r->setName($desc);
			// Link to biomarker-organ-study-data
			if (false == ($bosd = BiomarkerOrganStudyDataFactory::Retrieve($bosdId))) {
				// bosd did not exist
				echo "error";
				exit();
			}
			$bosd->link(BiomarkerOrganStudyDataVars::RESOURCES,
				$r->getObjId(),ResourceVars::BIOMARKERORGANSTUDIES);
			
			echo <<<END
<li id="s{$bosdId}r{$r->getObjId()}" style="margin-bottom:15px;"><a href="{$url}">{$desc}</a><br/>
<span class="hint" style="color:#888;">{$url}</span>
<span class="hint">[<span class="pseudolink" onclick="if(confirm('Resource \'{$url}\' will be deleted. Proceed?')){dissocRes({$bosdId},{$r->getObjId()},'s{$bosdId}r{$r->getObjId()}');}">Remove Resource</a>]</span></li>
END;
			exit();
		}
		
		if ($_POST['action'] == 'study_dissociate_resource') {
			$bosdId = $_POST['bosdId'];
			$resId  = $_POST['resId'];
			// Unlink if both pieces exist
			if (false == ($bosd = BiomarkerOrganStudyDataFactory::Retrieve($bosdId))) {
				// bosd did not exist
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