<?php


require_once("../app.php");


if (isset($_POST['action'])){
	@include_once("AjaxHandlerExtensions.php");
	// UPDATES TO EXISTING FIELDS // 
	if ($_POST['action'] == "update"){
		$type = $_POST['objType'];
		$id   = $_POST['objID'];
		$attr = $_POST['attr'];
		$val  = $_POST['value'];
		switch($type){
			case "Biomarker":
				$obj = BiomarkerFactory::retrieve($id);
				switch ($attr){ 
					case "objId":
						$ovalue = $obj->getobjId();
						$obj->setobjId($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "EKE_ID":
						$ovalue = $obj->getEKE_ID();
						$obj->setEKE_ID($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "BiomarkerID":
						$ovalue = $obj->getBiomarkerID();
						$obj->setBiomarkerID($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "PanelID":
						$ovalue = $obj->getPanelID();
						$obj->setPanelID($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Title":
						$ovalue = $obj->getTitle();
						$obj->setTitle($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "ShortName":
						$ovalue = $obj->getShortName();
						$obj->setShortName($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Description":
						$ovalue = $obj->getDescription();
						$obj->setDescription($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "QAState":
						$ovalue = $obj->getQAState();
						$obj->setQAState($val);
						switch($val){
							case 1:
								echo "New"; break;
							case 2:
								echo "Under Review"; break;
							case 3:
								echo "Approved"; break;
							case 4:
								echo "Rejected"; break;
							default: echo stripslashes($val); break;
						}
						break;
					case "Phase":
						$ovalue = $obj->getPhase();
						$obj->setPhase($val);
						switch($val){
							case 1:
								echo "One (1)"; break;
							case 2:
								echo "Two (2)"; break;
							case 3:
								echo "Three (3)"; break;
							case 4:
								echo "Four (4)"; break;
							case 5:
								echo "Five (5)"; break;
							default: echo stripslashes($val); break;
						}
						break;
					case "Security":
						$ovalue = $obj->getSecurity();
						$obj->setSecurity($val);
						switch($val){
							case 1:
								echo "Public"; break;
							case 2:
								echo "Private"; break;
							default: echo stripslashes($val); break;
						}
						break;
					case "Type":
						$ovalue = $obj->getType();
						$obj->setType($val);
						switch($val){
							case 1:
								echo "Epigenomics"; break;
							case 2:
								echo "Genomics"; break;
							case 3:
								echo "Proteomics"; break;
							case 4:
								echo "Glycomics"; break;
							case 5:
								echo "Nanotechnology"; break;
							case 6:
								echo "Metabalomics"; break;
							case 7:
								echo "Hypermethylation"; break;
							default: echo stripslashes($val); break;
						}
						break;
				}
				break;
			case "BiomarkerAlias":
				$obj = BiomarkerAliasFactory::retrieve($id);
				switch ($attr){ 
					case "objId":
						$ovalue = $obj->getobjId();
						$obj->setobjId($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Alias":
						$ovalue = $obj->getAlias();
						$obj->setAlias($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
				}
				break;
			case "Study":
				$obj = StudyFactory::retrieve($id);
				switch ($attr){ 
					case "objId":
						$ovalue = $obj->getobjId();
						$obj->setobjId($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "EDRNID":
						$ovalue = $obj->getEDRNID();
						$obj->setEDRNID($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "FHCRC_ID":
						$ovalue = $obj->getFHCRC_ID();
						$obj->setFHCRC_ID($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "DMCC_ID":
						$ovalue = $obj->getDMCC_ID();
						$obj->setDMCC_ID($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Title":
						$ovalue = $obj->getTitle();
						$obj->setTitle($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Abstract":
						$ovalue = $obj->getAbstract();
						$obj->setAbstract($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "BiomarkerPopulationCharacteristics":
						$ovalue = $obj->getBiomarkerPopulationCharacteristics();
						$obj->setBiomarkerPopulationCharacteristics($val);
						switch($val){
							case 1:
								echo "Case/Control"; break;
							case 2:
								echo "Longitudinal"; break;
							case 3:
								echo "Randomized"; break;
							default: echo stripslashes($val); break;
						}
						break;
					case "BPCDescription":
						$ovalue = $obj->getBPCDescription();
						$obj->setBPCDescription($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Design":
						$ovalue = $obj->getDesign();
						$obj->setDesign($val);
						switch($val){
							case 1:
								echo "Retrospective"; break;
							case 2:
								echo "Prospective Analysis"; break;
							case 3:
								echo "Cross Sectional"; break;
							default: echo stripslashes($val); break;
						}
						break;
					case "DesignDescription":
						$ovalue = $obj->getDesignDescription();
						$obj->setDesignDescription($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "BiomarkerStudyType":
						$ovalue = $obj->getBiomarkerStudyType();
						$obj->setBiomarkerStudyType($val);
						switch($val){
							case 1:
								echo "Registered"; break;
							case 2:
								echo "Unregistered"; break;
							default: echo stripslashes($val); break;
						}
						break;
				}
				break;
			case "BiomarkerStudyData":
				$obj = BiomarkerStudyDataFactory::retrieve($id);
				switch ($attr){ 
					case "objId":
						$ovalue = $obj->getobjId();
						$obj->setobjId($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Sensitivity":
						$ovalue = $obj->getSensitivity();
						$obj->setSensitivity($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Specificity":
						$ovalue = $obj->getSpecificity();
						$obj->setSpecificity($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "PPV":
						$ovalue = $obj->getPPV();
						$obj->setPPV($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "NPV":
						$ovalue = $obj->getNPV();
						$obj->setNPV($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Assay":
						$ovalue = $obj->getAssay();
						$obj->setAssay($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Technology":
						$ovalue = $obj->getTechnology();
						$obj->setTechnology($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
				}
				break;
			case "Organ":
				$obj = OrganFactory::retrieve($id);
				switch ($attr){ 
					case "objId":
						$ovalue = $obj->getobjId();
						$obj->setobjId($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Name":
						$ovalue = $obj->getName();
						$obj->setName($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
				}
				break;
			case "BiomarkerOrganData":
				$obj = BiomarkerOrganDataFactory::retrieve($id);
				switch ($attr){ 
					case "objId":
						$ovalue = $obj->getobjId();
						$obj->setobjId($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "SensitivityMin":
						$ovalue = $obj->getSensitivityMin();
						$obj->setSensitivityMin($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "SensitivityMax":
						$ovalue = $obj->getSensitivityMax();
						$obj->setSensitivityMax($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "SensitivityComment":
						$ovalue = $obj->getSensitivityComment();
						$obj->setSensitivityComment($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "SpecificityMin":
						$ovalue = $obj->getSpecificityMin();
						$obj->setSpecificityMin($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "SpecificityMax":
						$ovalue = $obj->getSpecificityMax();
						$obj->setSpecificityMax($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "SpecificityComment":
						$ovalue = $obj->getSpecificityComment();
						$obj->setSpecificityComment($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "PPVMin":
						$ovalue = $obj->getPPVMin();
						$obj->setPPVMin($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "PPVMax":
						$ovalue = $obj->getPPVMax();
						$obj->setPPVMax($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "PPVComment":
						$ovalue = $obj->getPPVComment();
						$obj->setPPVComment($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "NPVMin":
						$ovalue = $obj->getNPVMin();
						$obj->setNPVMin($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "NPVMax":
						$ovalue = $obj->getNPVMax();
						$obj->setNPVMax($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "NPVComment":
						$ovalue = $obj->getNPVComment();
						$obj->setNPVComment($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "QAState":
						$ovalue = $obj->getQAState();
						$obj->setQAState($val);
						switch($val){
							case 1:
								echo "New"; break;
							case 2:
								echo "Under Review"; break;
							case 3:
								echo "Approved"; break;
							case 4:
								echo "Rejected"; break;
							default: echo stripslashes($val); break;
						}
						break;
					case "Phase":
						$ovalue = $obj->getPhase();
						$obj->setPhase($val);
						switch($val){
							case 1:
								echo "One (1)"; break;
							case 2:
								echo "Two (2)"; break;
							case 3:
								echo "Three (3)"; break;
							case 4:
								echo "Four (4)"; break;
							case 5:
								echo "Five (5)"; break;
							default: echo stripslashes($val); break;
						}
						break;
					case "Description":
						$ovalue = $obj->getDescription();
						$obj->setDescription($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
				}
				break;
			case "BiomarkerOrganStudyData":
				$obj = BiomarkerOrganStudyDataFactory::retrieve($id);
				switch ($attr){ 
					case "objId":
						$ovalue = $obj->getobjId();
						$obj->setobjId($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Sensitivity":
						$ovalue = $obj->getSensitivity();
						$obj->setSensitivity($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Specificity":
						$ovalue = $obj->getSpecificity();
						$obj->setSpecificity($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "PPV":
						$ovalue = $obj->getPPV();
						$obj->setPPV($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "NPV":
						$ovalue = $obj->getNPV();
						$obj->setNPV($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Assay":
						$ovalue = $obj->getAssay();
						$obj->setAssay($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Technology":
						$ovalue = $obj->getTechnology();
						$obj->setTechnology($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
				}
				break;
			case "Publication":
				$obj = PublicationFactory::retrieve($id);
				switch ($attr){ 
					case "objId":
						$ovalue = $obj->getobjId();
						$obj->setobjId($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "PubMedID":
						$ovalue = $obj->getPubMedID();
						$obj->setPubMedID($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Title":
						$ovalue = $obj->getTitle();
						$obj->setTitle($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Author":
						$ovalue = $obj->getAuthor();
						$obj->setAuthor($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Journal":
						$ovalue = $obj->getJournal();
						$obj->setJournal($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Volume":
						$ovalue = $obj->getVolume();
						$obj->setVolume($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Issue":
						$ovalue = $obj->getIssue();
						$obj->setIssue($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Year":
						$ovalue = $obj->getYear();
						$obj->setYear($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
				}
				break;
			case "Resource":
				$obj = ResourceFactory::retrieve($id);
				switch ($attr){ 
					case "objId":
						$ovalue = $obj->getobjId();
						$obj->setobjId($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Name":
						$ovalue = $obj->getName();
						$obj->setName($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "URL":
						$ovalue = $obj->getURL();
						$obj->setURL($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
				}
				break;
			case "Site":
				$obj = SiteFactory::retrieve($id);
				switch ($attr){ 
					case "objId":
						$ovalue = $obj->getobjId();
						$obj->setobjId($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Name":
						$ovalue = $obj->getName();
						$obj->setName($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
				}
				break;
			case "Person":
				$obj = PersonFactory::retrieve($id);
				switch ($attr){ 
					case "objId":
						$ovalue = $obj->getobjId();
						$obj->setobjId($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "FirstName":
						$ovalue = $obj->getFirstName();
						$obj->setFirstName($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "LastName":
						$ovalue = $obj->getLastName();
						$obj->setLastName($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Title":
						$ovalue = $obj->getTitle();
						$obj->setTitle($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Specialty":
						$ovalue = $obj->getSpecialty();
						$obj->setSpecialty($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Phone":
						$ovalue = $obj->getPhone();
						$obj->setPhone($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Fax":
						$ovalue = $obj->getFax();
						$obj->setFax($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
					case "Email":
						$ovalue = $obj->getEmail();
						$obj->setEmail($val);
						echo stripslashes(htmlspecialchars_decode($val));
						break;
				}
				break;
		}
	} else if ($_POST['action'] == 'associate'){
		// ASSOCIATIONS BETWEEN EXISTING OBJECTS //
		if ($_POST['obj1Type'] == "Biomarker"){
			$localType  = "Biomarker";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "BiomarkerAlias":
					$obj1 = BiomarkerFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"Biomarker\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"BiomarkerAlias\"}}}";
					return;
				case "BiomarkerStudyData":
					$obj1 = BiomarkerFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"Biomarker\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"BiomarkerStudyData\"}}}";
					return;
				case "BiomarkerOrganData":
					$obj1 = BiomarkerFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"Biomarker\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"BiomarkerOrganData\"}}}";
					return;
				case "Publication":
					$obj1 = BiomarkerFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"Biomarker\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"Publication\"}}}";
					return;
				case "Resource":
					$obj1 = BiomarkerFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"Biomarker\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"Resource\"}}}";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "BiomarkerAlias"){
			$localType  = "BiomarkerAlias";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "Biomarker":
					$obj1 = BiomarkerAliasFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"BiomarkerAlias\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"Biomarker\"}}}";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "Study"){
			$localType  = "Study";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "BiomarkerStudyData":
					$obj1 = StudyFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"Study\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"BiomarkerStudyData\"}}}";
					return;
				case "BiomarkerOrganStudyData":
					$obj1 = StudyFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"Study\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"BiomarkerOrganStudyData\"}}}";
					return;
				case "BiomarkerOrganData":
					$obj1 = StudyFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"Study\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"BiomarkerOrganData\"}}}";
					return;
				case "Publication":
					$obj1 = StudyFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"Study\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"Publication\"}}}";
					return;
				case "Resource":
					$obj1 = StudyFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"Study\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"Resource\"}}}";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "BiomarkerStudyData"){
			$localType  = "BiomarkerStudyData";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "Biomarker":
					$obj1 = BiomarkerStudyDataFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"BiomarkerStudyData\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"Biomarker\"}}}";
					return;
				case "Study":
					$obj1 = BiomarkerStudyDataFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"BiomarkerStudyData\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"Study\"}}}";
					return;
				case "Publication":
					$obj1 = BiomarkerStudyDataFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"BiomarkerStudyData\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"Publication\"}}}";
					return;
				case "Resource":
					$obj1 = BiomarkerStudyDataFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"BiomarkerStudyData\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"Resource\"}}}";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "Organ"){
			$localType  = "Organ";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "BiomarkerOrganData":
					$obj1 = OrganFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"Organ\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"BiomarkerOrganData\"}}}";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "BiomarkerOrganData"){
			$localType  = "BiomarkerOrganData";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "Biomarker":
					$obj1 = BiomarkerOrganDataFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"BiomarkerOrganData\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"Biomarker\"}}}";
					return;
				case "Organ":
					$obj1 = BiomarkerOrganDataFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"BiomarkerOrganData\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"Organ\"}}}";
					return;
				case "Resource":
					$obj1 = BiomarkerOrganDataFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"BiomarkerOrganData\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"Resource\"}}}";
					return;
				case "Publication":
					$obj1 = BiomarkerOrganDataFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"BiomarkerOrganData\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"Publication\"}}}";
					return;
				case "BiomarkerOrganStudyData":
					$obj1 = BiomarkerOrganDataFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"BiomarkerOrganData\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"BiomarkerOrganStudyData\"}}}";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "BiomarkerOrganStudyData"){
			$localType  = "BiomarkerOrganStudyData";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "BiomarkerOrganData":
					$obj1 = BiomarkerOrganStudyDataFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"BiomarkerOrganStudyData\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"BiomarkerOrganData\"}}}";
					return;
				case "Study":
					$obj1 = BiomarkerOrganStudyDataFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"BiomarkerOrganStudyData\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"Study\"}}}";
					return;
				case "Publication":
					$obj1 = BiomarkerOrganStudyDataFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"BiomarkerOrganStudyData\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"Publication\"}}}";
					return;
				case "Resource":
					$obj1 = BiomarkerOrganStudyDataFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"BiomarkerOrganStudyData\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"Resource\"}}}";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "Publication"){
			$localType  = "Publication";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "Biomarker":
					$obj1 = PublicationFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"Publication\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"Biomarker\"}}}";
					return;
				case "BiomarkerStudyData":
					$obj1 = PublicationFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"Publication\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"BiomarkerStudyData\"}}}";
					return;
				case "BiomarkerOrganData":
					$obj1 = PublicationFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"Publication\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"BiomarkerOrganData\"}}}";
					return;
				case "BiomarkerOrganStudyData":
					$obj1 = PublicationFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"Publication\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"BiomarkerOrganStudyData\"}}}";
					return;
				case "Study":
					$obj1 = PublicationFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"Publication\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"Study\"}}}";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "Resource"){
			$localType  = "Resource";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "Biomarker":
					$obj1 = ResourceFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"Resource\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"Biomarker\"}}}";
					return;
				case "BiomarkerStudyData":
					$obj1 = ResourceFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"Resource\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"BiomarkerStudyData\"}}}";
					return;
				case "BiomarkerOrganData":
					$obj1 = ResourceFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"Resource\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"BiomarkerOrganData\"}}}";
					return;
				case "BiomarkerOrganStudyData":
					$obj1 = ResourceFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"Resource\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"BiomarkerOrganStudyData\"}}}";
					return;
				case "Study":
					$obj1 = ResourceFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"Resource\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"Study\"}}}";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "Site"){
			$localType  = "Site";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "Person":
					$obj1 = SiteFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"Site\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"Person\"}}}";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "Person"){
			$localType  = "Person";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "Site":
					$obj1 = PersonFactory::retrieve($_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":\"{$_POST['obj1Id']}\", \"obj1Type\":\"Person\", \"obj2Id\":\"{$_POST['obj2Id']}\", \"obj2Type\":\"Site\"}}}";
					return;
				default:break;
			}
		}
	} else if ($_POST['action'] == 'dissociate'){
		// DISSOCIATIONS BETWEEN EXISTING OBJECTS //
		if ($_POST['obj1Type'] == "Biomarker"){
			$localType  = "Biomarker";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "BiomarkerAlias":
					$obj1 = BiomarkerFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "BiomarkerStudyData":
					$obj1 = BiomarkerFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "BiomarkerOrganData":
					$obj1 = BiomarkerFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Publication":
					$obj1 = BiomarkerFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Resource":
					$obj1 = BiomarkerFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "BiomarkerAlias"){
			$localType  = "BiomarkerAlias";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "Biomarker":
					$obj1 = BiomarkerAliasFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "Study"){
			$localType  = "Study";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "BiomarkerStudyData":
					$obj1 = StudyFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "BiomarkerOrganStudyData":
					$obj1 = StudyFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "BiomarkerOrganData":
					$obj1 = StudyFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Publication":
					$obj1 = StudyFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Resource":
					$obj1 = StudyFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "BiomarkerStudyData"){
			$localType  = "BiomarkerStudyData";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "Biomarker":
					$obj1 = BiomarkerStudyDataFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Study":
					$obj1 = BiomarkerStudyDataFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Publication":
					$obj1 = BiomarkerStudyDataFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Resource":
					$obj1 = BiomarkerStudyDataFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "Organ"){
			$localType  = "Organ";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "BiomarkerOrganData":
					$obj1 = OrganFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "BiomarkerOrganData"){
			$localType  = "BiomarkerOrganData";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "Biomarker":
					$obj1 = BiomarkerOrganDataFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Organ":
					$obj1 = BiomarkerOrganDataFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Resource":
					$obj1 = BiomarkerOrganDataFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Publication":
					$obj1 = BiomarkerOrganDataFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "BiomarkerOrganStudyData":
					$obj1 = BiomarkerOrganDataFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "BiomarkerOrganStudyData"){
			$localType  = "BiomarkerOrganStudyData";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "BiomarkerOrganData":
					$obj1 = BiomarkerOrganStudyDataFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Study":
					$obj1 = BiomarkerOrganStudyDataFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Publication":
					$obj1 = BiomarkerOrganStudyDataFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Resource":
					$obj1 = BiomarkerOrganStudyDataFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "Publication"){
			$localType  = "Publication";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "Biomarker":
					$obj1 = PublicationFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "BiomarkerStudyData":
					$obj1 = PublicationFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "BiomarkerOrganData":
					$obj1 = PublicationFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "BiomarkerOrganStudyData":
					$obj1 = PublicationFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Study":
					$obj1 = PublicationFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "Resource"){
			$localType  = "Resource";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "Biomarker":
					$obj1 = ResourceFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "BiomarkerStudyData":
					$obj1 = ResourceFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "BiomarkerOrganData":
					$obj1 = ResourceFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "BiomarkerOrganStudyData":
					$obj1 = ResourceFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Study":
					$obj1 = ResourceFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "Site"){
			$localType  = "Site";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "Person":
					$obj1 = SiteFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "Person"){
			$localType  = "Person";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "Site":
					$obj1 = PersonFactory::retrieve($_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				default:break;
			}
		}
	} else if ($_POST['action'] == 'create'){
		// NEW OBJECT CREATION //
		if ($_POST['objType'] == "Biomarker"){
			$obj = BiomarkerFactory::create($_POST['Title']);
			echo $obj->toJSON();
			return;
		}
		if ($_POST['objType'] == "BiomarkerAlias"){
			$obj = BiomarkerAliasFactory::create($_POST['BiomarkerId'],$_POST['BiomarkerId']);
			echo $obj->toJSON();
			return;
		}
		if ($_POST['objType'] == "Study"){
			$obj = StudyFactory::create($_POST['Title']);
			echo $obj->toJSON();
			return;
		}
		if ($_POST['objType'] == "BiomarkerStudyData"){
			$obj = BiomarkerStudyDataFactory::create($_POST['StudyId'],$_POST['BiomarkerId'],$_POST['StudyId'],$_POST['BiomarkerId']);
			echo $obj->toJSON();
			return;
		}
		if ($_POST['objType'] == "Organ"){
			$obj = OrganFactory::create($_POST['Name']);
			echo $obj->toJSON();
			return;
		}
		if ($_POST['objType'] == "BiomarkerOrganData"){
			$obj = BiomarkerOrganDataFactory::create($_POST['OrganId'],$_POST['BiomarkerId'],$_POST['OrganId'],$_POST['BiomarkerId']);
			echo $obj->toJSON();
			return;
		}
		if ($_POST['objType'] == "BiomarkerOrganStudyData"){
			$obj = BiomarkerOrganStudyDataFactory::create($_POST['StudyId'],$_POST['BiomarkerOrganDataId'],$_POST['StudyId'],$_POST['BiomarkerOrganDataId']);
			echo $obj->toJSON();
			return;
		}
		if ($_POST['objType'] == "Publication"){
			$obj = PublicationFactory::create($_POST['PubMedID']);
			echo $obj->toJSON();
			return;
		}
		if ($_POST['objType'] == "Resource"){
			$obj = ResourceFactory::create();
			echo $obj->toJSON();
			return;
		}
		if ($_POST['objType'] == "Site"){
			$obj = SiteFactory::create();
			echo $obj->toJSON();
			return;
		}
		if ($_POST['objType'] == "Person"){
			$obj = PersonFactory::create();
			echo $obj->toJSON();
			return;
		}
	} else if ($_POST['action'] == 'delete'){
		// OBJECT DELETION //
		if ($_POST['objType'] == "Biomarker"){
			$obj = BiomarkerFactory::retrieve($_POST['objId'],XPress::FETCH_NONE);
			$obj->delete();
			echo "Biomarker Deleted.";
			return;
		}
		if ($_POST['objType'] == "BiomarkerAlias"){
			$obj = BiomarkerAliasFactory::retrieve($_POST['objId'],XPress::FETCH_NONE);
			$obj->delete();
			echo "BiomarkerAlias Deleted.";
			return;
		}
		if ($_POST['objType'] == "Study"){
			$obj = StudyFactory::retrieve($_POST['objId'],XPress::FETCH_NONE);
			$obj->delete();
			echo "Study Deleted.";
			return;
		}
		if ($_POST['objType'] == "BiomarkerStudyData"){
			$obj = BiomarkerStudyDataFactory::retrieve($_POST['objId'],XPress::FETCH_NONE);
			$obj->delete();
			echo "BiomarkerStudyData Deleted.";
			return;
		}
		if ($_POST['objType'] == "Organ"){
			$obj = OrganFactory::retrieve($_POST['objId'],XPress::FETCH_NONE);
			$obj->delete();
			echo "Organ Deleted.";
			return;
		}
		if ($_POST['objType'] == "BiomarkerOrganData"){
			$obj = BiomarkerOrganDataFactory::retrieve($_POST['objId'],XPress::FETCH_NONE);
			$obj->delete();
			echo "BiomarkerOrganData Deleted.";
			return;
		}
		if ($_POST['objType'] == "BiomarkerOrganStudyData"){
			$obj = BiomarkerOrganStudyDataFactory::retrieve($_POST['objId'],XPress::FETCH_NONE);
			$obj->delete();
			echo "BiomarkerOrganStudyData Deleted.";
			return;
		}
		if ($_POST['objType'] == "Publication"){
			$obj = PublicationFactory::retrieve($_POST['objId'],XPress::FETCH_NONE);
			$obj->delete();
			echo "Publication Deleted.";
			return;
		}
		if ($_POST['objType'] == "Resource"){
			$obj = ResourceFactory::retrieve($_POST['objId'],XPress::FETCH_NONE);
			$obj->delete();
			echo "Resource Deleted.";
			return;
		}
		if ($_POST['objType'] == "Site"){
			$obj = SiteFactory::retrieve($_POST['objId'],XPress::FETCH_NONE);
			$obj->delete();
			echo "Site Deleted.";
			return;
		}
		if ($_POST['objType'] == "Person"){
			$obj = PersonFactory::retrieve($_POST['objId'],XPress::FETCH_NONE);
			$obj->delete();
			echo "Person Deleted.";
			return;
		}
	}
}?>