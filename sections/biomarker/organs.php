<?php
	$organDisplay = '';
	if (sizeof($object->getOrganDatas()) == 0){
		$organDisplay = 'There is no organ data associated with this biomarker.';
	} else {
		foreach ($object->getOrganDatas() as $od){
			$organDisplay .=  <<<ENDORGANDISPLAY
				<div class="overview">
					<h3><a href="biomarkerorgan.php?view=basics&objId={$od->getObjId()}">{$od->getOrgan()->getName()}</a>&nbsp;<span class="titleAction">(delete)</span></h3>
					<table>
						<tr><td>Sensitivity (Min/Max): </td><td>{$od->getSensitivityMin()} / {$od->getSensitivityMax()}</td></tr>
						<tr><td>Specificity (Min/Max): </td><td>{$od->getSpecificityMin()} / {$od->getSpecificityMax()}</td></tr>
						<tr><td>Negative Predictive Value (Min/Max): </td><td>{$od->getNPVMin()} / {$od->getNPVMax()}</td></tr>
						<tr><td>Positive Predictive Value (Min/Max): </td><td>{$od->getSpecificityMin()} / {$od->getSpecificityMax()}</td></tr>
					</table>
				</div>
ENDORGANDISPLAY;
		}
	}
	
	$associateDisplay = <<<ENDASSOCDISPLAY
			<h3>Add organ data to {$object->getTitle()}</h3>
			Organ:&nbsp;
				<select id="organChoice">
					<option id="1" value="1">Lung</option>
					<option id="2" value="2">Colon</option>
					<option id="3" value="3">Liver</option>
					<option id="4" value="4">Bladder</option>
				</select>&nbsp;
				<input type="button" value="Associate" onclick="createBiomarkerOrganData({$object->getObjId()},$('organChoice').getValue());"/>
				<input type="button" value="Cancel" onclick="Effect.SwitchOff('associationContainer');"/>
ENDASSOCDISPLAY;
	

	echo <<<ENDDISPLAY
		<div class="associationContainer" id="associationContainer" style="display:none;">
			{$associateDisplay}
		</div>
		<div id="notice"></div>
		<h4>Associated Organs:</h4>
		<div class="overviewContainer">
			{$organDisplay}	
		</div>
	
ENDDISPLAY;
	
	echo <<<ENDACTIONS
		<div class="specialactions">
			<ul>
			  <li><span class="pseudolink grey" onclick="Effect.Appear('associationContainer');">Associate organ data</span></li>
			</ul>
		</div>
ENDACTIONS

?>