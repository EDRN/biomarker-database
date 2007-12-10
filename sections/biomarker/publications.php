<?php
	require_once("utilities/common/AjaxEditors.class.php");
	
$associateDisplay = <<<ENDASSOCDISPLAY
			<h3>Add publication data to {$object->getTitle()}</h3>
			<span class="hint">Type slowly into the box. Available matches will appear in a drop-down list below.</span><br/><br/>
			PubMed ID:&nbsp;
				<script type="text/javascript">ajaxti('assocPub','',240,'model/AjaxAutocomplete.php','Publication','PubMedID','assets/images/working.gif','assocPub','PubMedID|Title');</script>
				<input type="button" value="Associate" onclick="linkBiomarkerPublication({$object->getObjId()},$('assocPubAutocompleteID').getValue());"/>
				<input type="button" value="Cancel" onclick="$('assocPub_autocomplete').value='';Effect.SwitchOff('associationContainer');"/><br/><br/>
			No Matches? <a href="util/importpubmed.php">Import a new publication from PubMed</a>
ENDASSOCDISPLAY;



	$pubDisplay = '';
	if (sizeof($object->getPublications()) == 0){
		$pubDisplay = 'There is no publication data associated with this biomarker.';
	} else {
		foreach ($object->getPublications() as $pd){
			$pubDisplay .=  <<<ENDPUBDISPLAY
				<div class="overview" id="pub{$pd->getObjId()}">
					<h3><a href="publication.php?view=basics&objId={$pd->getObjId()}">{$pd->getTitle()}</a>&nbsp;<span class="titleAction" onclick="deletePublication({$pd->getObjId()},'pub{$pd->getObjId()}')");">(delete)</span></h3>
					<table>
						<tr><td class="label">PubMed ID:</td><td>{$pd->getPubMedID()}</td></tr>
						<tr><td class="label">Author:</td><td>{$pd->getAuthor()}</td></tr>
						<tr><td class="label">Journal:</td><td>{$pd->getJournal()}</td>
						<tr><td>&nbsp;</td><td>Vol: {$pd->getVolume()}, Issue: {$pd->GetIssue()}, Year: {$pd->getYear()}</td></tr>
					</table>
				</div>
ENDPUBDISPLAY;
		}
	}
	
	
	

	

	echo <<<ENDDISPLAY
		<div class="associationContainer" id="associationContainer" style="display:none;">
			{$associateDisplay}
		</div>
		<div id="notice"></div>
		<h4>Associated Publications:</h4>
		<div class="overviewContainer">
			{$pubDisplay}	
		</div>
	
ENDDISPLAY;
	
	echo <<<ENDACTIONS
		<div class="specialactions">
			<ul>
			  <li><span class="pseudolink grey" onclick="Effect.Appear('associationContainer');">Associate a publication</span></li>
			</ul>
		</div>
ENDACTIONS

?>