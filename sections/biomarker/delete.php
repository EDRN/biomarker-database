<?php
	require_once("utilities/common/AjaxEditors.class.php");

	echo <<<ENDDISPLAY
		<h4>Confirm Delete:</h4>
		<table class="ajaxEdits">
			<tr class="even"><td class="label">Title:</td><td>{$titleEditor}</td></tr>
			<tr><td class="label">Identifier:</td><td>{$urnEditor}</td></tr>
			<tr class="even"><td class="label">Phase:</td><td>{$phaseEditor}</td></tr>
			<tr><td class="label">Security:</td><td>{$securityEditor}</td></tr>
			<tr class="even"><td class="label">Type:</td><td>{$typeEditor}</td></tr>
			<tr><td class="label">Description:</td><td>{$descriptionEditor}</td></tr>
		</table>
ENDDISPLAY;
	
	echo <<<ENDACTIONS
		<div class="specialactions">
			<ul>
			  <li><a href="browse/biomarkers/">Browse Biomarkers</a></li>
			  <li><a href="#">Delete This Biomarker</a></li>
			</ul>
		</div>
ENDACTIONS
?>