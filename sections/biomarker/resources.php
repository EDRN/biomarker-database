<?php
	require_once("utilities/common/AjaxEditors.class.php");
	
$associateDisplay = <<<ENDASSOCDISPLAY
			<h3>Add a resource to {$object->getTitle()}</h3>
			<table>
				<tr><td colspan="2">THIS IS NOT WORKING YET</td></tr>
				<tr><td>Resource URL: &nbsp;<span style="color:#333;">http://</span></td>
					<td><script type="text/javascript">ti('resURL','resURL','',240);</script></td></tr>
				<tr><td>Description:</td>
					<td><script type="text/javascript">ti('resDESC','resDESC','',340);</script></td></tr>
				<tr><td><input type="button" value="Associate" onclick="createBiomarkerResource({$object->getObjId()},$('assocPubAutocompleteID').getValue());"/></td>
					<td><input type="button" value="Cancel" onclick="Effect.SwitchOff('associationContainer');"/></td></tr>
			</table>
ENDASSOCDISPLAY;



	$resDisplay = '';
	if (sizeof($object->getResources()) == 0){
		$resDisplay = 'There are no resources associated with this biomarker.';
	} else {
		foreach ($object->getResources() as $res){
			$resDisplay .=  <<<ENDPUBDISPLAY
				<div class="overview">
					<h3><a href="publication.php?view=basics&objId={$pd->getObjId()}">{$pd->getTitle()}</a>&nbsp;<span class="titleAction">(delete)</span></h3>
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
		<h4>Associated Resources:</h4>
		<div class="overviewContainer">
			{$resDisplay}	
		</div>
	
ENDDISPLAY;
	
	echo <<<ENDACTIONS
		<div class="specialactions">
			<ul>
			  <li><span class="pseudolink grey" onclick="Effect.Appear('associationContainer');">Associate a resource</span</li>
			</ul>
		</div>
ENDACTIONS

?>