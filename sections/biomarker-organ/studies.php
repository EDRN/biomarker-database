<?php
	require_once("utilities/common/AjaxEditors.class.php");
	
$associateDisplay = <<<ENDASSOCDISPLAY
			<h3>Add study data to {$object->getBiomarker()->getTitle()}/{$object->getOrgan()->getName()}</h3>
			<span class="hint">Type slowly into the box. Available matches will appear in a drop-down list below.</span><br/><br/>
			Study Title:&nbsp;
				<script type="text/javascript">ajaxti('assocStudy','',455,'model/AjaxAutocomplete.php','Study','Title','assets/images/working.gif','assocStudy','Title');</script>
				<input type="button" value="Associate" onclick="createBiomarkerOrganStudyData({$object->getObjId()},$('assocStudyAutocompleteID').getValue());"/>
				<input type="button" value="Cancel" onclick="$('assocStudy_autocomplete').value='';Effect.SwitchOff('associationContainer');"/>
ENDASSOCDISPLAY;

function showAssociateStudyPublication($biomarkerOrganStudyData){
	return <<<ENDASSOCPUBDISPLAY
			<h3>Add a related publication to this study:</h3>
			<span class="hint">Type slowly into the box. Available matches will appear in a drop-down list below.</span><br/><br/>
			Publication PubMed ID: &nbsp;
				<script type="text/javascript">ajaxti('assocPub','',455,'model/AjaxAutocomplete.php','Publication','PubMedID','assets/images/working.gif','assocPub','PubMedID|Title');</script>
				<input type="button" value="Associate" onclick="linkBiomarkerOrganStudyPublication({$biomarkerOrganStudyData->getObjId()},$('assocPubAutocompleteID').getValue());"/>
				<input type="button" value="Cancel" onclick="$('assocPub_autocomplete').value='';Effect.SwitchOff('pubAssociationContainer{$biomarkerOrganStudyData->getObjId()}');"/>
ENDASSOCPUBDISPLAY;
}
function showStudyAssociatedPublications($biomarkerOrganStudyData){
	if (sizeof($biomarkerOrganStudyData->getPublications()) == 0){
		$pubsDisplay = "<p class=\"smaller indented\">no publications have been associated with this study</p>";
	} else {
		$pubsDisplay = "<ul>";
		foreach ($biomarkerOrganStudyData->getPublications() as $pub){
			$title = ((strlen($pub->getTitle()) > 80)? substr($pub->getTitle(),0,80) . "..." : $pub->getTitle());
			$pubsDisplay .= "<li><a href=\"publication.php?objId={$pub->getObjId()}&view=basics\">{$pub->getPubMedID()}</a> - {$title}</li>";
		}
		$pubsDisplay .= "</ul>";
	}
	return $pubsDisplay;
}


$associateResDisplay = <<<ENDASSOCRESDISPLAY
			<h3>Add a related resource to this study:</h3>
				<input type="button" value="Cancel" onclick="$('assocStudy_autocomplete').value='';Effect.SwitchOff('associationContainer');"/>
ENDASSOCRESDISPLAY;

	//print_r($object);

	$studyDisplay = '';
	if (sizeof($object->getStudyDatas()) == 0){
		$studyDisplay = 'There is no study data associated with this biomarker-organ.';
	} else {
		foreach ($object->getStudyDatas() as $sd){
			$sensEditor = AjaxEditors::create('model/AjaxHandler.php',$sd->getSensitivity(),"sens{$sd->getObjId()}","sens{$sd->getObjId()}",$sd->_getType(),$sd->getObjId(),BiomarkerOrganStudyDataVars::BIO_SENSITIVITY);
			$specEditor = AjaxEditors::create('model/AjaxHandler.php',$sd->getSpecificity(),"spec{$sd->getObjId()}","spec{$sd->getObjId()}",$sd->_getType(),$sd->getObjId(),BiomarkerOrganStudyDataVars::BIO_SPECIFICITY);
			$npvEditor  = AjaxEditors::create('model/AjaxHandler.php',$sd->getNPV(),"npv{$sd->getObjId()}","npv{$sd->getObjId()}",$sd->_getType(),$sd->getObjId(),BiomarkerOrganStudyDataVars::BIO_NPV);
			$ppvEditor  = AjaxEditors::create('model/AjaxHandler.php',$sd->getPPV(),"ppv{$sd->getObjId()}","npv{$sd->getObjId()}",$sd->_getType(),$sd->getObjId(),BiomarkerOrganStudyDataVars::BIO_PPV);
			
			
			if (sizeof($object->getResources()) == 0){
				$ressDisplay = "<p class=\"smaller indented\">no resources have been associated with this study</p>";
			} else {
				$ressDisplay = "<ul>";
				foreach ($object->getPublications() as $pub){
					$ressDisplay .= "<li>{$pub->getTitle()}</li>";
				}
				$ressDisplay .= "</ul>";
			}
			
			$studyDisplay .=  <<<ENDSTUDYDISPLAYSTART
				<div class="overview">
					<h3><a href="study.php?view=basics&objId={$sd->getStudy()->getObjId()}">{$sd->getStudy()->getTitle()}</a>&nbsp;<span class="titleAction">(delete)</span></h3>
					<table class="ajaxEdits greenborder" >
						<tr><td class="label">Sensitivity (%):</td><td>{$sensEditor}</td></tr>
						<tr class="even"><td class="label">Specificity (%):</td><td>{$specEditor}</td></tr>
						<tr><td class="label">Negative Predictive Value (%):</td><td>{$npvEditor}</td></tr>
						<tr class="even"><td class="label">Positive Predictive Value (%):</td><td>{$ppvEditor}</td></tr>
					</table>
ENDSTUDYDISPLAYSTART;
			$studyDisplay .= "<h5>Publications (<span style=\"font-weight:normal;\"><span class=\"pseudolink\" onclick=\"Effect.Appear('pubAssociationContainer{$sd->getObjId()}');\">associate publication</span></span>)</h5>"
							."<div class=\"associationContainer\" id=\"pubAssociationContainer{$sd->getObjId()}\" style=\"display:none;\">";
			$studyDisplay .= showAssociateStudyPublication($sd);
			$studyDisplay .= "</div>";
			$studyDisplay .= showStudyAssociatedPublications($sd);
			$studyDisplay .= <<<ENDSTUDYDISPLAYEND
					
					<h5>Resources (<span style="font-weight:normal;"><span class="pseudolink" onclick="Effect.Appear('resAssociationContainer{$sd->getObjId()}');">associate resource</a></span>)</h5>
					<div class="associationContainer" id="resAssociationContainer{$sd->getObjId()}" style="display:none;">
						{$associateResDisplay}
					</div>
					{$ressDisplay}		
				</div>
ENDSTUDYDISPLAYEND;
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
			  <li><span class="pseudolink grey" onclick="Effect.Appear('associationContainer');">Associate study data</span></li>
			</ul>
		</div>
ENDACTIONS

?>