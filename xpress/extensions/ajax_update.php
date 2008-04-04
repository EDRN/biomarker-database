<?php
	/**
	 * Processes updates to data objects sent via AJAX
	 */
	require_once("../app.php");
	
	if (isset($_POST['action']) && $_POST['action'] == 'update') {
		switch ($_POST['object']) {
			case 'Biomarker':
				$b = BiomarkerFactory::Retrieve($_POST['id']);
				if (isset($_POST['Title'])) {$b->setTitle($_POST['Title']);echo ($b->getTitle() == '')? 'click to edit' : $b->getTitle();exit();}
				if (isset($_POST['ShortName'])) {$b->setShortname($_POST['ShortName']);echo ($b->getShortName() == '')? 'click to edit' : $b->getShortName();exit();}
				if (isset($_POST['BiomarkerID'])) {$b->setBiomarkerID($_POST['BiomarkerID']);echo ($b->getBiomarkerID() == '')? 'click to edit' : $b->getBiomarkerID();exit();}
				if (isset($_POST['Type'])) {$b->setType($_POST['Type']);echo ($b->getType() == '')? 'click to edit' : $b->getType();exit();}
				if (isset($_POST['Security'])) {$b->setSecurity($_POST['Security']);echo ($b->getSecurity() == '')? 'click to edit' : $b->getSecurity();exit();}
				if (isset($_POST['QAState'])) {$b->setQAState($_POST['QAState']);echo ($b->getQAState() == '')? 'click to edit' : $b->getQAState();exit();}
				if (isset($_POST['Type'])) {$b->setType($_POST['Type']);echo ($b->getType() == '')? 'click to edit' : $b->getType();exit();}
				if (isset($_POST['Description'])) {$b->setDescription($_POST['Description']);echo ($b->getDescription() == '')? 'click to edit' : $b->getDescription();exit();}
				break;
			default:
				echo 'error';
				exit();
		}	
	}
?>