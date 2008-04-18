<?php
/**
 * EDRN Biomarker Database
 * Curation Webapp
 * 
 * Author: Andrew F. Hart (andrew.f.hart@jpl.nasa.gov)
 * See AUTHORS file for list of members of the JPL EDRN Project Team
 * 
 * Copyright 2008, by the California Institute of Technology. ALL RIGHTS RESERVED. 
 * United States Government Sponsorship acknowledged. Any commercial use must be 
 * negotiated with the Office of Technology Transfer at the California Institute 
 * of Technology.
 * 
 * This software may be subject to U.S. export control laws and regulations.  
 * By accepting this document, the user agrees to comply with all applicable 
 * U.S. export laws and regulations.  User has the responsibility to obtain 
 * export licenses, or other export authority as may be required before 
 * exporting such information to foreign countries or providing access to 
 * foreign persons.
 * 
******************************************************************************/

	require_once("xpress/app.php");
	
	// Page Header Setup
	$p = new XPressPage(App::NAME." ".App::VERSION,"text/html","UTF-8");
	$p->includeCSS('static/css/frozen.css');
	$p->open();
	$p->view()->LoadTemplate("view/homepage.html");
	$p->view()->Show();
	$p->close();
	
	exit();


	require_once("assets/skins/edrn/prologue.php");
?>
<div class="main">
<!-- Breadcrumbs Area -->
	<div class="mainContent" style="padding-bottom:0px;margin-bottom:0px;border-bottom:solid 3px #a0a0a0;padding:3px;color:#666;">
<?php 
	echo "Home ";
?>
	</div><!-- End Breadcrumbs -->
	<div class="mainContent">
	<h2 class="title" style="margin-bottom:13px;">EDRN Biomarker Database</h2>
	<p style="padding-left:10px;width:625px;text-align:justify;line-height:16px;">
	Welcome to the EDRN Biomarker Database. Browse the 
	database objects and their relationships by clicking on one of 
	the links below. Note that to make any changes to the information, 
	you must be logged in first.</p>
		<div class="optionsContainer" style="padding-left:10px;">
			<div class="option">
				<h4>Biomarkers</h4>
				<ul><li><a href="browse/biomarkers/">View Existing Biomarkers</a></li>
					<li><span class="pseudolink" onclick="Element.hide('newStudy');Element.hide('newPublication');Effect.Appear('newBiomarker');">Create a new Biomarker</span></li>
				</ul>
			</div>
			<div class="option">
				<h4>Studies</h4>
				<ul><li><a href="browse/studies/">View Existing Studies</a></li>
					<li><span class="pseudolink" onclick="Element.hide('newBiomarker');Element.hide('newPublication');Effect.Appear('newStudy');">Create a new Study</span></li>
				</ul>
			</div>
			<div class="option">
				<h4>Publications</h4>
				<ul><li><a href="browse/publications/">View Existing Publications</a></li>
					<li><a href="util/importpubmed.php">Import a Publication</a></li>
					<!--<li><span class="pseudolink" onclick="Element.hide('newStudy');Element.hide('newBiomarker');Effect.Appear('newPublication');">Create a new Publication</span></li>-->
				</ul>
			</div>
			<div style="clear:both;"></div>
		</div>
		<!-- Create New Biomarker -->
		<div class="createnew associationContainer" id="newBiomarker" style="padding-left:10px;display:none;">
			<h3>Create new Biomarker:</h3>
			<form action="biomarker.php" method="post">
			<input type="hidden" name="special" value="createnew"/>
			Biomarker Title (Long Name):
			<input type="text" name="title" style="width:300px;"/>
			<input type="submit" value="Create!"/>&nbsp;
			<span class="pseudolink" onclick="Element.hide('newBiomarker');">Cancel</span>
			</form>
		</div>
		<!-- Create New Study -->
		<div class="createnew associationContainer" id="newStudy" style="padding-left:10px;display:none;">
			<h3>Create new Study:</h3>
			<form action="study.php" method="post">
			<input type="hidden" name="special" value="createnew"/>
			Study Title:
			<input type="text" name="title" style="width:300px;"/>
			<input type="submit" value="Create!"/>&nbsp;
			<span class="pseudolink" onclick="Element.hide('newStudy');">Cancel</span>
			</form>
		</div>
		<!-- Create New Publication -->
		<div class="createnew associationContainer" id="newPublication" style="padding-left:10px;display:none;">
			<h3>Create new Publication:</h3>
			<form action="publication.php" method="post">
			<input type="hidden" name="special" value="createnew"/>
			Publication Title:
			<input type="text" name="title" style="width:300px;"/>
			<input type="submit" value="Create!"/>&nbsp;
			<span class="pseudolink" onclick="Element.hide('newPublication');">Cancel</span>
			</form>
		</div>
	</div>
</div>
<?php
	require_once("assets/skins/edrn/epilogue.php");
	$p->drawFooter();
?>