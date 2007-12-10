<?php
	$boDisplay = '';
	if (sizeof($object->getBiomarkerOrganDatas()) == 0){
		$boDisplay = 'There is no biomarker/organ data associated with this study.';
	} else {
		foreach ($object->getBiomarkerOrganDatas() as $od){
			$boDisplay .=  <<<ENDORGANDISPLAY
				<div class="overview">
					<h3><a href="biomarkerorgan.php?view=basics&objId={$od->getBiomarkerOrganData()->getObjId()}">{$od->getBiomarkerOrganData()->getBiomarker()->getTitle()}/{$od->getBiomarkerOrganData()->getOrgan()->getName()}</a></h3>
					<div style="padding-left:9px;">
						<table>
							<tr><td>Biomarker:</td><td><a href="biomarker.php?objId={$od->getBiomarkerOrganData()->getBiomarker()->getObjId()}&view=basics">{$od->getBiomarkerOrganData()->getBiomarker()->getTitle()}</a></td></tr>
							<tr><td>Organ Site:</td><td>{$od->getBiomarkerOrganData()->getOrgan()->getName()}</td></tr>
						</table>
					</div>
				</div>
ENDORGANDISPLAY;
		}
	}
	

	echo <<<ENDDISPLAY
		<div id="notice"></div>
		<h4>Associated Biomarker/Organs:</h4>
		<div class="overviewContainer">
			{$boDisplay}	
		</div>
	
ENDDISPLAY;
	
	echo <<<ENDACTIONS
		<div class="specialactions">
			<ul>
			</ul>
		</div>
ENDACTIONS

?>