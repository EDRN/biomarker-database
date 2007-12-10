<?php
	require_once("utilities/common/AjaxEditors.class.php");
	$titleEditor = AjaxEditors::create('model/AjaxHandler.php',$object->getTitle(),"title","title",$object->_getType(),$object->getObjId(),PublicationVars::PUB_TITLE);
	$authorEditor   = AjaxEditors::create('model/AjaxHandler.php',$object->getAuthor(),"auth","auth",$object->_getType(),$object->getObjId(),PublicationVars::PUB_AUTHOR);
	$journalEditor  = AjaxEditors::create('model/AjaxHandler.php',$object->getJournal(),"journal","journal",$object->_getType(),$object->getObjId(),PublicationVars::PUB_JOURNAL);
	$volumeEditor   = AjaxEditors::create('model/AjaxHandler.php',$object->getVolume(),"volume","volume",$object->_getType(),$object->getObjId(),PublicationVars::PUB_VOLUME);
	$issueEditor    = AjaxEditors::create('model/AjaxHandler.php',$object->getIssue(),"issue","issue",$object->_getType(),$object->getObjId(),PublicationVars::PUB_ISSUE);
	$yearEditor     = AjaxEditors::create('model/AjaxHandler.php',$object->getYear(),"year","year",$object->_getType(),$object->getObjId(),PublicationVars::PUB_YEAR);
	
	echo <<<ENDDELETEDISPLAY
		<div class="associationContainer warn delete" id="deletePub" style="display:none;">
			<h3>Confirm Delete</h3>
			Are you sure you want to delete this Publication? This action can not be undone.<br/><br/>
			<form action="publication.php" method="post">
			<input type="hidden" name="special" value="delete"/>
			<input type="hidden" name="objId" value="{$object->getObjId()}"/>
			<input type="submit" value="Delete"/>&nbsp;
			<span class="pseudolink" onclick="Element.hide('deletePub');">Cancel</span>
		</div>
ENDDELETEDISPLAY;

	echo <<<ENDDISPLAY
		<h4>Basic Information:</h4>
		<table class="ajaxEdits">
			<tr class="even"><td class="label">Title:</td><td>{$titleEditor}</td></tr>
			<tr><td class="label">Author:</td><td>{$authorEditor}</td></tr>
			<tr class="even"><td class="label">Journal:</td><td>{$journalEditor}</td></tr>
			<tr><td class="label">Volume:</td><td>{$volumeEditor}</td></tr>
			<tr class="even"><td class="label">Issue:</td><td>{$issueEditor}</td></tr>
			<tr><td class="label">Year:</td><td>{$yearEditor}</td></tr>
		</table>
ENDDISPLAY;
	
	echo <<<ENDACTIONS
		<div class="specialactions">
			<ul>
			  <li><a href="browse/publications/">Browse Publications</a></li>
			  <li><span class="pseudolink grey" onclick="Effect.Appear('deletePub');">Delete This Publication</span></li>
			</ul>
		</div>
ENDACTIONS
?>