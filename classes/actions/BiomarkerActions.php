<?php
require_once(BMDB_ROOT."/classes/model/Biomarker.php");
require_once(BMDB_ROOT."/classes/model/Biomarker-Alias.php");
require_once(BMDB_ROOT."/classes/model/Biomarker-Study.php");
require_once(BMDB_ROOT."/classes/model/Study.php");

// Create
function createBiomarker($title,&$conn) {
	$marker = new vo_Biomarker();
	$marker->Title = $title;
	$markerdao = new dao_Biomarker(&$conn);
	$markerdao->save(&$marker);	// Inserts the marker
	return $marker->ID;
}

// Delete
function deleteBiomarker($id,&$conn) {
	$markerdao = new dao_Biomarker(&$conn);
	$markers = $markerdao->getBy("ID",$id);
	if (sizeof($markers == 1)){
		$markerdao->delete(&$markers[0]);	// Deletes the marker
	}
}

// Associate a Biomarker and a Study
function associateStudy($bmID,$sID,&$conn){
	$bsvo = new vo_Biomarker_Study();
	$bsvo->BiomarkerID = $bmID;
	$bsvo->StudyID = $sID;
	$bsdao = new dao_Biomarker_Study(&$conn);
	$bsdao->save(&$bsvo);	// Inserts the association
}

// Disassociate a Biomarker and a Study
function disassociateStudy($bmID,$sID,&$conn){
	$bsvo = new vo_Biomarker_Study();
	$bsvo->BiomarkerID = $bmID;
	$bsvo->StudyID = $sID;
	$bsdao = new dao_Biomarker_Study(&$conn);
	$bsdao->delete(&$bsvo);	// Deletes the association
}

// Add an Alias for a biomarker
function addAlias($bmID,$alias,&$conn){
	$bavo = new vo_Biomarker_Alias();
	$bavo->BiomarkerID = $bmID;
	$bavo->Alias = $alias;
	$badao = new dao_Biomarker_Alias(&$conn);
	$badao->save(&$bavo);	// Inserts the alias
}

// Remove an alias for a biomarker
function removeAlias($bmID,$alias,&$conn){
	$bavo = new vo_Biomarker_Alias();
	$bavo->BiomarkerID = $bmID;
	$bavo->Alias = $alias;
	$badao = new dao_Biomarker_Alias(&$conn);
	$badao->delete(&$bavo);	// Deletes the Alias	
}

function loadStudies($bmID,&$conn){
	$biomarkerstudies = array();
	$bsIDs = array();
	$studies = array();
	
	// Get Biomarker-Study Objects
	try {
		$bsdao = new dao_Biomarker_Study(&$conn);
		$biomarkerstudies = $bsdao->getBy("BiomarkerID",$bmID);
	} catch (NoSuchBiomarker_StudyException $e){
		// silently ignore
	}
	// Extract StudyIDs from Biomarker-Study objects
	foreach ($biomarkerstudies as $study){
		$bsIDs[] = $study->StudyID;	
	}
	// Get matching Study objects for the set of Biomarker-Study IDs
	$studydao = new dao_Study(&$conn);
	$studies = $studydao->getSubset("ID",$bsIDs);
	return $studies;
}

function loadAliases($bmID,&$conn){
	$aliases = array();
	try {
		$badao = new dao_Biomarker_Alias(&$conn);
		$aliases = $badao->getBy("BiomarkerID",$bmID);
	} catch (NoSuchBiomarker_AliasException $e){
		// silently ignore
	}
	return $aliases;
}
?>