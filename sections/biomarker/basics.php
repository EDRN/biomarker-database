<?php
	require_once("utilities/common/AjaxEditors.class.php");
	$titleEditor = AjaxEditors::create('model/AjaxHandler.php',$object->getTitle(),"title","title",$object->_getType(),$object->getObjId(),BiomarkerVars::BIO_TITLE);
	$shortNameEd = AjaxEditors::create('model/AjaxHandler.php',$object->getShortName(),"shortname","shortname",$object->_getType(),$object->getObjId(),BiomarkerVars::BIO_SHORTNAME);
	$urnEditor   = AjaxEditors::create('model/AjaxHandler.php',$object->getBiomarkerID(),"ident","ident",$object->_getType(),$object->getObjId(),BiomarkerVars::BIO_BIOMARKERID);
	$phaseEditor = AjaxEditors::createSelect('model/AjaxHandler.php',$object->getPhase(),"phase","phase",$object->_getType(),$object->getObjId(),BiomarkerVars::BIO_PHASE,array("1","One (1)","2"=>"Two (2)","3"=>"Three (3)","4","label"=>"Four (4)","5"=>"Five (5)"),'','click to select');
	$qaStateEditor  = AjaxEditors::createSelect('model/AjaxHandler.php',$object->getQAState(),"qastate","qastate",$object->_getType(),$object->getObjId(),BiomarkerVars::BIO_QASTATE,array("1"=>"New","2"=>"Under Review","3"=>"Approved","4"=>"Rejected"),'','click to select');
	$securityEditor = AjaxEditors::createSelect('model/AjaxHandler.php',$object->getSecurity(),"security","security",$object->_getType(),$object->getObjId(),BiomarkerVars::BIO_SECURITY,array("1"=>"Public","2"=>"Private"),'',"click to select");
	$typeEditor  = AjaxEditors::createSelect('model/AjaxHandler.php',$object->getType(),"type","type",$object->_getType(),$object->getObjId(),BiomarkerVars::BIO_TYPE,array("1"=>"Epigenomics","2"=>"Genomics","3"=>"Proteomics","4"=>"Glycomics","5"=>"Nanotechnology","6"=>"Metabalomics","7"=>"Hypermethylation"),'',"click to select");
	$descriptionEditor = AjaxEditors::createMultiline('model/AjaxHandler.php',$object->getDescription(),"description","description",$object->_getType(),$object->getObjId(),BiomarkerVars::BIO_DESCRIPTION,7,47,'','click to edit');
	
	echo  <<<ENDDELETEDISPLAY
		<div class="associationContainer warn delete" id="deleteBio" style="display:none;">
			<h3>Confirm Delete</h3>
			Are you sure you want to delete this Biomarker? This action can not be undone.<br/><br/>
			<form action="biomarker.php" method="post">
			<input type="hidden" name="special" value="delete"/>
			<input type="hidden" name="objId" value="{$object->getObjId()}"/>
			<input type="submit" value="Delete"/>&nbsp;
			<span class="pseudolink" onclick="Element.hide('deleteBio');">Cancel</span>
		</div>
ENDDELETEDISPLAY;
	
	echo <<<ENDDISPLAY
		<h4>Basic Information:</h4>
		<table class="ajaxEdits">
			<tr class="even"><td class="label">Title:</td><td>{$titleEditor}</td></tr>
			<tr><td class="label">Short Name:</td><td>{$shortNameEd}</td></tr>
			<tr class="even"><td class="label">Identifier:</td><td>{$urnEditor}</td></tr>
			<!--<tr><td class="label">Phase:</td><td>{$phaseEditor}</td></tr>-->
			<tr class=""><td class="label">Security:</td><td>{$securityEditor}</td></tr>
			<tr class="even"><td class="label">QA State:</td><td>{$qaStateEditor}</td></tr>
			<tr class=""><td class="label">Type:</td><td>{$typeEditor}</td></tr>
			<tr class="even"><td class="label">Description:</td><td>{$descriptionEditor}</td></tr>
		</table>
ENDDISPLAY;
	
	echo <<<ENDACTIONS
		<div class="specialactions">
			<ul>
			  <li><a href="browse/biomarkers/">Browse Biomarkers</a></li>
			  <li><span class="pseudolink grey" onclick="Effect.Appear('deleteBio');">Delete This Biomarker</span></li>
			</ul>
		</div>
ENDACTIONS
?>