<?php


	/**
	 * Processes updates to data objects sent via AJAX
	 *
	 * When including this file, please remember that it needs access to your
	 * app.php file, which should be included first. 
	 */
	 
	if (isset($_POST['action']) && $_POST['action'] == 'update') {
		switch ($_POST['object']) {
			case 'Biomarker':
				$o = BiomarkerFactory::Retrieve($_POST['id']);
				if (isset($_POST['EKEID'])) {$o->setEKEID($_POST['EKEID']);echo ($o->getEKEID() == '')? 'click to edit' : $o->getEKEID();exit();}
				if (isset($_POST['BiomarkerID'])) {$o->setBiomarkerID($_POST['BiomarkerID']);echo ($o->getBiomarkerID() == '')? 'click to edit' : $o->getBiomarkerID();exit();}
				if (isset($_POST['PanelID'])) {$o->setPanelID($_POST['PanelID']);echo ($o->getPanelID() == '')? 'click to edit' : $o->getPanelID();exit();}
				if (isset($_POST['Title'])) {$o->setTitle($_POST['Title']);echo ($o->getTitle() == '')? 'click to edit' : $o->getTitle();exit();}
				if (isset($_POST['ShortName'])) {$o->setShortName($_POST['ShortName']);echo ($o->getShortName() == '')? 'click to edit' : $o->getShortName();exit();}
				if (isset($_POST['Description'])) {$o->setDescription($_POST['Description']);echo ($o->getDescription() == '')? 'click to edit' : $o->getDescription();exit();}
				if (isset($_POST['QAState'])) {$o->setQAState($_POST['QAState']);echo ($o->getQAState() == '')? 'click to edit' : $o->getQAState();exit();}
				if (isset($_POST['Phase'])) {$o->setPhase($_POST['Phase']);echo ($o->getPhase() == '')? 'click to edit' : $o->getPhase();exit();}
				if (isset($_POST['Security'])) {$o->setSecurity($_POST['Security']);echo ($o->getSecurity() == '')? 'click to edit' : $o->getSecurity();exit();}
				if (isset($_POST['Type'])) {$o->setType($_POST['Type']);echo ($o->getType() == '')? 'click to edit' : $o->getType();exit();}
				break;
case 'BiomarkerAlias':
				$o = BiomarkerAliasFactory::Retrieve($_POST['id']);
				if (isset($_POST['Alias'])) {$o->setAlias($_POST['Alias']);echo ($o->getAlias() == '')? 'click to edit' : $o->getAlias();exit();}
				break;
case 'Study':
				$o = StudyFactory::Retrieve($_POST['id']);
				if (isset($_POST['EDRNID'])) {$o->setEDRNID($_POST['EDRNID']);echo ($o->getEDRNID() == '')? 'click to edit' : $o->getEDRNID();exit();}
				if (isset($_POST['FHCRCID'])) {$o->setFHCRCID($_POST['FHCRCID']);echo ($o->getFHCRCID() == '')? 'click to edit' : $o->getFHCRCID();exit();}
				if (isset($_POST['DMCCID'])) {$o->setDMCCID($_POST['DMCCID']);echo ($o->getDMCCID() == '')? 'click to edit' : $o->getDMCCID();exit();}
				if (isset($_POST['Title'])) {$o->setTitle($_POST['Title']);echo ($o->getTitle() == '')? 'click to edit' : $o->getTitle();exit();}
				if (isset($_POST['StudyAbstract'])) {$o->setStudyAbstract($_POST['StudyAbstract']);echo ($o->getStudyAbstract() == '')? 'click to edit' : $o->getStudyAbstract();exit();}
				if (isset($_POST['BiomarkerPopulationCharacteristics'])) {$o->setBiomarkerPopulationCharacteristics($_POST['BiomarkerPopulationCharacteristics']);echo ($o->getBiomarkerPopulationCharacteristics() == '')? 'click to edit' : $o->getBiomarkerPopulationCharacteristics();exit();}
				if (isset($_POST['BPCDescription'])) {$o->setBPCDescription($_POST['BPCDescription']);echo ($o->getBPCDescription() == '')? 'click to edit' : $o->getBPCDescription();exit();}
				if (isset($_POST['Design'])) {$o->setDesign($_POST['Design']);echo ($o->getDesign() == '')? 'click to edit' : $o->getDesign();exit();}
				if (isset($_POST['DesignDescription'])) {$o->setDesignDescription($_POST['DesignDescription']);echo ($o->getDesignDescription() == '')? 'click to edit' : $o->getDesignDescription();exit();}
				if (isset($_POST['BiomarkerStudyType'])) {$o->setBiomarkerStudyType($_POST['BiomarkerStudyType']);echo ($o->getBiomarkerStudyType() == '')? 'click to edit' : $o->getBiomarkerStudyType();exit();}
				break;
case 'BiomarkerStudyData':
				$o = BiomarkerStudyDataFactory::Retrieve($_POST['id']);
				if (isset($_POST['Sensitivity'])) {$o->setSensitivity($_POST['Sensitivity']);echo ($o->getSensitivity() == '')? 'click to edit' : $o->getSensitivity();exit();}
				if (isset($_POST['Specificity'])) {$o->setSpecificity($_POST['Specificity']);echo ($o->getSpecificity() == '')? 'click to edit' : $o->getSpecificity();exit();}
				if (isset($_POST['PPV'])) {$o->setPPV($_POST['PPV']);echo ($o->getPPV() == '')? 'click to edit' : $o->getPPV();exit();}
				if (isset($_POST['NPV'])) {$o->setNPV($_POST['NPV']);echo ($o->getNPV() == '')? 'click to edit' : $o->getNPV();exit();}
				if (isset($_POST['Assay'])) {$o->setAssay($_POST['Assay']);echo ($o->getAssay() == '')? 'click to edit' : $o->getAssay();exit();}
				if (isset($_POST['Technology'])) {$o->setTechnology($_POST['Technology']);echo ($o->getTechnology() == '')? 'click to edit' : $o->getTechnology();exit();}
				break;
case 'Organ':
				$o = OrganFactory::Retrieve($_POST['id']);
				if (isset($_POST['Name'])) {$o->setName($_POST['Name']);echo ($o->getName() == '')? 'click to edit' : $o->getName();exit();}
				break;
case 'BiomarkerOrganData':
				$o = BiomarkerOrganDataFactory::Retrieve($_POST['id']);
				if (isset($_POST['SensitivityMin'])) {$o->setSensitivityMin($_POST['SensitivityMin']);echo ($o->getSensitivityMin() == '')? 'click to edit' : $o->getSensitivityMin();exit();}
				if (isset($_POST['SensitivityMax'])) {$o->setSensitivityMax($_POST['SensitivityMax']);echo ($o->getSensitivityMax() == '')? 'click to edit' : $o->getSensitivityMax();exit();}
				if (isset($_POST['SensitivityComment'])) {$o->setSensitivityComment($_POST['SensitivityComment']);echo ($o->getSensitivityComment() == '')? 'click to edit' : $o->getSensitivityComment();exit();}
				if (isset($_POST['SpecificityMin'])) {$o->setSpecificityMin($_POST['SpecificityMin']);echo ($o->getSpecificityMin() == '')? 'click to edit' : $o->getSpecificityMin();exit();}
				if (isset($_POST['SpecificityMax'])) {$o->setSpecificityMax($_POST['SpecificityMax']);echo ($o->getSpecificityMax() == '')? 'click to edit' : $o->getSpecificityMax();exit();}
				if (isset($_POST['SpecificityComment'])) {$o->setSpecificityComment($_POST['SpecificityComment']);echo ($o->getSpecificityComment() == '')? 'click to edit' : $o->getSpecificityComment();exit();}
				if (isset($_POST['PPVMin'])) {$o->setPPVMin($_POST['PPVMin']);echo ($o->getPPVMin() == '')? 'click to edit' : $o->getPPVMin();exit();}
				if (isset($_POST['PPVMax'])) {$o->setPPVMax($_POST['PPVMax']);echo ($o->getPPVMax() == '')? 'click to edit' : $o->getPPVMax();exit();}
				if (isset($_POST['PPVComment'])) {$o->setPPVComment($_POST['PPVComment']);echo ($o->getPPVComment() == '')? 'click to edit' : $o->getPPVComment();exit();}
				if (isset($_POST['NPVMin'])) {$o->setNPVMin($_POST['NPVMin']);echo ($o->getNPVMin() == '')? 'click to edit' : $o->getNPVMin();exit();}
				if (isset($_POST['NPVMax'])) {$o->setNPVMax($_POST['NPVMax']);echo ($o->getNPVMax() == '')? 'click to edit' : $o->getNPVMax();exit();}
				if (isset($_POST['NPVComment'])) {$o->setNPVComment($_POST['NPVComment']);echo ($o->getNPVComment() == '')? 'click to edit' : $o->getNPVComment();exit();}
				if (isset($_POST['QAState'])) {$o->setQAState($_POST['QAState']);echo ($o->getQAState() == '')? 'click to edit' : $o->getQAState();exit();}
				if (isset($_POST['Phase'])) {$o->setPhase($_POST['Phase']);echo ($o->getPhase() == '')? 'click to edit' : $o->getPhase();exit();}
				if (isset($_POST['Description'])) {$o->setDescription($_POST['Description']);echo ($o->getDescription() == '')? 'click to edit' : $o->getDescription();exit();}
				break;
case 'BiomarkerOrganStudyData':
				$o = BiomarkerOrganStudyDataFactory::Retrieve($_POST['id']);
				if (isset($_POST['Sensitivity'])) {$o->setSensitivity($_POST['Sensitivity']);echo ($o->getSensitivity() == '')? 'click to edit' : $o->getSensitivity();exit();}
				if (isset($_POST['Specificity'])) {$o->setSpecificity($_POST['Specificity']);echo ($o->getSpecificity() == '')? 'click to edit' : $o->getSpecificity();exit();}
				if (isset($_POST['PPV'])) {$o->setPPV($_POST['PPV']);echo ($o->getPPV() == '')? 'click to edit' : $o->getPPV();exit();}
				if (isset($_POST['NPV'])) {$o->setNPV($_POST['NPV']);echo ($o->getNPV() == '')? 'click to edit' : $o->getNPV();exit();}
				if (isset($_POST['Assay'])) {$o->setAssay($_POST['Assay']);echo ($o->getAssay() == '')? 'click to edit' : $o->getAssay();exit();}
				if (isset($_POST['Technology'])) {$o->setTechnology($_POST['Technology']);echo ($o->getTechnology() == '')? 'click to edit' : $o->getTechnology();exit();}
				break;
case 'Publication':
				$o = PublicationFactory::Retrieve($_POST['id']);
				if (isset($_POST['PubMedID'])) {$o->setPubMedID($_POST['PubMedID']);echo ($o->getPubMedID() == '')? 'click to edit' : $o->getPubMedID();exit();}
				if (isset($_POST['Title'])) {$o->setTitle($_POST['Title']);echo ($o->getTitle() == '')? 'click to edit' : $o->getTitle();exit();}
				if (isset($_POST['Author'])) {$o->setAuthor($_POST['Author']);echo ($o->getAuthor() == '')? 'click to edit' : $o->getAuthor();exit();}
				if (isset($_POST['Journal'])) {$o->setJournal($_POST['Journal']);echo ($o->getJournal() == '')? 'click to edit' : $o->getJournal();exit();}
				if (isset($_POST['Volume'])) {$o->setVolume($_POST['Volume']);echo ($o->getVolume() == '')? 'click to edit' : $o->getVolume();exit();}
				if (isset($_POST['Issue'])) {$o->setIssue($_POST['Issue']);echo ($o->getIssue() == '')? 'click to edit' : $o->getIssue();exit();}
				if (isset($_POST['Year'])) {$o->setYear($_POST['Year']);echo ($o->getYear() == '')? 'click to edit' : $o->getYear();exit();}
				break;
case 'Resource':
				$o = ResourceFactory::Retrieve($_POST['id']);
				if (isset($_POST['Name'])) {$o->setName($_POST['Name']);echo ($o->getName() == '')? 'click to edit' : $o->getName();exit();}
				if (isset($_POST['URL'])) {$o->setURL($_POST['URL']);echo ($o->getURL() == '')? 'click to edit' : $o->getURL();exit();}
				break;
case 'Site':
				$o = SiteFactory::Retrieve($_POST['id']);
				if (isset($_POST['Name'])) {$o->setName($_POST['Name']);echo ($o->getName() == '')? 'click to edit' : $o->getName();exit();}
				break;
case 'Person':
				$o = PersonFactory::Retrieve($_POST['id']);
				if (isset($_POST['FirstName'])) {$o->setFirstName($_POST['FirstName']);echo ($o->getFirstName() == '')? 'click to edit' : $o->getFirstName();exit();}
				if (isset($_POST['LastName'])) {$o->setLastName($_POST['LastName']);echo ($o->getLastName() == '')? 'click to edit' : $o->getLastName();exit();}
				if (isset($_POST['Title'])) {$o->setTitle($_POST['Title']);echo ($o->getTitle() == '')? 'click to edit' : $o->getTitle();exit();}
				if (isset($_POST['Specialty'])) {$o->setSpecialty($_POST['Specialty']);echo ($o->getSpecialty() == '')? 'click to edit' : $o->getSpecialty();exit();}
				if (isset($_POST['Phone'])) {$o->setPhone($_POST['Phone']);echo ($o->getPhone() == '')? 'click to edit' : $o->getPhone();exit();}
				if (isset($_POST['Fax'])) {$o->setFax($_POST['Fax']);echo ($o->getFax() == '')? 'click to edit' : $o->getFax();exit();}
				if (isset($_POST['Email'])) {$o->setEmail($_POST['Email']);echo ($o->getEmail() == '')? 'click to edit' : $o->getEmail();exit();}
				break;
			default:
				echo 'error';
				exit();
		}
	}
?>