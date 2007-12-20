<?php
require_once("ModelProperties.inc.php");


if (isset($_POST['action'])){
	include_once("AjaxHandlerExtensions.php");
	// UPDATES TO EXISTING FIELDS // 
	if ($_POST['action'] == "update"){
		$type = $_POST['objType'];
		$id   = $_POST['objID'];
		$attr = $_POST['attr'];
		$val  = $_POST['value'];
		switch($type){
			case "Biomarker":
				$obj = new objBiomarker($XPress);
				$obj->initialize($id,false); // don't retrieve relationships
				switch ($attr){ 
					case "objId":
						$ovalue = $obj->getobjId();
						$obj->setobjId($val);
						echo $val;
						break;
					case "EKE_ID":
						$ovalue = $obj->getEKE_ID();
						$obj->setEKE_ID($val);
						echo $val;
						break;
					case "BiomarkerID":
						$ovalue = $obj->getBiomarkerID();
						$obj->setBiomarkerID($val);
						echo $val;
						break;
					case "PanelID":
						$ovalue = $obj->getPanelID();
						$obj->setPanelID($val);
						echo $val;
						break;
					case "Title":
						$ovalue = $obj->getTitle();
						$obj->setTitle($val);
						echo $val;
						break;
					case "Description":
						$ovalue = $obj->getDescription();
						$obj->setDescription($val);
						echo $val;
						break;
					case "QAState":
						$ovalue = $obj->getQAState();
						$obj->setQAState($val);
						echo $val;
						break;
					case "Phase":
						$ovalue = $obj->getPhase();
						$obj->setPhase($val);
						switch($val){
							case 1:
								echo "One (I)"; break;
							case 2:
								echo "Two (II)"; break;
							case 3:
								echo "Three (III)"; break;
							case 4:
								echo "Four (IV)"; break;
							case 5:
								echo "Five (V)"; break;
							default: echo $val; break;
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
							default: echo $val; break;
						}
						break;
					case "Type":
						$ovalue = $obj->getType();
						$obj->setType($val);
						switch($val){
							case 1:
								echo "Epigenetic"; break;
							case 2:
								echo "Gene"; break;
							case 3:
								echo "Protein"; break;
							default: echo $val; break;
						}
						break;
				}
				break;
			case "BiomarkerAlias":
				$obj = new objBiomarkerAlias($XPress);
				$obj->initialize($id,false); // don't retrieve relationships
				switch ($attr){ 
					case "objId":
						$ovalue = $obj->getobjId();
						$obj->setobjId($val);
						echo $val;
						break;
					case "Alias":
						$ovalue = $obj->getAlias();
						$obj->setAlias($val);
						echo $val;
						break;
				}
				break;
			case "Study":
				$obj = new objStudy($XPress);
				$obj->initialize($id,false); // don't retrieve relationships
				switch ($attr){ 
					case "objId":
						$ovalue = $obj->getobjId();
						$obj->setobjId($val);
						echo $val;
						break;
					case "EDRNID":
						$ovalue = $obj->getEDRNID();
						$obj->setEDRNID($val);
						echo $val;
						break;
					case "FHCRC_ID":
						$ovalue = $obj->getFHCRC_ID();
						$obj->setFHCRC_ID($val);
						echo $val;
						break;
					case "DMCC_ID":
						$ovalue = $obj->getDMCC_ID();
						$obj->setDMCC_ID($val);
						echo $val;
						break;
					case "Title":
						$ovalue = $obj->getTitle();
						$obj->setTitle($val);
						echo $val;
						break;
					case "Abstract":
						$ovalue = $obj->getAbstract();
						$obj->setAbstract($val);
						echo stripslashes($val);
						break;
					case "BiomarkerPopulationCharacteristics":
						$ovalue = $obj->getBiomarkerPopulationCharacteristics();
						$obj->setBiomarkerPopulationCharacteristics($val);
						echo $val;
						break;
					case "Design":
						$ovalue = $obj->getDesign();
						$obj->setDesign($val);
						echo $val;
						break;
					case "BiomarkerStudyType":
						$ovalue = $obj->getBiomarkerStudyType();
						$obj->setBiomarkerStudyType($val);
						switch($val){
							case 1:
								echo "Registered"; break;
							case 2:
								echo "Unregistered"; break;
							default: echo $val; break;
						}
						break;
				}
				break;
			case "BiomarkerStudyData":
				$obj = new objBiomarkerStudyData($XPress);
				$obj->initialize($id,false); // don't retrieve relationships
				switch ($attr){ 
					case "objId":
						$ovalue = $obj->getobjId();
						$obj->setobjId($val);
						echo $val;
						break;
					case "Sensitivity":
						$ovalue = $obj->getSensitivity();
						$obj->setSensitivity($val);
						echo $val;
						break;
					case "Specificity":
						$ovalue = $obj->getSpecificity();
						$obj->setSpecificity($val);
						echo $val;
						break;
					case "PPV":
						$ovalue = $obj->getPPV();
						$obj->setPPV($val);
						echo $val;
						break;
					case "NPV":
						$ovalue = $obj->getNPV();
						$obj->setNPV($val);
						echo $val;
						break;
					case "Assay":
						$ovalue = $obj->getAssay();
						$obj->setAssay($val);
						echo $val;
						break;
					case "Technology":
						$ovalue = $obj->getTechnology();
						$obj->setTechnology($val);
						echo $val;
						break;
				}
				break;
			case "Organ":
				$obj = new objOrgan($XPress);
				$obj->initialize($id,false); // don't retrieve relationships
				switch ($attr){ 
					case "objId":
						$ovalue = $obj->getobjId();
						$obj->setobjId($val);
						echo $val;
						break;
					case "Name":
						$ovalue = $obj->getName();
						$obj->setName($val);
						echo $val;
						break;
				}
				break;
			case "BiomarkerOrganData":
				$obj = new objBiomarkerOrganData($XPress);
				$obj->initialize($id,false); // don't retrieve relationships
				switch ($attr){ 
					case "objId":
						$ovalue = $obj->getobjId();
						$obj->setobjId($val);
						echo $val;
						break;
					case "SensitivityMin":
						$ovalue = $obj->getSensitivityMin();
						$obj->setSensitivityMin($val);
						echo $val;
						break;
					case "SensitivityMax":
						$ovalue = $obj->getSensitivityMax();
						$obj->setSensitivityMax($val);
						echo $val;
						break;
					case "SensitivityComment":
						$ovalue = $obj->getSensitivityComment();
						$obj->setSensitivityComment($val);
						echo $val;
						break;
					case "SpecificityMin":
						$ovalue = $obj->getSpecificityMin();
						$obj->setSpecificityMin($val);
						echo $val;
						break;
					case "SpecificityMax":
						$ovalue = $obj->getSpecificityMax();
						$obj->setSpecificityMax($val);
						echo $val;
						break;
					case "SpecificityComment":
						$ovalue = $obj->getSpecificityComment();
						$obj->setSpecificityComment($val);
						echo $val;
						break;
					case "PPVMin":
						$ovalue = $obj->getPPVMin();
						$obj->setPPVMin($val);
						echo $val;
						break;
					case "PPVMax":
						$ovalue = $obj->getPPVMax();
						$obj->setPPVMax($val);
						echo $val;
						break;
					case "PPVComment":
						$ovalue = $obj->getPPVComment();
						$obj->setPPVComment($val);
						echo $val;
						break;
					case "NPVMin":
						$ovalue = $obj->getNPVMin();
						$obj->setNPVMin($val);
						echo $val;
						break;
					case "NPVMax":
						$ovalue = $obj->getNPVMax();
						$obj->setNPVMax($val);
						echo $val;
						break;
					case "NPVComment":
						$ovalue = $obj->getNPVComment();
						$obj->setNPVComment($val);
						echo $val;
						break;
				}
				break;
			case "BiomarkerOrganStudyData":
				$obj = new objBiomarkerOrganStudyData($XPress);
				$obj->initialize($id,false); // don't retrieve relationships
				switch ($attr){ 
					case "objId":
						$ovalue = $obj->getobjId();
						$obj->setobjId($val);
						echo $val;
						break;
					case "Sensitivity":
						$ovalue = $obj->getSensitivity();
						$obj->setSensitivity($val);
						echo $val;
						break;
					case "Specificity":
						$ovalue = $obj->getSpecificity();
						$obj->setSpecificity($val);
						echo $val;
						break;
					case "PPV":
						$ovalue = $obj->getPPV();
						$obj->setPPV($val);
						echo $val;
						break;
					case "NPV":
						$ovalue = $obj->getNPV();
						$obj->setNPV($val);
						echo $val;
						break;
					case "Assay":
						$ovalue = $obj->getAssay();
						$obj->setAssay($val);
						echo $val;
						break;
					case "Technology":
						$ovalue = $obj->getTechnology();
						$obj->setTechnology($val);
						echo $val;
						break;
				}
				break;
			case "Publication":
				$obj = new objPublication($XPress);
				$obj->initialize($id,false); // don't retrieve relationships
				switch ($attr){ 
					case "objId":
						$ovalue = $obj->getobjId();
						$obj->setobjId($val);
						echo $val;
						break;
					case "PubMedID":
						$ovalue = $obj->getPubMedID();
						$obj->setPubMedID($val);
						echo $val;
						break;
					case "Title":
						$ovalue = $obj->getTitle();
						$obj->setTitle($val);
						echo $val;
						break;
					case "Author":
						$ovalue = $obj->getAuthor();
						$obj->setAuthor($val);
						echo $val;
						break;
					case "Journal":
						$ovalue = $obj->getJournal();
						$obj->setJournal($val);
						echo $val;
						break;
					case "Volume":
						$ovalue = $obj->getVolume();
						$obj->setVolume($val);
						echo $val;
						break;
					case "Issue":
						$ovalue = $obj->getIssue();
						$obj->setIssue($val);
						echo $val;
						break;
					case "Year":
						$ovalue = $obj->getYear();
						$obj->setYear($val);
						echo $val;
						break;
				}
				break;
			case "Resource":
				$obj = new objResource($XPress);
				$obj->initialize($id,false); // don't retrieve relationships
				switch ($attr){ 
					case "objId":
						$ovalue = $obj->getobjId();
						$obj->setobjId($val);
						echo $val;
						break;
					case "Name":
						$ovalue = $obj->getName();
						$obj->setName($val);
						echo $val;
						break;
					case "URL":
						$ovalue = $obj->getURL();
						$obj->setURL($val);
						echo $val;
						break;
				}
				break;
			case "Site":
				$obj = new objSite($XPress);
				$obj->initialize($id,false); // don't retrieve relationships
				switch ($attr){ 
					case "objId":
						$ovalue = $obj->getobjId();
						$obj->setobjId($val);
						echo $val;
						break;
					case "Name":
						$ovalue = $obj->getName();
						$obj->setName($val);
						echo $val;
						break;
				}
				break;
			case "Person":
				$obj = new objPerson($XPress);
				$obj->initialize($id,false); // don't retrieve relationships
				switch ($attr){ 
					case "objId":
						$ovalue = $obj->getobjId();
						$obj->setobjId($val);
						echo $val;
						break;
					case "FirstName":
						$ovalue = $obj->getFirstName();
						$obj->setFirstName($val);
						echo $val;
						break;
					case "LastName":
						$ovalue = $obj->getLastName();
						$obj->setLastName($val);
						echo $val;
						break;
					case "Title":
						$ovalue = $obj->getTitle();
						$obj->setTitle($val);
						echo $val;
						break;
					case "Specialty":
						$ovalue = $obj->getSpecialty();
						$obj->setSpecialty($val);
						echo $val;
						break;
					case "Phone":
						$ovalue = $obj->getPhone();
						$obj->setPhone($val);
						echo $val;
						break;
					case "Fax":
						$ovalue = $obj->getFax();
						$obj->setFax($val);
						echo $val;
						break;
					case "Email":
						$ovalue = $obj->getEmail();
						$obj->setEmail($val);
						echo $val;
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
					$obj1 = new objBiomarker($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"Biomarker\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"BiomarkerAlias\"}}}";
					return;
				case "BiomarkerStudyData":
					$obj1 = new objBiomarker($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"Biomarker\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"BiomarkerStudyData\"}}}";
					return;
				case "BiomarkerOrganData":
					$obj1 = new objBiomarker($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"Biomarker\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"BiomarkerOrganData\"}}}";
					return;
				case "Publication":
					$obj1 = new objBiomarker($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"Biomarker\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"Publication\"}}}";
					return;
				case "Resource":
					$obj1 = new objBiomarker($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"Biomarker\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"Resource\"}}}";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "BiomarkerAlias"){
			$localType  = "BiomarkerAlias";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "Biomarker":
					$obj1 = new objBiomarkerAlias($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"BiomarkerAlias\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"Biomarker\"}}}";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "Study"){
			$localType  = "Study";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "BiomarkerStudyData":
					$obj1 = new objStudy($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"Study\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"BiomarkerStudyData\"}}}";
					return;
				case "BiomarkerOrganStudyData":
					$obj1 = new objStudy($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"Study\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"BiomarkerOrganStudyData\"}}}";
					return;
				case "BiomarkerOrganData":
					$obj1 = new objStudy($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"Study\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"BiomarkerOrganData\"}}}";
					return;
				case "Publication":
					$obj1 = new objStudy($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"Study\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"Publication\"}}}";
					return;
				case "Resource":
					$obj1 = new objStudy($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"Study\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"Resource\"}}}";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "BiomarkerStudyData"){
			$localType  = "BiomarkerStudyData";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "Biomarker":
					$obj1 = new objBiomarkerStudyData($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"BiomarkerStudyData\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"Biomarker\"}}}";
					return;
				case "Study":
					$obj1 = new objBiomarkerStudyData($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"BiomarkerStudyData\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"Study\"}}}";
					return;
				case "Publication":
					$obj1 = new objBiomarkerStudyData($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"BiomarkerStudyData\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"Publication\"}}}";
					return;
				case "Resource":
					$obj1 = new objBiomarkerStudyData($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"BiomarkerStudyData\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"Resource\"}}}";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "Organ"){
			$localType  = "Organ";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "BiomarkerOrganData":
					$obj1 = new objOrgan($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"Organ\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"BiomarkerOrganData\"}}}";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "BiomarkerOrganData"){
			$localType  = "BiomarkerOrganData";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "Biomarker":
					$obj1 = new objBiomarkerOrganData($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"BiomarkerOrganData\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"Biomarker\"}}}";
					return;
				case "Organ":
					$obj1 = new objBiomarkerOrganData($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"BiomarkerOrganData\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"Organ\"}}}";
					return;
				case "Resource":
					$obj1 = new objBiomarkerOrganData($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"BiomarkerOrganData\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"Resource\"}}}";
					return;
				case "Publication":
					$obj1 = new objBiomarkerOrganData($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"BiomarkerOrganData\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"Publication\"}}}";
					return;
				case "BiomarkerOrganStudyData":
					$obj1 = new objBiomarkerOrganData($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"BiomarkerOrganData\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"BiomarkerOrganStudyData\"}}}";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "BiomarkerOrganStudyData"){
			$localType  = "BiomarkerOrganStudyData";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "BiomarkerOrganData":
					$obj1 = new objBiomarkerOrganStudyData($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"BiomarkerOrganStudyData\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"BiomarkerOrganData\"}}}";
					return;
				case "Study":
					$obj1 = new objBiomarkerOrganStudyData($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"BiomarkerOrganStudyData\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"Study\"}}}";
					return;
				case "Publication":
					$obj1 = new objBiomarkerOrganStudyData($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"BiomarkerOrganStudyData\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"Publication\"}}}";
					return;
				case "Resource":
					$obj1 = new objBiomarkerOrganStudyData($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"BiomarkerOrganStudyData\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"Resource\"}}}";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "Publication"){
			$localType  = "Publication";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "Biomarker":
					$obj1 = new objPublication($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"Publication\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"Biomarker\"}}}";
					return;
				case "BiomarkerStudyData":
					$obj1 = new objPublication($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"Publication\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"BiomarkerStudyData\"}}}";
					return;
				case "BiomarkerOrganData":
					$obj1 = new objPublication($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"Publication\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"BiomarkerOrganData\"}}}";
					return;
				case "BiomarkerOrganStudyData":
					$obj1 = new objPublication($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"Publication\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"BiomarkerOrganStudyData\"}}}";
					return;
				case "Study":
					$obj1 = new objPublication($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"Publication\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"Study\"}}}";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "Resource"){
			$localType  = "Resource";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "Biomarker":
					$obj1 = new objResource($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"Resource\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"Biomarker\"}}}";
					return;
				case "BiomarkerStudyData":
					$obj1 = new objResource($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"Resource\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"BiomarkerStudyData\"}}}";
					return;
				case "BiomarkerOrganData":
					$obj1 = new objResource($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"Resource\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"BiomarkerOrganData\"}}}";
					return;
				case "BiomarkerOrganStudyData":
					$obj1 = new objResource($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"Resource\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"BiomarkerOrganStudyData\"}}}";
					return;
				case "Study":
					$obj1 = new objResource($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"Resource\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"Study\"}}}";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "Site"){
			$localType  = "Site";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "Person":
					$obj1 = new objSite($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"Site\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"Person\"}}}";
					return;
				default:break;
			}
		}
		if ($_POST['obj1Type'] == "Person"){
			$localType  = "Person";
			$remoteType = "{$_POST['obj2Type']}";
			switch($remoteType){
				case "Site":
					$obj1 = new objPerson($XPress,$_POST['obj1Id']);
					$obj1->link($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "{\"AjaxMessage\": {  \"Description\": \"The ids and types of the newly linked objects\", \"Content\": { \"obj1Id\":{$_POST['obj1Id']}, \"obj1Type\":\"Person\", \"obj2Id\":{$_POST['obj2Id']}, \"obj2Type\":\"Site\"}}}";
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
					$obj1 = new objBiomarker($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "BiomarkerStudyData":
					$obj1 = new objBiomarker($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "BiomarkerOrganData":
					$obj1 = new objBiomarker($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Publication":
					$obj1 = new objBiomarker($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Resource":
					$obj1 = new objBiomarker($XPress,$_POST['obj1Id']);
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
					$obj1 = new objBiomarkerAlias($XPress,$_POST['obj1Id']);
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
					$obj1 = new objStudy($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "BiomarkerOrganStudyData":
					$obj1 = new objStudy($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "BiomarkerOrganData":
					$obj1 = new objStudy($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Publication":
					$obj1 = new objStudy($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Resource":
					$obj1 = new objStudy($XPress,$_POST['obj1Id']);
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
					$obj1 = new objBiomarkerStudyData($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Study":
					$obj1 = new objBiomarkerStudyData($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Publication":
					$obj1 = new objBiomarkerStudyData($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Resource":
					$obj1 = new objBiomarkerStudyData($XPress,$_POST['obj1Id']);
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
					$obj1 = new objOrgan($XPress,$_POST['obj1Id']);
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
					$obj1 = new objBiomarkerOrganData($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Organ":
					$obj1 = new objBiomarkerOrganData($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Resource":
					$obj1 = new objBiomarkerOrganData($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Publication":
					$obj1 = new objBiomarkerOrganData($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "BiomarkerOrganStudyData":
					$obj1 = new objBiomarkerOrganData($XPress,$_POST['obj1Id']);
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
					$obj1 = new objBiomarkerOrganStudyData($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Study":
					$obj1 = new objBiomarkerOrganStudyData($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Publication":
					$obj1 = new objBiomarkerOrganStudyData($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Resource":
					$obj1 = new objBiomarkerOrganStudyData($XPress,$_POST['obj1Id']);
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
					$obj1 = new objPublication($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "BiomarkerStudyData":
					$obj1 = new objPublication($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "BiomarkerOrganData":
					$obj1 = new objPublication($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "BiomarkerOrganStudyData":
					$obj1 = new objPublication($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Study":
					$obj1 = new objPublication($XPress,$_POST['obj1Id']);
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
					$obj1 = new objResource($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "BiomarkerStudyData":
					$obj1 = new objResource($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "BiomarkerOrganData":
					$obj1 = new objResource($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "BiomarkerOrganStudyData":
					$obj1 = new objResource($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				case "Study":
					$obj1 = new objResource($XPress,$_POST['obj1Id']);
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
					$obj1 = new objSite($XPress,$_POST['obj1Id']);
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
					$obj1 = new objPerson($XPress,$_POST['obj1Id']);
					$obj1->unlink($_POST['obj1Attr'],$_POST['obj2Id'],$_POST['obj2Attr']);
					echo "OK";
					return;
				default:break;
			}
		}
	} else if ($_POST['action'] == 'create'){
		// NEW OBJECT CREATION //
		if ($_POST['objType'] == "Biomarker"){
			$obj = new objBiomarker($XPress);
			$obj->create($_POST['Title']);
			$obj->inflate();
			echo $obj->toJSON();
			return;
		}
		if ($_POST['objType'] == "BiomarkerAlias"){
			$obj = new objBiomarkerAlias($XPress);
			$obj->create($_POST['BiomarkerId']);
			$obj->inflate();
			echo $obj->toJSON();
			return;
		}
		if ($_POST['objType'] == "Study"){
			$obj = new objStudy($XPress);
			$obj->create($_POST['Title']);
			$obj->inflate();
			echo $obj->toJSON();
			return;
		}
		if ($_POST['objType'] == "BiomarkerStudyData"){
			$obj = new objBiomarkerStudyData($XPress);
			$obj->create($_POST['StudyId'],$_POST['BiomarkerId']);
			$obj->inflate();
			echo $obj->toJSON();
			return;
		}
		if ($_POST['objType'] == "Organ"){
			$obj = new objOrgan($XPress);
			$obj->create($_POST['Name']);
			$obj->inflate();
			echo $obj->toJSON();
			return;
		}
		if ($_POST['objType'] == "BiomarkerOrganData"){
			$obj = new objBiomarkerOrganData($XPress);
			$obj->create($_POST['OrganId'],$_POST['BiomarkerId']);
			$obj->inflate();
			echo $obj->toJSON();
			return;
		}
		if ($_POST['objType'] == "BiomarkerOrganStudyData"){
			$obj = new objBiomarkerOrganStudyData($XPress);
			$obj->create($_POST['StudyId'],$_POST['BiomarkerOrganDataId']);
			$obj->inflate();
			echo $obj->toJSON();
			return;
		}
		if ($_POST['objType'] == "Publication"){
			$obj = new objPublication($XPress);
			$obj->create($_POST['PubMedID']);
			$obj->inflate();
			echo $obj->toJSON();
			return;
		}
		if ($_POST['objType'] == "Resource"){
			$obj = new objResource($XPress);
			$obj->create();
			$obj->inflate();
			echo $obj->toJSON();
			return;
		}
		if ($_POST['objType'] == "Site"){
			$obj = new objSite($XPress);
			$obj->create();
			$obj->inflate();
			echo $obj->toJSON();
			return;
		}
		if ($_POST['objType'] == "Person"){
			$obj = new objPerson($XPress);
			$obj->create();
			$obj->inflate();
			echo $obj->toJSON();
			return;
		}
	} else if ($_POST['action'] == 'delete'){
		// OBJECT DELETION //
		if ($_POST['objType'] == "Biomarker"){
			$obj = new objBiomarker($XPress,$_POST['objId']);
			$obj->delete();
			echo "Biomarker Deleted.";
			return;
		}
		if ($_POST['objType'] == "BiomarkerAlias"){
			$obj = new objBiomarkerAlias($XPress,$_POST['objId']);
			$obj->delete();
			echo "BiomarkerAlias Deleted.";
			return;
		}
		if ($_POST['objType'] == "Study"){
			$obj = new objStudy($XPress,$_POST['objId']);
			$obj->delete();
			echo "Study Deleted.";
			return;
		}
		if ($_POST['objType'] == "BiomarkerStudyData"){
			$obj = new objBiomarkerStudyData($XPress,$_POST['objId']);
			$obj->delete();
			echo "BiomarkerStudyData Deleted.";
			return;
		}
		if ($_POST['objType'] == "Organ"){
			$obj = new objOrgan($XPress,$_POST['objId']);
			$obj->delete();
			echo "Organ Deleted.";
			return;
		}
		if ($_POST['objType'] == "BiomarkerOrganData"){
			$obj = new objBiomarkerOrganData($XPress,$_POST['objId']);
			$obj->delete();
			echo "BiomarkerOrganData Deleted.";
			return;
		}
		if ($_POST['objType'] == "BiomarkerOrganStudyData"){
			$obj = new objBiomarkerOrganStudyData($XPress,$_POST['objId']);
			$obj->delete();
			echo "BiomarkerOrganStudyData Deleted.";
			return;
		}
		if ($_POST['objType'] == "Publication"){
			$obj = new objPublication($XPress,$_POST['objId']);
			$obj->delete();
			echo "Publication Deleted.";
			return;
		}
		if ($_POST['objType'] == "Resource"){
			$obj = new objResource($XPress,$_POST['objId']);
			$obj->delete();
			echo "Resource Deleted.";
			return;
		}
		if ($_POST['objType'] == "Site"){
			$obj = new objSite($XPress,$_POST['objId']);
			$obj->delete();
			echo "Site Deleted.";
			return;
		}
		if ($_POST['objType'] == "Person"){
			$obj = new objPerson($XPress,$_POST['objId']);
			$obj->delete();
			echo "Person Deleted.";
			return;
		}
	}
}?>