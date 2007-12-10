<?php
	require_once("utilities/common/AjaxEditors.class.php");
	
$associateDisplay = <<<ENDASSOCDISPLAY
			<h3>Add study data to {$object->getTitle()}</h3>
			<span class="hint">Type slowly into the box. Available matches will appear in a drop-down list below.</span><br/><br/>
			Study Title:&nbsp;
				<script type="text/javascript">ajaxti('assocStudy','',455,'model/AjaxAutocomplete.php','Study','Title','assets/images/working.gif','assocStudy','Title');</script>
				<input type="button" value="Associate" onclick="createBiomarkerStudyData({$object->getObjId()},$('assocStudyAutocompleteID').getValue());"/>
				<input type="button" value="Cancel" onclick="$('assocStudy_autocomplete').value='';Effect.SwitchOff('associationContainer');"/>
ENDASSOCDISPLAY;



	$studyDisplay = '';
	if (sizeof($object->getStudies()) == 0){
		$studyDisplay = 'There is no study data associated with this biomarker.';
	} else {
		foreach ($object->getStudies() as $sd){
			$sensEditor = AjaxEditors::create('model/AjaxHandler.php',$sd->getSensitivity(),"sens{$sd->getObjId()}","sens{$sd->getObjId()}",$sd->_getType(),$sd->getObjId(),BiomarkerOrganStudyDataVars::BIO_SENSITIVITY);
			$specEditor = AjaxEditors::create('model/AjaxHandler.php',$sd->getSpecificity(),"spec{$sd->getObjId()}","spec{$sd->getObjId()}",$sd->_getType(),$sd->getObjId(),BiomarkerOrganStudyDataVars::BIO_SPECIFICITY);
			$npvEditor  = AjaxEditors::create('model/AjaxHandler.php',$sd->getNPV(),"npv{$sd->getObjId()}","npv{$sd->getObjId()}",$sd->_getType(),$sd->getObjId(),BiomarkerOrganStudyDataVars::BIO_NPV);
			$ppvEditor  = AjaxEditors::create('model/AjaxHandler.php',$sd->getPPV(),"ppv{$sd->getObjId()}","npv{$sd->getObjId()}",$sd->_getType(),$sd->getObjId(),BiomarkerOrganStudyDataVars::BIO_PPV);
			
			$studyDisplay .=  <<<ENDORGANDISPLAY
				<div class="overview">
					<h3><a href="study.php?view=basics&objId={$sd->getStudy()->getObjId()}">{$sd->getStudy()->getTitle()}</a>&nbsp;<span class="titleAction">(delete)</span></h3>
					<table class="ajaxEdits greenborder" >
						<tr><td class="label">Sensitivity (%):</td><td>{$sensEditor}</td></tr>
						<tr class="even"><td class="label">Specificity (%):</td><td>{$specEditor}</td></tr>
						<tr><td class="label">Negative Predictive Value (%):</td><td>{$npvEditor}</td></tr>
						<tr class="even"><td class="label">Positive Predictive Value (%):</td><td>{$ppvEditor}</td></tr>
					</table>
					<h5>Publications (<span style="font-weight:normal;"><a href="#" onclick="Effect.Appear('associationContainer{$sd->getObjId()}');">associate publication</a></span>)</h5>
					<div class="associationContainer" id="associationContainer{$sd->getObjId()}" style="display:none;">
						{$associatePubDisplay}
					</div>
					
				</div>
ENDORGANDISPLAY;
		}
	}
	
	
	

	

	echo <<<ENDDISPLAY
		<div class="associationContainer" id="associationContainer" style="display:none;">
			{$associateDisplay}
		</div>
		<div id="notice"></div>
		<h4>Associated Studies:</h4>
		<div class="overviewContainer">
			{$studyDisplay}	
		</div>
	
ENDDISPLAY;
	
	echo <<<ENDACTIONS
		<div class="specialactions">
			<ul>
			  <li><span class="pseudolink grey" onclick="Effect.Appear('associationContainer');">Associate study data</span</li>
			</ul>
		</div>
ENDACTIONS

?>